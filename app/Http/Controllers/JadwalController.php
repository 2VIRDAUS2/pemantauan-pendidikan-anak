<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use App\Services\JadwalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function __construct(
        private readonly JadwalService $jadwalService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas')->sort()->values();

        $selectedKelas = $request->get('kelas', $kelasList->first() ?? '1');
        $jadwals = $this->jadwalService->getByKelas($selectedKelas);
        $hariList = $this->jadwalService->getHariList();

        return view('shared.jadwal.index', compact('jadwals', 'selectedKelas', 'kelasList', 'hariList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas' => 'required|string|max:10',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required|string|max:5',
            'jam_selesai' => 'required|string|max:5',
            'mata_pelajaran' => 'required|string|max:100',
        ]);

        $validated['guru_id'] = $request->user()->id;

        $this->jadwalService->store($validated);

        return redirect()
            ->route('jadwal.index', ['kelas' => $validated['kelas']])
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function destroy(JadwalPelajaran $jadwal): RedirectResponse
    {
        $kelas = $jadwal->kelas;
        $jadwal->delete();

        return redirect()
            ->route('jadwal.index', ['kelas' => $kelas])
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
