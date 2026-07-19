<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Services\NilaiService;
use App\Services\PresensiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function __construct(
        private readonly NilaiService $nilaiService,
        private readonly PresensiService $presensiService,
    ) {}

    public function index(Request $request): View
    {
        $data = $this->nilaiService->getByOrangTua($request->user());

        return view('orang-tua.nilai.index', compact('data'));
    }

    public function show(Request $request, Siswa $siswa): View
    {
        $this->authorizeSiswaAccess($request->user(), $siswa);

        $nilai = $this->nilaiService->getBySiswa($siswa);
        $presensi = $this->presensiService->getBySiswa($siswa);

        return view('orang-tua.nilai.show', compact('siswa', 'nilai', 'presensi'));
    }

    private function authorizeSiswaAccess($user, Siswa $siswa): void
    {
        if ($siswa->orang_tua_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke data siswa ini.');
        }
    }
}
