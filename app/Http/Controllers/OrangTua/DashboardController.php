<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Services\JadwalService;
use App\Services\PengumumanService;
use App\Services\PresensiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PresensiService $presensiService,
        private readonly JadwalService $jadwalService,
        private readonly PengumumanService $pengumumanService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $siswas = $user->siswas()->with(['presensis', 'nilais', 'pengajuanIzins'])->get();

        $siswaData = $siswas->map(function (Siswa $siswa) {
            $presensiHariIni = $siswa->presensis()
                ->whereDate('tanggal', now()->toDateString())
                ->first();

            $jadwalHariIni = $this->jadwalService->getByKelas($siswa->kelas)
                ->get(now()->translatedFormat('l'));

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
                'jadwal_hari_ini' => $jadwalHariIni,
                'izin_pending' => $izinPending,
                'rata_rata_nilai' => $siswa->rata_rata_nilai,
                'rekap_bulan' => $rekapBulan,
            ];
        });

        $pengumumans = $this->pengumumanService->getAktif(
            $siswas->first()?->kelas
        );

        return view('orang-tua.dashboard', compact('siswaData', 'pengumumans'));
    }
}
