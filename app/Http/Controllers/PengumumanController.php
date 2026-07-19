<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Services\PengumumanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function __construct(
        private readonly PengumumanService $pengumumanService,
    ) {}

    public function index(Request $request): View
    {
        if ($request->user()->isGuru()) {
            $data = $this->pengumumanService->getAll();
        } else {
            $kelas = $request->user()->siswas->first()?->kelas;
            $data = $this->pengumumanService->getAktif($kelas);
        }

        return view('shared.pengumuman.index', compact('data'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'isi' => 'required|string|max:2000',
            'target_kelas' => 'nullable|string|max:10',
        ]);

        $this->pengumumanService->store($validated, $request->user());

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function toggleStatus(Pengumuman $pengumuman): RedirectResponse
    {
        $this->pengumumanService->toggleStatus($pengumuman);

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Status pengumuman berhasil diubah.');
    }
}
