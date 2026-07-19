@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Jadwal Pelajaran</h1>
            <p class="text-gray-500 mt-1">Lihat dan atur jadwal pelajaran per kelas.</p>
        </div>
    </div>

    <div class="flex gap-2 overflow-x-auto pb-2">
        @foreach($kelasList as $kelas)
        <a href="{{ route('jadwal.index', ['kelas' => $kelas]) }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap transition-all
                  {{ $selectedKelas === $kelas
                     ? 'bg-indigo-500 text-white shadow-sm'
                     : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600' }}">
            Kelas {{ $kelas }}
        </a>
        @endforeach
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @foreach($hariList as $hari)
    @php
        $jadwalHari = ($jadwals->get($hari) ?? collect())->sortBy('jam_mulai');
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-lg font-semibold text-gray-900">{{ $hari }}</h2>
        </div>

        @if($jadwalHari->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($jadwalHari as $jadwal)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 text-center flex-shrink-0">
                        <p class="text-xs text-gray-400 font-medium">{{ $jadwal->jam_mulai }}</p>
                        <div class="w-0.5 h-2 bg-gray-200 mx-auto my-0.5 rounded"></div>
                        <p class="text-xs text-gray-400 font-medium">{{ $jadwal->jam_selesai }}</p>
                    </div>
                    <div class="w-px h-10 bg-indigo-200 rounded flex-shrink-0"></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $jadwal->mata_pelajaran }}</p>
                        <p class="text-xs text-gray-500">{{ $jadwal->guru->name ?? '-' }}</p>
                    </div>
                </div>
                @if(auth()->user()->isGuru())
                <form method="POST" action="{{ route('jadwal.destroy', $jadwal) }}" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-8 text-center">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-400 text-sm">Tidak ada jadwal untuk hari ini</p>
        </div>
        @endif
    </div>
    @endforeach

    @if(auth()->user()->isGuru())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Tambah Jadwal Baru</h2>
        </div>

        <form method="POST" action="{{ route('jadwal.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas" id="kelas" required
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ $selectedKelas === $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="hari" class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                    <select name="hari" id="hari" required
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                        <option value="">Pilih Hari</option>
                        @foreach($hariList as $hari)
                            <option value="{{ $hari }}">{{ $hari }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" id="mata_pelajaran" required placeholder="Contoh: Matematika"
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors placeholder:text-gray-400">
                </div>
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" required
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                </div>
                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" required
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2.5 bg-indigo-500 text-white text-sm font-semibold rounded-xl hover:bg-indigo-600 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Jadwal
                    </button>
                </div>
            </div>
            @error('kelas')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
            @error('hari')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
            @error('mata_pelajaran')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
            @error('jam_mulai')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
            @error('jam_selesai')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </form>
    </div>
    @endif

</div>
@endsection
