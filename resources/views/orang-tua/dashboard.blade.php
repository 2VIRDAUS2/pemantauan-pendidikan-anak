@extends('layouts.app')

@section('title', 'Dashboard Orang Tua')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Berikut ringkasan informasi pendidikan anak Anda hari ini.</p>
        </div>
        <span class="text-sm text-gray-400 mt-2 sm:mt-0">{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>

    @forelse($siswaData as $data)
        @php
            $siswa = $data['siswa'];
            $presensi = $data['presensi_hari_ini'];
            $jadwal = $data['jadwal_hari_ini'];
            $izinPending = $data['izin_pending'];
            $rataNilai = $data['rata_rata_nilai'];
            $rekap = $data['rekap_bulan'];

            $statusMap = [
                'hadir' => ['bg' => 'from-emerald-500 to-green-600', 'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'label' => 'Hadir', 'icon' => '✅'],
                'izin' => ['bg' => 'from-yellow-400 to-amber-500', 'badge' => 'bg-yellow-100 text-yellow-700 border-yellow-200', 'label' => 'Izin', 'icon' => '📋'],
                'sakit' => ['bg' => 'from-blue-400 to-blue-600', 'badge' => 'bg-blue-100 text-blue-700 border-blue-200', 'label' => 'Sakit', 'icon' => '🤒'],
                'alpha' => ['bg' => 'from-red-400 to-red-600', 'badge' => 'bg-red-100 text-red-700 border-red-200', 'label' => 'Alpha', 'icon' => '❌'],
            ];
            $st = $statusMap[$presensi->status ?? ''] ?? ['bg' => 'from-gray-400 to-gray-500', 'badge' => 'bg-gray-100 text-gray-500 border-gray-200', 'label' => 'Belum Absen', 'icon' => '⏳'];

            $nilaiColor = $rataNilai > 75 ? 'text-emerald-600' : ($rataNilai > 60 ? 'text-yellow-600' : 'text-red-500');
            $nilaiBar = $rataNilai > 75 ? 'from-emerald-400 to-green-500' : ($rataNilai > 60 ? 'from-yellow-400 to-amber-500' : 'from-red-400 to-rose-500');
        @endphp

        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r {{ $st['bg'] }} px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-2xl shadow-inner">🎓</div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $siswa->nama_siswa }}</h2>
                        <p class="text-white/80 text-sm">Kelas {{ $siswa->kelas }} &bull; NISN: {{ $siswa->nisn }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Status Hari Ini</h3>
                    <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl border-2 {{ $st['badge'] }} shadow-sm">
                        <span class="text-2xl">{{ $st['icon'] }}</span>
                        <span class="font-bold text-lg">{{ $st['label'] }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rata-rata Nilai</p>
                        <p class="text-2xl font-extrabold {{ $nilaiColor }} mt-1">{{ number_format($rataNilai, 1) }}</p>
                        <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r {{ $nilaiBar }} rounded-full" style="width: {{ min($rataNilai, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-4 border border-yellow-100">
                        <p class="text-xs font-bold text-yellow-500 uppercase tracking-wider">Izin Pending</p>
                        <p class="text-2xl font-extrabold text-yellow-600 mt-1">{{ $izinPending }}</p>
                        <p class="text-xs text-yellow-400 mt-1">Menunggu persetujuan</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-4 border border-emerald-100">
                        <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Rekap Bulan Ini</p>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-1 mt-2">
                            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span><span class="text-xs font-bold text-emerald-700">{{ $rekap['hadir'] ?? 0 }}</span><span class="text-xs text-gray-400">Hadir</span></div>
                            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-yellow-500 rounded-full"></span><span class="text-xs font-bold text-yellow-700">{{ $rekap['izin'] ?? 0 }}</span><span class="text-xs text-gray-400">Izin</span></div>
                            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-blue-500 rounded-full"></span><span class="text-xs font-bold text-blue-700">{{ $rekap['sakit'] ?? 0 }}</span><span class="text-xs text-gray-400">Sakit</span></div>
                            <div class="flex items-center gap-1"><span class="w-2 h-2 bg-red-500 rounded-full"></span><span class="text-xs font-bold text-red-700">{{ $rekap['alpha'] ?? 0 }}</span><span class="text-xs text-gray-400">Alpha</span></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                        <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Total Hadir</p>
                        <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ ($rekap['hadir'] ?? 0) + ($rekap['izin'] ?? 0) + ($rekap['sakit'] ?? 0) }}</p>
                        <p class="text-xs text-blue-400 mt-1">hari aktif bulan ini</p>
                    </div>
                </div>

                @if($jadwal && $jadwal->isNotEmpty())
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Jadwal Hari Ini</h3>
                    <div class="space-y-2">
                        @foreach($jadwal as $j)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition border border-gray-100">
                            <div class="w-20 text-center">
                                <p class="text-xs font-bold text-indigo-600">{{ $j->jam_mulai }}</p>
                                <p class="text-xs text-gray-400">- {{ $j->jam_selesai }}</p>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ $j->mata_pelajaran }}</p>
                                <p class="text-xs text-gray-400">{{ $j->guru->name }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <span class="text-3xl">📅</span>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Tidak ada jadwal untuk hari ini</p>
                </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-16 bg-white rounded-2xl shadow-lg border border-gray-100">
            <span class="text-5xl">📭</span>
            <h3 class="text-lg font-bold text-gray-600 mt-4">Belum Ada Data Anak</h3>
            <p class="text-sm text-gray-400 mt-1">Hubungi administrator untuk menambahkan data anak Anda.</p>
        </div>
    @endforelse

    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📢</span>
                <h2 class="text-lg font-bold text-white">Pengumuman Terbaru</h2>
            </div>
        </div>
        <div class="p-6">
            @if($pengumumans->isNotEmpty())
            <div class="space-y-3">
                @foreach($pengumumans as $item)
                <div class="flex gap-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-xl border border-gray-100 hover:shadow-md transition">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl flex flex-col items-center justify-center text-white shadow-sm">
                        <span class="text-xs font-bold leading-none">{{ $item->created_at->format('d') }}</span>
                        <span class="text-xs font-medium leading-none mt-0.5">{{ $item->created_at->locale('id')->format('M') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800">{{ $item->judul }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($item->isi, 120) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <span class="text-3xl">📭</span>
                <p class="text-sm text-gray-400 mt-2 font-medium">Belum ada pengumuman</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
