<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifikasiIzinRequest;
use App\Models\PengajuanIzin;
use App\Services\PengajuanIzinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerifikasiIzinController extends Controller
{
    public function __construct(
        private readonly PengajuanIzinService $izinService,
    ) {}

    public function index(): View
    {
        $data = $this->izinService->getPendingVerifications();

        return view('guru.verifikasi-izin.index', compact('data'));
    }

    public function update(
        VerifikasiIzinRequest $request,
        PengajuanIzin $pengajuanIzin
    ): RedirectResponse {
        $status = $request->input('status');

        if ($status === 'approved') {
            $this->izinService->approve($pengajuanIzin, $request->user());
        } else {
            $this->izinService->reject($pengajuanIzin, $request->user());
        }

        return redirect()
            ->route('guru.verifikasi-izin.index')
            ->with('success', 'Pengajuan izin berhasil diverifikasi.');
    }
}
