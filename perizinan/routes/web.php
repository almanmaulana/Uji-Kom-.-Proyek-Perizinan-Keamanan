<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthWaliSantriController;

// ===============================
// 🔹 HALAMAN UTAMA
// ===============================
Route::get('/', fn() => view('welcome'));

// ===============================
// 🔹 REGISTER WALI SANTRI (Custom)
// ===============================
// Pakai register.blade.php yang sudah kamu ubah
Route::get('/register-wali-santri', [AuthWaliSantriController::class, 'showRegisterForm'])
    ->name('register.walisantri');
Route::post('/register-wali-santri', [AuthWaliSantriController::class, 'register'])
    ->name('register.walisantri.store');

// ===============================
// 🔹 DASHBOARD
// ===============================
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ===============================
// 🔹 ROUTE DENGAN AUTH
// ===============================
Route::middleware(['auth'])->group(function () {

        // ===============================
    // 🔹 HALAMAN PROFIL USER LOGIN
    // ===============================
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/photo', [UserController::class, 'updatePhoto'])->name('profile.photo');
    });

    // =======================================
    // ROLE: KEAMANAN & WALI KELAS
    // =======================================
    Route::middleware(['role:keamanan,wali_kelas'])->group(function () {

        // Kelas
        Route::resource('kelas', KelasController::class);
        Route::get('/kelas/by-jenjang/{jenjang}', [KelasController::class, 'byJenjang'])
            ->name('kelas.byJenjang');

        // Wali Santri (autocomplete)
        Route::get('/wali-santri/search', [UserController::class, 'searchWaliSantri'])
            ->name('waliSantri.search');

        // Santri
        Route::post('/santri/import', [SantriController::class, 'import'])->name('santri.import');
        Route::resource('santri', SantriController::class)->where(['santri' => '[0-9]+']);
        Route::post('/santri/bulk-move', [SantriController::class, 'bulkMove'])->name('santri.bulkMove');
    });

    // =======================================
    // ROLE: KEAMANAN
    // =======================================
    Route::middleware(['role:keamanan'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulkDelete');

        Route::delete('/santri/bulk-delete', [SantriController::class, 'bulkDelete'])->name('santri.bulkDelete');

        Route::get('/cek-kode-keluarga', [App\Http\Controllers\SantriController::class, 'cekKodeKeluarga'])->name('santri.cekKodeKeluarga');

        // Laporan Izin
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

        // Izin Keamanan
        Route::prefix('izin/keamanan')->name('izin.keamanan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Keamanan\IzinController::class, 'index'])->name('index');
            Route::post('/approve/{izin}', [\App\Http\Controllers\Keamanan\IzinController::class, 'approve'])->name('approve');
            Route::post('/reject/{izin}', [\App\Http\Controllers\Keamanan\IzinController::class, 'reject'])->name('reject');
            Route::post('/lapor/{izin}', [\App\Http\Controllers\Keamanan\IzinController::class, 'lapor'])->name('lapor');
            Route::post('/bayar/{izin}', [\App\Http\Controllers\Keamanan\IzinController::class, 'bayarDenda'])->name('bayar');

        });
    });

    // =======================================
    // ROLE: WALI KELAS
    // =======================================
    Route::middleware(['role:wali_kelas'])->prefix('izin/walikelas')->name('izin.walikelas.')->group(function () {
        Route::get('/', [\App\Http\Controllers\WaliKelas\IzinController::class, 'index'])->name('index');
        Route::post('/approve/{izin}', [\App\Http\Controllers\WaliKelas\IzinController::class, 'approve'])->name('approve');
        Route::post('/reject/{izin}', [\App\Http\Controllers\WaliKelas\IzinController::class, 'reject'])->name('reject');
    });

    // =======================================
    // ROLE: WALI SANTRI
    // =======================================
    Route::middleware(['role:wali_santri'])->prefix('izin/walisantri')->name('izin.walisantri.')->group(function () {
        Route::get('/', [\App\Http\Controllers\WaliSantri\IzinController::class, 'index'])->name('index');
        Route::post('/store', [\App\Http\Controllers\WaliSantri\IzinController::class, 'store'])->name('store');
    });

    // =======================================
    // LOGOUT
    // =======================================
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// ===============================
// 🔹 ROUTE AUTH DEFAULT (BREEZE / FORTIFY)
// ===============================
require __DIR__ . '/auth.php';
