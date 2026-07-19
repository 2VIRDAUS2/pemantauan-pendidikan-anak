<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresensiController extends Controller
{
    public function index(Request $request): View
    {
        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas')->sort()->values();
        $selectedKelas = $request->get('kelas', $kelasList->first() ?? '1');
        $tanggal = $request->get('tanggal', now()->toDateString());

        $siswas = Siswa::where('kelas', $selectedKelas)
            ->with(['presensis' => function ($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal);
            }])
            ->orderBy('nama_siswa')
            ->get();

        return view('guru.presensi.index', compact('kelasList', 'selectedKelas', 'tanggal', 'siswas'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas' => 'required|string|max:10',
            'presensi' => 'required|array',
            'presensi.*.status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        foreach ($request->presensi as $siswaId => $data) {
            Siswa::findOrFail($siswaId)->presensis()->updateOrCreate(
                ['tanggal' => $request->tanggal],
                ['status' => $data['status']]
            );
        }

        return redirect()
            ->route('guru.presensi.index', [
                'kelas' => $request->kelas,
                'tanggal' => $request->tanggal,
            ])
            ->with('success', 'Presensi berhasil disimpan.');
    }
}
