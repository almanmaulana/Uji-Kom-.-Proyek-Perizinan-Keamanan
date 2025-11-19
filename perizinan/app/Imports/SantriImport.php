<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Validators\Failure;

class SantriImport implements ToModel, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    private $nisSeen = [];

    public function headingRow(): int
    {
        return 1;
    }

    private function normalizeGender($gender)
{
    if (!$gender) return 'L'; // default

    $g = strtolower(trim($gender));

    $map = [
        'l' => 'L',
        'laki' => 'L',
        'laki-laki' => 'L',
        'lakilaki' => 'L',

        'p' => 'P',
        'perempuan' => 'P',
        'wanita' => 'P',
    ];

    return $map[$g] ?? 'L'; // default ke L kalau tidak dikenali
}


    private function normalizeTingkat($tingkat)
    {
        $map = [
            'VII'  => '7',
            'VIII' => '8',
            'IX'   => '9',
            'X'    => '10',
            'XI'   => '11',
            'XII'  => '12',
        ];
        $t = strtoupper(trim($tingkat));
        return $map[$t] ?? $t;
    }

    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER);

        try {
            if (empty($row['nis']) || empty($row['nama'])) {
                throw new \Exception("❌ Kolom 'NIS' dan 'Nama' wajib diisi.");
            }

            $tingkat = $this->normalizeTingkat($row['kelas'] ?? '');
            $jenjang = strtoupper(trim($row['jenjang'] ?? ''));
            $jurusan = strtoupper(trim($row['jurusan'] ?? ''));

            // Cek kelas berdasarkan jenjang, tingkat, dan jurusan
            $kelas = Kelas::whereRaw('LOWER(jenjang) = ?', [strtolower($jenjang)])
                ->where('tingkat', $tingkat)
                ->when(!empty($jurusan), function ($q) use ($jurusan) {
                    $q->whereRaw('LOWER(jurusan) = ?', [strtolower($jurusan)]);
                })
                ->first();

            if (!$kelas) {
                throw new \Exception("❌ Kelas tidak ditemukan untuk: {$jenjang} {$tingkat} {$jurusan}");
            }

            // Cek NIS duplikat di file Excel
            if (in_array($row['nis'], $this->nisSeen)) {
                throw new \Exception("⚠️ NIS {$row['nis']} duplikat di file Excel.");
            }
            $this->nisSeen[] = $row['nis'];

            // Cek NIS duplikat di database
            if (Santri::where('nis', $row['nis'])->exists()) {
                throw new \Exception("⚠️ NIS {$row['nis']} sudah ada di database.");
            }

            // Temukan wali berdasarkan kode_keluarga (jika ada)
            $wali = null;
            $kodeKeluarga = trim($row['kode_keluarga'] ?? '');

            if (!empty($kodeKeluarga)) {
                $wali = User::where('kode_keluarga', $kodeKeluarga)
                    ->where('role', 'wali_santri')
                    ->first();

                if (!$wali) {
                    Log::warning("⚠️ Tidak ditemukan wali dengan kode_keluarga '{$kodeKeluarga}'.", $row);
                }
            }

            Log::info('✅ Importing santri:', $row);

            return new Santri([
                'nis'             => trim($row['nis']),
                'nama'            => trim($row['nama']),
                'jenis_kelamin'   => $this->normalizeGender($row['jenis_kelamin'] ?? 'L'),
                'kelas_id'        => $kelas->id,
                'wali_santri_id'  => $wali->id ?? null,
                'alamat'          => trim($row['alamat'] ?? '-'),
                'kode_keluarga'   => $kodeKeluarga ?: null,
            ]);
        } catch (\Exception $e) {
            $this->onFailure(new Failure(
                0,
                'import',
                [$e->getMessage()],
                $row
            ));
            return null;
        }
    }
}
