<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Santri;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    private array $emailsSeen = [];

    public function model(array $row)
    {
        // Pastikan kolom wajib
        if (!isset($row['email'], $row['nama'], $row['password'], $row['role'])) {
            throw ValidationException::withMessages([
                'format' => 'Format Excel tidak sesuai. Wajib ada kolom: nama, email, password, role, kode_keluarga(optional).',
            ]);
        }

        $email = trim(strtolower($row['email']));

        // ❌ Cek duplikat email di file
        if (in_array($email, $this->emailsSeen)) {
            throw ValidationException::withMessages([
                'email' => "Email {$email} duplikat di file Excel.",
            ]);
        }
        $this->emailsSeen[] = $email;

        // ❌ Cek duplikat di database
        if (User::where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => "Email {$email} sudah ada di database.",
            ]);
        }

        // Validasi role
        $validRoles = ['keamanan', 'wali_kelas', 'wali_santri'];
        $role = strtolower(trim($row['role']));
        if (!in_array($role, $validRoles)) {
            throw ValidationException::withMessages([
                'role' => "Role {$row['role']} tidak valid. Pilih: keamanan, wali_kelas, wali_santri.",
            ]);
        }

        // ------------------------------------------------------
        // KHUSUS WALI SANTRI — WAJIB VALIDASI KODE KELUARGA
        // ------------------------------------------------------
        $kodeKeluarga = $row['kode_keluarga'] ?? null;

        if ($role === 'wali_santri') {

            // ❌ Kode keluarga wajib
            if (!$kodeKeluarga) {
                throw ValidationException::withMessages([
                    'kode_keluarga' => "Wali santri wajib mengisi kolom kode_keluarga.",
                ]);
            }

            // ❌ Cek apakah ada di tabel santri
            $santriAda = Santri::where('kode_keluarga', $kodeKeluarga)->exists();
            if (!$santriAda) {
                throw ValidationException::withMessages([
                    'kode_keluarga' => "Kode keluarga {$kodeKeluarga} tidak ditemukan pada data santri.",
                ]);
            }

            // ❌ Cek apakah sudah dipakai wali lain
            $waliSudahAda = User::where('role', 'wali_santri')
                                ->where('kode_keluarga', $kodeKeluarga)
                                ->exists();

            if ($waliSudahAda) {
                throw ValidationException::withMessages([
                    'kode_keluarga' => "Kode keluarga {$kodeKeluarga} sudah digunakan wali santri lain.",
                ]);
            }
        }

        // ------------------------------------------------------
        // SIMPAN USER
        // ------------------------------------------------------
        return new User([
            'nama'          => trim($row['nama']),
            'email'         => $email,
            'password'      => Hash::make($row['password']),
            'role'          => $role,
            'no_hp'         => $row['no_hp'] ?? null,
            'kode_keluarga' => $kodeKeluarga,
        ]);
    }
}
