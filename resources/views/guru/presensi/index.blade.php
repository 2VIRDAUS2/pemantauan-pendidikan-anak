@extends('layouts.app')

@section('title', 'Absensi Siswa')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Absensi Siswa</h1>
        <p class="text-gray-500 mt-1">Catat kehadiran siswa per kelas setiap hari.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('guru.presensi.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-48">
                <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                <select name="kelas" id="kelas" onchange="this.form.submit()"
                    class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ $selectedKelas === $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-48">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" max="{{ now()->toDateString() }}"
                    class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </div>
            <div class="text-sm text-gray-400 flex items-center gap-2 py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $siswas->count() }} siswa</span>
            </div>
        </form>
    </div>

    <form method="POST" action="{{ route('guru.presensi.update') }}">
        @csrf
        <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📋</span>
                        <h2 class="text-base font-bold text-white">Kelas {{ $selectedKelas }} &mdash; {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</h2>
                    </div>
                    @if($tanggal === now()->toDateString() || $tanggal < now()->toDateString())
                    <button type="submit" class="px-5 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl backdrop-blur-sm transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Presensi
                    </button>
                    @endif
                </div>
            </div>

            @if(session('success'))
            <div class="mx-6 mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">NISN</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Hadir</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Izin</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Sakit</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($siswas as $siswa)
                            @php
                                $presensi = $siswa->presensis->first();
                                $statusSaatIni = $presensi->status ?? 'alpha';
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-5 py-4 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4 text-gray-600 font-mono text-xs">{{ $siswa->nisn }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">{{ $siswa->nama_siswa }}</p>
                                </td>
                                @foreach(['hadir', 'izin', 'sakit', 'alpha'] as $opt)
                                <td class="px-5 py-4 text-center">
                                    <label class="inline-flex items-center justify-center w-8 h-8 rounded-full cursor-pointer transition-all duration-150
                                        {{ $statusSaatIni === $opt
                                            ? ($opt === 'hadir' ? 'bg-emerald-100 ring-2 ring-emerald-400' : ($opt === 'izin' ? 'bg-yellow-100 ring-2 ring-yellow-400' : ($opt === 'sakit' ? 'bg-blue-100 ring-2 ring-blue-400' : 'bg-red-100 ring-2 ring-red-400')))
                                            : 'bg-gray-50 hover:bg-gray-100 ring-1 ring-gray-200' }}">
                                        <input type="radio" name="presensi[{{ $siswa->id }}][status]" value="{{ $opt }}"
                                            {{ $statusSaatIni === $opt ? 'checked' : '' }}
                                            class="sr-only"
                                            {{ $tanggal !== now()->toDateString() && $tanggal > now()->toDateString() ? 'disabled' : '' }}>
                                        @if($opt === 'hadir')
                                            <svg class="w-5 h-5 {{ $statusSaatIni === $opt ? 'text-emerald-600' : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($opt === 'izin')
                                            <span class="text-sm {{ $statusSaatIni === $opt ? 'text-yellow-600 font-bold' : 'text-gray-300 font-semibold' }}">I</span>
                                        @elseif($opt === 'sakit')
                                            <span class="text-sm {{ $statusSaatIni === $opt ? 'text-blue-600 font-bold' : 'text-gray-300 font-semibold' }}">S</span>
                                        @else
                                            <span class="text-sm {{ $statusSaatIni === $opt ? 'text-red-600 font-bold' : 'text-gray-300 font-semibold' }}">A</span>
                                        @endif
                                    </label>
                                </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <span class="text-4xl">📭</span>
                                    <p class="text-sm text-gray-400 mt-3 font-medium">Tidak ada siswa di kelas {{ $selectedKelas }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($siswas->isNotEmpty() && ($tanggal === now()->toDateString() || $tanggal < now()->toDateString()))
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Presensi
                </button>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection
