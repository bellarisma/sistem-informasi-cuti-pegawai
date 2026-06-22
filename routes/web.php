<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\LaporanController;

// ==========================================
// GUEST (Belum Login)
// ==========================================
Route::middleware(['guest'])->group(function(){
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ==========================================
// AUTH (Sudah Login - Bisa Diakses Semua Role)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Halaman Profil Pintar (Pegawai & Admin Bisa Akses)
    Route::get('/profil', [PegawaiController::class, 'editProfil'])->name('profil.edit');
    Route::put('/profil', [PegawaiController::class, 'updateProfil'])->name('profil.update');
    
    // 3. Cuti Umum (Admin melihat semua, Pegawai melihat data sendiri + Form)
    Route::get('/cuti', [CutiController::class, 'index'])->name('cuti.index');
    Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');

    // 4. Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================
    // KHUSUS ROLE: ADMIN (Ditaruh DI DALAM grup auth)
    // ==========================================
    Route::middleware(['user-role:admin'])->group(function(){
    // Cukup rute index, store, update, dan destroy saja
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    Route::post('/cuti/{id}/setujui', [CutiController::class, 'setujui'])->name('cuti.setujui');
    Route::post('/cuti/{id}/tolak', [CutiController::class, 'tolak'])->name('cuti.tolak');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
}); // <-- Tutup grup auth umum di paling bawah