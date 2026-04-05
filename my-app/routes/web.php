<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController; // Tambahkan baris ini

Route::get('/', function () {
    return view('welcome');
});

// Tambahkan baris ini untuk mendaftarkan semua rute CRUD pasien
Route::resource('patients', PatientController::class);