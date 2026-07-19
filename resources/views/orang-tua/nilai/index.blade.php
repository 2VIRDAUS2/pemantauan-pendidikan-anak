@extends('layouts.app')

@section('title', 'Nilai & Presensi')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Nilai & Presensi</h1>
        <p class="text-sm text-gray-500 mt-1">Lihat perkembangan akademik dan kehadiran anak Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($data as $siswa)
            @php
                $avg = $siswa->rata_rata_nilai;
                $avgColor = $avg > 75 ? 'from-emerald-400 to-green-500' : ($avg > 60 ? 'from-yellow-400 to-amber-500' : 'from-red-400 to-rose-500');
                $avgTextColor = $avg > 75 ? 'text-emerald-600' : ($avg > 60 ? 'text-yellow-600' : 'text-red-500');
                $avgLabel = $avg > 75 ? 'Sangat Baik' : ($avg > 60 ? 'Baik' : 'Perlu Ditingkatkan');
                $avgBg = $avg > 75 ? 'bg-emerald-50 border-emerald-100' : ($avg > 60 ? 'bg-yellow-50 border-yellow-100' : 'bg-red-50 border-red-100');
            @endphp
            <a href="{{ route('orang-tua.nilai.show', $siswa->id) }}" class="block group">
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-300/50 transform hover:-translate-y-1 transition-all duration-300">
                    <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white text-xl">🎓</div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $siswa->nama_siswa }}</h3>
                                    <p class="text-blue-200 text-sm">Kelas {{ $siswa->kelas }} &bull; NISN: {{ $siswa->nisn }}</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-white/60 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rata-rata Nilai</p>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $avgBg }} {{ $avgTextColor }} border">{{ $avgLabel }}</span>
                        </div>
                        <div class="flex items-end gap-3">
                            <span class="text-4xl font-extrabold {{ $avgTextColor }}">{{ number_format($avg, 1) }}</span>
                            <span class="text-sm text-gray-400 mb-1">/ 100</span>
                        </div>
                        <div class="mt-4 h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r {{ $avgColor }} rounded-full transition-all duration-700 ease-out" style="width: {{ min($avg, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3 text-center">Klik untuk melihat detail nilai dan presensi</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-2xl shadow-lg border border-gray-100">
                <span class="text-5xl">📭</span>
                <h3 class="text-lg font-bold text-gray-600 mt-4">Belum Ada Data Anak</h3>
                <p class="text-sm text-gray-400 mt-1">Hubungi administrator untuk menambahkan data anak.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
