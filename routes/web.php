<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\LaporanController;

//guest
Route::middleware(['guest'])->group(function(){
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// memproses form login setelah submit
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

//Login dulu
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Pegawai (CRUD) (admin)
    Route::middleware(['user-role:admin'])->group(function(){
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    // Fitur approval cuti dari sisi admin
    Route::post('/cuti/{id}/setujui', [CutiController::class, 'setujui'])->name('cuti.setujui');
    Route::post('/cuti/{id}/tolak', [CutiController::class, 'tolak'])->name('cuti.tolak');
    });

    // 3. Cuti (admin maupun pegawai)
    Route::get('/cuti', [CutiController::class, 'index'])->name('cuti.index');
    Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');

    // 4. Laporan (Hanya Admin yang bisa lihat lewat sidebar)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // 5. Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});