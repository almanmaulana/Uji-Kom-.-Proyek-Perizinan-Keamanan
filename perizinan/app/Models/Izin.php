<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Izin extends Model
{
    use HasFactory;

    public static $tanggalSimulasi = null;

    protected $table = 'izin';

    protected $fillable = [
        'santri_id',
        'jenis_izin',
        'alasan',
        'status',
        'catatan',
        'tgl_mulai_disetujui',
        'tgl_selesai_disetujui',
        'status_lapor',
        'denda',
        'status_denda',
        'tanggal_dibayar'
    ];

    protected $casts = [
        'tgl_mulai_disetujui' => 'datetime:Y-m-d',
        'tgl_selesai_disetujui' => 'datetime:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function today()
    {
        return self::$tanggalSimulasi
            ? Carbon::parse(self::$tanggalSimulasi)
            : now();
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /** 🔥 ACCESSOR DURASI */
    public function getDurasiAttribute()
    {
        if (!$this->tgl_mulai_disetujui || !$this->tgl_selesai_disetujui) {
            return '-';
        }

        return Carbon::parse($this->tgl_mulai_disetujui)->format('d/m/Y')
            .' - '.
            Carbon::parse($this->tgl_selesai_disetujui)->format('d/m/Y');
    }

    // (fungsi denda tetap)
public function getDendaBerjalanAttribute()
{
    // Jika sudah lapor → JANGAN PERNAH hitung ulang lagi
    if ($this->status_lapor === 'sudah_lapor') {
        return $this->denda; // FIX PERMANEN
    }

    // Belum lapor → dihitung
    if (!$this->tgl_selesai_disetujui) return 0;

    if (self::today()->greaterThan($this->tgl_selesai_disetujui)) {
        return $this->tgl_selesai_disetujui->diffInDays(self::today()) * 15000;
    }

    return 0;
}


    public function kunciDenda()
    {
        // Hitung denda berjalan
        $dendaBerjalan = $this->denda_berjalan;

        // Jika lebih besar dari yang sudah tersimpan, simpan
        if ($dendaBerjalan > 0) {
            $this->denda = $dendaBerjalan;
        }

        // Update status denda menjadi belum dibayar
        $this->status_denda = 'belum_dibayar';

        $this->save();
    }


}
