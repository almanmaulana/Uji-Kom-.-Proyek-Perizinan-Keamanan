<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Santri;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'kode_keluarga',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

        public function waliKelas()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function santriAsWali()
    {
        return $this->hasMany(Santri::class, 'wali_santri_id');
    }

        public function hasRole($role)
    {
        return $this->role === $role;
    }



}
