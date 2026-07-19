<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\PresensiController;
use App\Http\Controllers\Guru\VerifikasiIzinController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\OrangTua\DashboardController as OrangTuaDashboardController;
use App\Http\Controllers\OrangTua\NilaiController as OrangTuaNilaiController;
use App\Http\Controllers\OrangTua\PengajuanIzinController;
use App\Http\Controllers\PengumumanController;
use App\Models\PengajuanIzin;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// --- Public ---
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- Authenticated ---
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return auth()->user()->isGuru()
            ? redirect()->route('guru.dashboard')
            : redirect()->route('orang-tua.dashboard');
    })->name('dashboard');

    Route::get('/bukti-izin/{pengajuanIzin}', function (PengajuanIzin $pengajuanIzin) {
        header('Content-Type: '.$pengajuanIzin->file_bukti_mime);
        header('Content-Disposition: inline; filename="'.$pengajuanIzin->file_bukti_nama.'"');
        echo base64_decode($pengajuanIzin->file_bukti_data);
        exit;
    })->name('bukti-izin.show');

    // --- Guru ---
    Route::prefix('guru')->name('guru.')->middleware('role:guru')->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('/verifikasi-izin', [VerifikasiIzinController::class, 'index'])->name('verifikasi-izin.index');
        Route::patch('/verifikasi-izin/{pengajuanIzin}', [VerifikasiIzinController::class, 'update'])->name('verifikasi-izin.update');
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');

        // Absensi
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/presensi', [PresensiController::class, 'update'])->name('presensi.update');
    });

    // --- Orang Tua ---
    Route::prefix('orang-tua')->name('orang-tua.')->middleware('role:orang_tua')->group(function () {
        Route::get('/dashboard', [OrangTuaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengajuan-izin', [PengajuanIzinController::class, 'index'])->name('pengajuan-izin.index');
        Route::get('/pengajuan-izin/create', [PengajuanIzinController::class, 'create'])->name('pengajuan-izin.create');
        Route::post('/pengajuan-izin', [PengajuanIzinController::class, 'store'])->name('pengajuan-izin.store');
        Route::get('/nilai', [OrangTuaNilaiController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/{siswa}', [OrangTuaNilaiController::class, 'show'])->name('nilai.show');
    });

    // --- Shared ---
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::patch('/pengumuman/{pengumuman}/toggle', [PengumumanController::class, 'toggleStatus'])->name('pengumuman.toggle');
});
