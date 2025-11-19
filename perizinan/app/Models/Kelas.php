<?php

namespace App\Models;

use App\Http\Controllers\SantriController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\table;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'jenjang',
        'tingkat',
        'jurusan',
        'wali_kelas_id',
    ];

    protected $table = 'kelas';


        public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function santris()
    {
        return $this->hasMany(Santri::class, 'kelas_id');
    }

}
