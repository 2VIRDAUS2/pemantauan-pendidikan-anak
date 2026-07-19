@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Input Nilai Siswa</h1>
        <p class="text-gray-500 mt-1">Tambahkan dan kelola nilai siswa.</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Tambah Nilai Baru</h2>
        </div>

        <form method="POST" action="{{ route('guru.nilai.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">Siswa</label>
                    <select name="siswa_id" id="siswa_id" required
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                        <option value="">Pilih Siswa</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} (Kelas {{ $siswa->kelas }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" id="mata_pelajaran" required placeholder="Contoh: Matematika"
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors placeholder:text-gray-400">
                </div>
                <div>
                    <label for="skor" class="block text-sm font-medium text-gray-700 mb-2">Skor</label>
                    <input type="number" name="skor" id="skor" min="0" max="100" step="0.01" required placeholder="0 - 100"
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors placeholder:text-gray-400">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2.5 bg-indigo-500 text-white text-sm font-semibold rounded-xl hover:bg-indigo-600 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Nilai
                    </button>
                </div>
            </div>
            @error('siswa_id')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
            @error('mata_pelajaran')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
            @error('skor')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Data Nilai Siswa</h2>
        </div>

        @php
            $grouped = $siswas->filter(fn($s) => $s->nilais->count() > 0)->sortBy('nama_siswa');
        @endphp

        @forelse($grouped as $siswa)
        <div class="mb-4 last:mb-0">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                    {{ substr($siswa->nama_siswa, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $siswa->nama_siswa }}</p>
                    <p class="text-xs text-gray-400">Kelas {{ $siswa->kelas }}</p>
                </div>
            </div>
            <div class="ml-11 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach($siswa->nilais as $nilai)
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-2.5">
                    <span class="text-sm text-gray-600">{{ $nilai->mata_pelajaran }}</span>
                    <span class="text-sm font-bold @if($nilai->skor >= 75) text-emerald-600 @elseif($nilai->skor >= 50) text-amber-600 @else text-red-600 @endif">
                        {{ $nilai->skor }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-400 text-sm">Belum ada data nilai yang tercatat</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
