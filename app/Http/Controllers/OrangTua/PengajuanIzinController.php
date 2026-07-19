<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengajuanIzinRequest;
use App\Services\PengajuanIzinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengajuanIzinController extends Controller
{
    public function __construct(
        private readonly PengajuanIzinService $izinService,
    ) {}

    public function index(Request $request): View
    {
        $data = $this->izinService->getIzinByOrangTua($request->user());

        return view('orang-tua.pengajuan-izin.index', compact('data'));
    }

    public function create(Request $request): View
    {
        $siswas = $request->user()->siswas;

        return view('orang-tua.pengajuan-izin.create', compact('siswas'));
    }

    public function store(PengajuanIzinRequest $request): RedirectResponse
    {
        $this->izinService->create(
            $request->validated(),
            $request->file('file_bukti')
        );

        return redirect()
            ->route('orang-tua.pengajuan-izin.index')
            ->with('success', 'Pengajuan izin berhasil dikirim.');
    }
}
