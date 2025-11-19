<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user keamanan default
        User::firstOrCreate(
            ['email' => 'keamanan@test.com'],
            [
                'nama' => 'Keamanan',
                'password' => Hash::make('password123'),
                'role' => 'keamanan',
                'no_hp' => '08123456789',
            ]
        );

        // Buat user wali kelas default
        User::firstOrCreate(
            ['email' => 'walikelas@test.com'],
            [
                'nama' => 'Wali Kelas',
                'password' => Hash::make('password123'),
                'role' => 'wali_kelas',
                'no_hp' => '08123456780',
            ]
        );

        // Buat user wali santri default
        User::firstOrCreate(
            ['email' => 'walisantri@test.com'],
            [
                'nama' => 'Wali Santri',
                'password' => Hash::make('password123'),
                'role' => 'wali_santri',
                'no_hp' => '08123456781',
            ]
        );
    }
}
