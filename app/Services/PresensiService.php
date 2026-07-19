<?php

namespace App\Services;

use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Collection;

class PresensiService
{
    public function getRekapByDate(\DateTimeInterface $date): Collection
    {
        return Presensi::with('siswa')
            ->forDate($date)
            ->get()
            ->groupBy(function (Presensi $presensi) {
                return $presensi->siswa->orang_tua_id;
            });
    }

    public function getBySiswa(Siswa $siswa): Collection
    {
        return $siswa->presensis()->orderByDesc('tanggal')->get();
    }

    public function getByOrangTua(User $orangTua): Collection
    {
        return Presensi::whereHas('siswa', function ($query) use ($orangTua) {
            $query->where('orang_tua_id', $orangTua->id);
        })->with('siswa')->orderByDesc('tanggal')->get();
    }

    public function initializeDailyAttendance(Siswa $siswa, \DateTimeInterface $date): Presensi
    {
        return $siswa->presensis()->firstOrCreate(
            ['tanggal' => $date],
            ['status' => 'alpha']
        );
    }

    public function markPresent(Siswa $siswa, \DateTimeInterface $date): Presensi
    {
        return $siswa->presensis()->updateOrCreate(
            ['tanggal' => $date],
            ['status' => 'hadir']
        );
    }
}
