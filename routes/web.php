<?php

use App\Http\Controllers\AuthPesertaMagangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobTrainController;
use App\Http\Controllers\PesertaMagangController;
use App\Http\Controllers\LokasiKantorController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group([
    'prefix' => 'login-peserta-magang',
    'middleware' => ['guest', 'login-peserta-magang'],
], function () {
    Route::get('/', [AuthPesertaMagangController::class, 'create'])->name('login.view');
    Route::post('/', [AuthPesertaMagangController::class, 'store'])->name('login.auth');
});

Route::group([
    'prefix' => 'peserta_magang',
    'middleware' => ['peserta_magang'],
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/logout', [AuthPesertaMagangController::class, 'destroy'])->name('logout.auth');

    Route::group([
        'prefix' => 'presensi',
    ], function () {
        Route::get('/', [PresensiController::class, 'index'])->name('peserta_magang.presensi');
        Route::post('/', [PresensiController::class, 'store'])->name('peserta_magang.presensi.store');

        Route::group([
            'prefix' => 'history',
        ], function () {
            Route::get('/', [PresensiController::class, 'history'])->name('peserta_magang.history');
            Route::post('/search-history', [PresensiController::class, 'searchHistory'])->name('peserta_magang.history.search');
        });

        Route::group([
            'prefix' => 'izin',
        ], function () {
            Route::get('/', [PresensiController::class, 'pengajuanPresensi'])->name('peserta_magang.izin');
            Route::get('/pengajuan-presensi', [PresensiController::class, 'pengajuanPresensiCreate'])->name('peserta_magang.izin.create');
            Route::post('/pengajuan-presensi', [PresensiController::class, 'pengajuanPresensiStore'])->name('peserta_magang.izin.store');
            Route::post('/search-history', [PresensiController::class, 'searchPengajuanHistory'])->name('peserta_magang.izin.search');
        });
    });

    Route::group([
        'prefix' => 'profile',
    ], function () {
        Route::get('/', [PesertaMagangController::class, 'index'])->name('peserta_magang.profile');
        Route::post('/update', [PesertaMagangController::class, 'update'])->name('peserta_magang.profile.update');
    });
});

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth'],
], function () {
    Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('admin.dashboard');

    Route::get('/peserta_magang', [PesertaMagangController::class, 'indexAdmin'])->name('admin.peserta_magang');
    Route::post('/peserta_magang/tambah', [PesertaMagangController::class, 'store'])->name('admin.peserta_magang.store');
    Route::get('/peserta_magang/perbarui', [PesertaMagangController::class, 'edit'])->name('admin.peserta_magang.edit');
    Route::post('/peserta_magang/perbarui', [PesertaMagangController::class, 'updateAdmin'])->name('admin.peserta_magang.update');
    Route::post('/peserta_magang/hapus', [PesertaMagangController::class, 'delete'])->name('admin.peserta_magang.delete');

    Route::get('/jobtrain', [JobTrainController::class, 'index'])->name('admin.jobtrain');
    Route::post('/jobtrain/tambah', [JobTrainController::class, 'store'])->name('admin.jobtrain.store');
    Route::get('/jobtrain/perbarui', [JobTrainController::class, 'edit'])->name('admin.jobtrain.edit');
    Route::post('/jobtrain/perbarui', [JobTrainController::class, 'update'])->name('admin.jobtrain.update');
    Route::post('/jobtrain/hapus', [JobTrainController::class, 'delete'])->name('admin.jobtrain.delete');

    Route::get('/monitoring-presensi', [PresensiController::class, 'monitoringPresensi'])->name('admin.monitoring-presensi');
    Route::post('/monitoring-presensi', [PresensiController::class, 'viewLokasi'])->name('admin.monitoring-presensi.lokasi');

    Route::get('/laporan/presensi', [PresensiController::class, 'laporan'])->name('admin.laporan.presensi');
    Route::post('/laporan/presensi/peserta_magang', [PresensiController::class, 'laporanPresensiPesertaMagang'])->name('admin.laporan.presensi.peserta_magang');
    Route::post('/laporan/presensi/semua-peserta_magang', [PresensiController::class, 'laporanPresensiSemuaPesertaMagang'])->name('admin.laporan.presensi.semua-peserta_magang');

    Route::get('/lokasi', [LokasiKantorController::class, 'index'])->name('admin.lokasi-kantor');
    Route::post('/lokasi/tambah', [LokasiKantorController::class, 'store'])->name('admin.lokasi-kantor.store');
    Route::get('/lokasi/perbarui', [LokasiKantorController::class, 'edit'])->name('admin.lokasi-kantor.edit');
    Route::post('/lokasi/perbarui', [LokasiKantorController::class, 'update'])->name('admin.lokasi-kantor.update');
    Route::post('/lokasi/hapus', [LokasiKantorController::class, 'delete'])->name('admin.lokasi-kantor.delete');

    Route::get('/administrasi-presensi', [PresensiController::class, 'indexAdmin'])->name('admin.administrasi-presensi');
    Route::post('/administrasi-presensi/status', [PresensiController::class, 'persetujuanPresensi'])->name('admin.administrasi-presensi.persetujuan');
});

require __DIR__.'/auth.php';
