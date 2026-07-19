@extends('layouts.app')

@section('title', 'Verifikasi Izin')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Verifikasi Pengajuan Izin</h1>
        <p class="text-gray-500 mt-1">Tinjau dan verifikasi pengajuan izin siswa yang masuk.</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @forelse($data as $pengajuan)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex flex-col sm:flex-row sm:items-start gap-6">

            <div class="flex items-center gap-4 sm:w-64 flex-shrink-0">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ substr($pengajuan->siswa->nama_siswa, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->siswa->nama_siswa }}</p>
                    <p class="text-sm text-gray-500">Kelas {{ $pengajuan->siswa->kelas }}</p>
                </div>
            </div>

            <div class="flex-1 space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Tanggal Izin</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $pengajuan->tanggal_izin->format('d M Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Status</p>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                Menunggu Verifikasi
                            </span>
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Alasan</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $pengajuan->alasan }}</p>
                </div>
                @if($pengajuan->file_bukti_nama)
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Bukti</p>
                    <a href="{{ route('bukti-izin.show', $pengajuan) }}" target="_blank" class="inline-flex items-center gap-2 mt-1 text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        {{ $pengajuan->file_bukti_nama }}
                    </a>
                </div>
                @endif
            </div>

            <div class="flex sm:flex-col gap-3 sm:items-end flex-shrink-0">
                <form method="POST" action="{{ route('guru.verifikasi-izin.update', $pengajuan) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="w-full px-5 py-2.5 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui
                    </button>
                </form>
                <form method="POST" action="{{ route('guru.verifikasi-izin.update', $pengajuan) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="w-full px-5 py-2.5 bg-red-50 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak
                    </button>
                </form>
            </div>

        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Semua Beres!</h3>
        <p class="text-gray-500 mt-1 text-sm">Tidak ada pengajuan izin yang menunggu verifikasi saat ini.</p>
        <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-2 mt-4 text-sm text-indigo-600 hover:text-indigo-700 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
    @endforelse

</div>
@endsection
