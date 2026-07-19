@extends('layouts.app')

@section('title', 'Ajukan Izin Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('orang-tua.pengajuan-izin.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 font-medium transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Ajukan Izin Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Isi form berikut untuk mengajukan izin bagi anak Anda.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 p-5">
            <div class="flex items-center gap-3">
                <span class="text-2xl">📋</span>
                <h2 class="text-lg font-bold text-white">Formulir Pengajuan Izin</h2>
            </div>
        </div>

        <form action="{{ route('orang-tua.pengajuan-izin.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="siswa_id" class="block text-sm font-semibold text-gray-700 mb-2">🎓 Pilih Anak</label>
                <select id="siswa_id" name="siswa_id" required
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50/50 text-sm text-gray-800 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition-all duration-200">
                    <option value="">-- Pilih Anak --</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>{{ $siswa->nama_siswa }} - Kelas {{ $siswa->kelas }}</option>
                    @endforeach
                </select>
                @error('siswa_id')<p class="text-red-500 text-xs mt-1.5">⚠️ {{ $message }}</p>@enderror
            </div>

            <div>
                <label for="tanggal_izin" class="block text-sm font-semibold text-gray-700 mb-2">📅 Tanggal Izin</label>
                <input type="date" id="tanggal_izin" name="tanggal_izin" value="{{ old('tanggal_izin') }}" required min="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50/50 text-sm text-gray-800 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition-all duration-200" />
                @error('tanggal_izin')<p class="text-red-500 text-xs mt-1.5">⚠️ {{ $message }}</p>@enderror
            </div>

            <div>
                <label for="alasan" class="block text-sm font-semibold text-gray-700 mb-2">💬 Alasan Izin</label>
                <textarea id="alasan" name="alasan" rows="4" required placeholder="Tuliskan alasan pengajuan izin (min. 10 karakter)..."
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition-all duration-200 resize-none">{{ old('alasan') }}</textarea>
                @error('alasan')<p class="text-red-500 text-xs mt-1.5">⚠️ {{ $message }}</p>@enderror
            </div>

            <div>
                <label for="file_bukti" class="block text-sm font-semibold text-gray-700 mb-2">📎 Upload Bukti (PDF/JPG/PNG, max 5MB)</label>
                <input type="file" id="file_bukti" name="file_bukti" accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 text-sm text-gray-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                @error('file_bukti')<p class="text-red-500 text-xs mt-1.5">⚠️ {{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('orang-tua.pengajuan-izin.index') }}" class="flex-1 sm:flex-none px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm rounded-xl transition text-center">Batal</a>
                <button type="submit" class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
