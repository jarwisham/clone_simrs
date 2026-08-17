<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

// Laporan & Riwayat Presensi Routes
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/riwayat-presensi', [LaporanController::class, 'index'])->name('riwayat.index');
Route::get('/laporan/download', [LaporanController::class, 'download'])->name('laporan.download');
