<?php

use App\Http\Controllers\AnakController;
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
        $user = auth()->user();
        $siswa = $pengajuanIzin->siswa;

        if ($user->isGuru()) {
            // Guru can view all
        } elseif ($user->isOrangTua() && $siswa->orang_tua_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke berkas ini.');
        } else {
            abort(403);
        }

        return response(base64_decode($pengajuanIzin->file_bukti_data), 200, [
            'Content-Type' => $pengajuanIzin->file_bukti_mime,
            'Content-Disposition' => 'inline; filename="'.$pengajuanIzin->file_bukti_nama.'"',
        ]);
    })->name('bukti-izin.show');

    // --- Legacy Anak CRUD ---
    Route::get('/profil', [AnakController::class, 'ProfilAnak'])->name('profil.anak');
    Route::get('/tambah_anak', [AnakController::class, 'TampilForm'])->name('tambah.anak');
    Route::post('/simpan_anak', [AnakController::class, 'SimpanAnak'])->name('simpan.anak');
    Route::get('/hapus_anak/{id}', [AnakController::class, 'HapusAnak'])->name('hapus.anak');
    Route::get('/edit_anak/{id}', [AnakController::class, 'TampilFormEdit'])->name('edit.anak');
    Route::post('/update_anak/{id}', [AnakController::class, 'UpdateAnak'])->name('update.anak');

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
