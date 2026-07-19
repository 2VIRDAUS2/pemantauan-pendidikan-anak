@extends('layouts.app')

@section('title', 'Pengumuman')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengumuman</h1>
        <p class="text-gray-500 mt-1">{{ auth()->user()->isGuru() ? 'Buat dan kelola pengumuman untuk siswa dan orang tua.' : 'Lihat pengumuman terbaru dari sekolah.' }}</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(auth()->user()->isGuru())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-900">Buat Pengumuman Baru</h2>
        </div>

        <form method="POST" action="{{ route('pengumuman.store') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                    <input type="text" name="judul" id="judul" required maxlength="200" placeholder="Judul pengumuman"
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors placeholder:text-gray-400">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">Isi Pengumuman</label>
                    <textarea name="isi" id="isi" required maxlength="2000" rows="4" placeholder="Tuliskan isi pengumuman..."
                        class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors placeholder:text-gray-400 resize-none"></textarea>
                    @error('isi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="target_kelas" class="block text-sm font-medium text-gray-700 mb-2">Target Kelas <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <select name="target_kelas" id="target_kelas"
                            class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
                            <option value="">Semua Kelas</option>
                            @foreach(range(1, 6) as $k)
                                <option value="{{ $k }}">Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                        @error('target_kelas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-2.5 bg-indigo-500 text-white text-sm font-semibold rounded-xl hover:bg-indigo-600 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Publikasikan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

    <div class="space-y-4">
        @forelse($data as $pengumuman)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="flex">
                <div class="w-1.5 flex-shrink-0 {{ $pengumuman->is_aktif ? 'bg-emerald-400' : 'bg-gray-300' }}"></div>
                <div class="flex-1 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-base font-semibold text-gray-900">{{ $pengumuman->judul }}</h3>
                                @if($pengumuman->is_aktif)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-200">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">Nonaktif</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $pengumuman->isi }}</p>
                            <div class="flex flex-wrap items-center gap-3 mt-3">
                                <span class="text-xs text-gray-400">{{ $pengumuman->created_at->format('d M Y, H:i') }}</span>
                                @if($pengumuman->pembuat)
                                <span class="text-xs text-gray-400">&middot;</span>
                                <span class="text-xs text-gray-500 font-medium">{{ $pengumuman->pembuat->name }}</span>
                                @endif
                                @if($pengumuman->target_kelas)
                                <span class="text-xs text-gray-400">&middot;</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                                    Kelas {{ $pengumuman->target_kelas }}
                                </span>
                                @else
                                <span class="text-xs text-gray-400">&middot;</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-600">
                                    Semua Kelas
                                </span>
                                @endif
                            </div>
                        </div>

                        @if(auth()->user()->isGuru())
                        <div class="flex-shrink-0">
                            <form method="POST" action="{{ route('pengumuman.toggle', $pengumuman) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl transition-colors
                                    {{ $pengumuman->is_aktif
                                       ? 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                       : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                                    {{ $pengumuman->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Belum Ada Pengumuman</h3>
            <p class="text-gray-500 mt-1 text-sm">Pengumuman akan muncul di sini setelah dipublikasikan.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
