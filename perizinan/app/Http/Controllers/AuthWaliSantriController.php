<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthWaliSantriController extends Controller
{
    public function showRegisterForm()
    {
        // ✅ Pakai view bawaan: resources/views/auth/register.blade.php
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'kode_keluarga' => 'required|string',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $kode = $request->kode_keluarga;
        $santris = Santri::where('kode_keluarga', $kode)->get();

        if ($santris->isEmpty()) {
            return back()->withErrors(['kode_keluarga' => '⚠️ Kode keluarga tidak ditemukan.'])->withInput();
        }

        if (User::where('kode_keluarga', $kode)->exists()) {
            return back()->withErrors(['kode_keluarga' => '⚠️ Kode keluarga ini sudah digunakan.'])->withInput();
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'wali_santri',
            'kode_keluarga' => $kode,
        ]);

        // Hubungkan semua santri dengan wali santri baru
        foreach ($santris as $santri) {
            $santri->update(['wali_santri_id' => $user->id]);
        }

        return redirect()->route('login')->with('success', '✅ Akun berhasil dibuat. Silakan login.');
    }
}
