<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanStatusController;
use App\Http\Controllers\PenerusanLaporanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PihakTujuanController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('laporan.index'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::middleware('role:pelapor')->group(function () {
        Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    });

    Route::post('/laporan/{laporan}/tinjau', [TinjauLaporanController::class, 'store'])
        ->middleware('role:relawan')
        ->name('laporan.tinjau');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{laporan}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{laporan}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{laporan}/bukti', [LaporanController::class, 'bukti'])->name('laporan.bukti');

    Route::middleware('role:koordinator')->group(function () {
        Route::post('/laporan/{laporan}/penerusan', [PenerusanLaporanController::class, 'store'])->name('laporan.penerusan');
        Route::patch('/laporan/{laporan}/tutup', [LaporanStatusController::class, 'tutup'])->name('laporan.tutup');

        Route::resource('pihak-tujuan', PihakTujuanController::class)
            ->only(['index', 'store', 'destroy'])
            ->parameters(['pihak-tujuan' => 'pihakTujuan']);

        Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
        Route::post('/wilayah', [WilayahController::class, 'store'])->name('wilayah.store');

        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::patch('/pengguna/{user}/status', [PenggunaController::class, 'toggleStatus'])->name('pengguna.status');
    });
});

require __DIR__ . '/auth.php';