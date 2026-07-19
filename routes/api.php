<?php

use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\VerifikasiIzinController;
use App\Http\Controllers\OrangTua\NilaiController as OrangTuaNilaiController;
use App\Http\Controllers\OrangTua\PengajuanIzinController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    // --- Guru API Routes ---
    Route::prefix('guru')->middleware('role:guru')->group(function () {
        Route::get('/verifikasi-izin', [VerifikasiIzinController::class, 'index']);
        Route::patch('/verifikasi-izin/{pengajuanIzin}', [VerifikasiIzinController::class, 'update']);
        Route::post('/nilai', [NilaiController::class, 'store']);
    });

    // --- Orang Tua API Routes ---
    Route::prefix('orang-tua')->middleware('role:orang_tua')->group(function () {
        Route::get('/pengajuan-izin', [PengajuanIzinController::class, 'index']);
        Route::post('/pengajuan-izin', [PengajuanIzinController::class, 'store']);
        Route::get('/nilai', [OrangTuaNilaiController::class, 'index']);
        Route::get('/nilai/{siswa}', [OrangTuaNilaiController::class, 'show']);
    });
});
