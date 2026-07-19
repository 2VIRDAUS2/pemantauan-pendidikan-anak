@extends('layouts.app')

@section('title', 'Detail Nilai - ' . $siswa->nama_siswa)

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('orang-tua.nilai.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 font-medium transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-blue-500/30">🎓</div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $siswa->nama_siswa }}</h1>
                <p class="text-sm text-gray-500">Kelas {{ $siswa->kelas }} &bull; NISN: {{ $siswa->nisn }}</p>
            </div>
        </div>
    </div>

    @php
        $avg = $siswa->rata_rata_nilai;
        $avgTextColor = $avg > 75 ? 'text-emerald-600' : ($avg > 60 ? 'text-yellow-600' : 'text-red-500');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100 text-center">
            <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Total Mapel</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $nilai->count() }}</p>
        </div>
        <div class="bg-gradient-to-br {{ $avg > 75 ? 'from-emerald-50 to-green-50 border-emerald-100' : ($avg > 60 ? 'from-yellow-50 to-amber-50 border-yellow-100' : 'from-red-50 to-rose-50 border-red-100') }} rounded-xl p-4 border text-center">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rata-rata</p>
            <p class="text-2xl font-extrabold {{ $avgTextColor }} mt-1">{{ number_format($avg, 1) }}</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-4 border border-emerald-100 text-center">
            <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Hadir</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $presensi->where('status', 'hadir')->count() }}</p>
        </div>
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 text-center">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Presensi</p>
            <p class="text-2xl font-extrabold text-gray-600 mt-1">{{ $presensi->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 p-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📝</span>
                    <h3 class="text-base font-bold text-white">Daftar Nilai</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Skor</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($nilai as $item)
                            @php
                                $skor = $item->skor;
                                $sc = $skor > 75 ? 'text-emerald-600 bg-emerald-50' : ($skor > 60 ? 'text-yellow-600 bg-yellow-50' : 'text-red-500 bg-red-50');
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-700">{{ $item->mata_pelajaran }}</td>
                                <td class="px-4 py-3 text-right"><span class="inline-flex px-3 py-1 rounded-lg text-sm font-bold {{ $sc }}">{{ $skor }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-10 text-center"><span class="text-3xl">📭</span><p class="text-sm text-gray-400 mt-2 font-medium">Belum ada data nilai</p></td></tr>
                        @endforelse
                    </tbody>
                    @if($nilai->count() > 0)
                    <tfoot><tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-t-2 border-gray-200">
                        <td colspan="2" class="px-4 py-3 text-sm font-bold text-gray-600">Rata-rata</td>
                        <td class="px-4 py-3 text-right"><span class="inline-flex px-3 py-1 rounded-lg text-sm font-extrabold {{ $avgTextColor }} bg-gray-100">{{ number_format($avg, 1) }}</span></td>
                    </tr></tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 via-purple-600 to-pink-600 p-4">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📅</span>
                    <h3 class="text-base font-bold text-white">Riwayat Presensi</h3>
                </div>
            </div>
            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 z-10"><tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $statusMap = [
                                'hadir' => ['class' => 'bg-emerald-100 text-emerald-700 border border-emerald-200', 'icon' => '✅', 'label' => 'Hadir'],
                                'izin' => ['class' => 'bg-yellow-100 text-yellow-700 border border-yellow-200', 'icon' => '📋', 'label' => 'Izin'],
                                'sakit' => ['class' => 'bg-blue-100 text-blue-700 border border-blue-200', 'icon' => '🤒', 'label' => 'Sakit'],
                                'alpha' => ['class' => 'bg-red-100 text-red-700 border border-red-200', 'icon' => '❌', 'label' => 'Alpha'],
                            ];
                        @endphp
                        @forelse($presensi as $item)
                            @php $s = $statusMap[$item->status] ?? ['class' => 'bg-gray-100 text-gray-500 border border-gray-200', 'icon' => '❓', 'label' => $item->status]; @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-gray-600 font-medium">{{ $item->tanggal->format('d M Y') }}</td>
                                <td class="px-4 py-3"><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $s['class'] }}">{{ $s['icon'] }} {{ $s['label'] }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-10 text-center"><span class="text-3xl">📭</span><p class="text-sm text-gray-400 mt-2 font-medium">Belum ada data presensi</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
