<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AncController;
use App\Http\Controllers\PregnancyController; // ✅ Tambahkan import ini

// =============================================================================
// Halaman Publik (tanpa login)
// =============================================================================
Route::get('/', function () {
    return view('welcome');
});

// =============================================================================
// Rute Autentikasi (Login & Logout)
// =============================================================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================================================
// Rute yang Butuh Login + Role Tertentu
// =============================================================================
Route::middleware(['auth', 'role:admin,bidan,dokter'])->group(function () {

    // CRUD Data Pasien
    Route::resource('patients', PatientController::class);

    // -------------------------------------------------------
    // MODUL KEHAMILAN                                        
    // -------------------------------------------------------
    // Daftar kehamilan milik satu pasien
    Route::get('/patients/{patientId}/pregnancies', [PregnancyController::class, 'index'])
        ->name('pregnancies.index');                          // ✅ Tambahkan

    // Detail satu kehamilan + riwayat ANC
    Route::get('/pregnancies/{id}', [PregnancyController::class, 'show'])
        ->name('pregnancies.show');                           // ✅ Tambahkan

    // -------------------------------------------------------
    // MODUL ANC - Kunjungan Pemeriksaan 10T
    // -------------------------------------------------------
    Route::get('/pregnancies/{pregnancyId}/anc/create', [AncController::class, 'create'])
        ->name('anc.create');

    Route::post('/pregnancies/{pregnancyId}/anc', [AncController::class, 'store'])
        ->name('anc.store');

});