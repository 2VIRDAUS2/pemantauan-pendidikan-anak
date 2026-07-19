<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNilaiRequest;
use App\Models\Siswa;
use App\Services\NilaiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function __construct(
        private readonly NilaiService $nilaiService,
    ) {}

    public function index(): View
    {
        $siswas = Siswa::all();

        return view('guru.nilai.index', compact('siswas'));
    }

    public function store(StoreNilaiRequest $request): RedirectResponse
    {
        $this->nilaiService->store($request->validated());

        return redirect()
            ->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
