@extends('layouts.app')

@section('title', 'Edit Anak')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('profil.anak') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 font-medium transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Data Anak</h1>
        <p class="text-gray-500 mt-1">Perbarui data anak.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('update.anak', $anak->id) }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Anak</label>
                <input type="text" name="nama" id="nama" required value="{{ $anak->nama }}"
                    class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </div>
            <div>
                <label for="pendidikan" class="block text-sm font-medium text-gray-700 mb-2">Pendidikan</label>
                <input type="text" name="pendidikan" id="pendidikan" required value="{{ $anak->pendidikan }}"
                    class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </div>
            <div>
                <label for="umur" class="block text-sm font-medium text-gray-700 mb-2">Umur</label>
                <input type="number" name="umur" id="umur" required min="1" max="99" value="{{ $anak->umur }}"
                    class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors">
            </div>
            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" required
                    class="w-full rounded-xl border-gray-200 border bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors resize-none">{{ $anak->alamat }}</textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('profil.anak') }}" class="flex-1 sm:flex-none px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-sm rounded-xl transition text-center">Batal</a>
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection