<?php

namespace App\Models;

use App\Http\Controllers\SantriController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\table;

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
    'nis',
    'nama',
    'foto',
    'jenis_kelamin',
    'jenjang',
    'kelas_id',
    'wali_santri_id',
    'kode_keluarga',
    'alamat'
];

    protected $table = 'santris';


        public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function waliSantri()
    {
        return $this->belongsTo(User::class, 'wali_santri_id');
    }

}
