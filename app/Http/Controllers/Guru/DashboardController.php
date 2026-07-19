<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Services\JadwalService;
use App\Services\PengajuanIzinService;
use App\Services\PengumumanService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PengajuanIzinService $izinService,
        private readonly JadwalService $jadwalService,
        private readonly PengumumanService $pengumumanService,
    ) {}

    public function index(): View
    {
        $today = now()->toDateString();
        $stats = [
            'total_siswa' => Siswa::count(),
            'pending_izin' => PengajuanIzin::pending()->count(),
            'approved_bulan' => PengajuanIzin::where('status', 'approved')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'rejected_bulan' => PengajuanIzin::where('status', 'rejected')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'presensi_hari_ini' => Presensi::forDate($today)->count(),
            'hadir_hari_ini' => Presensi::forDate($today)->where('status', 'hadir')->count(),
        ];

        $pendingIzin = $this->izinService->getPendingVerifications()->take(5);

        $recentNilai = Siswa::with('nilais')
            ->has('nilais')
            ->withCount('nilais')
            ->latest()
            ->take(5)
            ->get();

        $pengumumans = $this->pengumumanService->getAll()->take(5);

        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas')->sort()->values();

        return view('guru.dashboard', compact(
            'stats',
            'pendingIzin',
            'recentNilai',
            'pengumumans',
            'kelasList'
        ));
    }
}
