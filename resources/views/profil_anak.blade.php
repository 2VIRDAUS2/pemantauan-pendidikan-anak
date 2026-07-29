@extends('layouts.app')

@section('title', 'Profil Anak')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Profil Anak</h1>
        <p class="text-gray-500 mt-1">Data profil anak yang terdaftar.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pendidikan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Umur</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($profil as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-4 text-gray-400 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4 font-semibold text-gray-800">{{ $item->nama }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $item->pendidikan }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $item->umur }} tahun</td>
                            <td class="px-5 py-4 text-gray-600 max-w-[200px] truncate">{{ $item->alamat }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('edit.anak', $item->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-semibold rounded-lg hover:bg-indigo-100 transition">Edit</a>
                                    <a href="{{ route('hapus.anak', $item->id) }}" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <span class="text-4xl">📭</span>
                                <p class="text-sm text-gray-400 mt-3 font-medium">Belum ada data anak</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('tambah.anak') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Data Anak
        </a>
    </div>
</div>
@endsection