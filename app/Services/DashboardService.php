<?php

namespace App\Services;

use App\Models\Siswa;

class DashboardService
{
    public function getOrangTuaStats($user): array
    {
        $siswas = $user->siswas()->with(['presensis', 'nilais', 'pengajuanIzins'])->get();

        $siswaData = $siswas->map(function (Siswa $siswa) {
            $presensiHariIni = $siswa->presensis()
                ->whereDate('tanggal', now()->toDateString())
                ->first();

            $izinPending = $siswa->pengajuanIzins()
                ->where('status', 'pending')
                ->count();

            $rekapBulan = $siswa->presensis()
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->selectRaw('status, count(*) as jumlah')
                ->groupBy('status')
                ->pluck('jumlah', 'status')
                ->toArray();

            return [
                'siswa' => $siswa,
                'presensi_hari_ini' => $presensiHariIni,
                'izin_pending' => $izinPending,
                'rata_rata_nilai' => $siswa->rata_rata_nilai,
                'rekap_bulan' => $rekapBulan,
            ];
        });

        return $siswaData->toArray();
    }
}
