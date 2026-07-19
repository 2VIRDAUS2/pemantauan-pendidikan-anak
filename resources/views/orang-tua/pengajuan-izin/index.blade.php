@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengajuan Izin</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pengajuan izin anak Anda.</p>
        </div>
        <a href="{{ route('orang-tua.pengajuan-izin.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Izin Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Alasan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $index => $izin)
                        @php
                            $statusMap = [
                                'pending' => ['class' => 'bg-yellow-100 text-yellow-700 border border-yellow-200', 'label' => '⏳ Menunggu'],
                                'approved' => ['class' => 'bg-emerald-100 text-emerald-700 border border-emerald-200', 'label' => '✅ Disetujui'],
                                'rejected' => ['class' => 'bg-red-100 text-red-700 border border-red-200', 'label' => '❌ Ditolak'],
                            ];
                            $s = $statusMap[$izin->status->value] ?? ['class' => 'bg-gray-100 text-gray-500 border border-gray-200', 'label' => $izin->status->value ?? '—'];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-4 text-gray-500 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-800">{{ $izin->siswa->nama_siswa }}</p>
                                <p class="text-xs text-gray-400">Kelas {{ $izin->siswa->kelas }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-600 font-medium">{{ $izin->tanggal_izin->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-gray-600 max-w-[200px]"><p class="text-xs line-clamp-2">{{ $izin->alasan }}</p></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $s['class'] }}">{{ $s['label'] }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($izin->file_bukti_nama)
                                    <a href="{{ route('bukti-izin.show', $izin) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-lg hover:bg-blue-100 transition border border-blue-100">📎 {{ $izin->file_bukti_nama }}</a>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <span class="text-4xl">📭</span>
                                <p class="text-sm text-gray-400 mt-3 font-medium">Belum ada pengajuan izin</p>
                                <p class="text-xs text-gray-300 mt-1">Klik "Ajukan Izin Baru" untuk membuat pengajuan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
