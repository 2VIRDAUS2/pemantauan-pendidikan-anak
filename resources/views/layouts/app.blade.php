<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pemantauan Pendidikan Anak')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover { background: #eef2ff; transform: translateX(4px); }
        .sidebar-link.active { background: #eef2ff; color: #4f46e5; font-weight: 600; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">

@php
    $role = auth()->check() ? auth()->user()->role->value : null;
    $isGuru = $role === 'guru';
    $isOrtu = $role === 'orang_tua';

    $menuItems = [];
    if ($isGuru) {
        $menuItems = [
            ['route' => 'guru.dashboard', 'label' => 'Dashboard', 'icon' => '📊'],
            ['route' => 'guru.presensi.index', 'label' => 'Absensi', 'icon' => '📋'],
            ['route' => 'guru.verifikasi-izin.index', 'label' => 'Verifikasi Izin', 'icon' => '✅'],
            ['route' => 'guru.nilai.index', 'label' => 'Input Nilai', 'icon' => '📝'],
            ['route' => 'jadwal.index', 'label' => 'Jadwal Pelajaran', 'icon' => '📅'],
            ['route' => 'pengumuman.index', 'label' => 'Pengumuman', 'icon' => '📢'],
        ];
    } elseif ($isOrtu) {
        $menuItems = [
            ['route' => 'orang-tua.dashboard', 'label' => 'Dashboard', 'icon' => '🏠'],
            ['route' => 'orang-tua.pengajuan-izin.index', 'label' => 'Pengajuan Izin', 'icon' => '📋'],
            ['route' => 'orang-tua.nilai.index', 'label' => 'Nilai & Presensi', 'icon' => '📚'],
            ['route' => 'jadwal.index', 'label' => 'Jadwal Pelajaran', 'icon' => '📅'],
            ['route' => 'pengumuman.index', 'label' => 'Pengumuman', 'icon' => '📢'],
        ];
    }
@endphp

@if(auth()->check())
<div class="flex min-h-screen">
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 shadow-sm transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex flex-col h-full">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span class="text-white text-lg font-bold">PA</span>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-800">PendidikanAnak</h1>
                    <p class="text-xs text-gray-400">Sistem Monitoring SD</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @foreach($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 @if(Route::currentRouteName() === $item['route']) active @endif">
                    <span>{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
                @endforeach
            </nav>

            <div class="px-4 py-4 border-t border-gray-100">
                <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-700 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->role->label() }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <div class="flex-1 lg:ml-64">
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center gap-3 ml-auto">
                    <span class="text-sm text-gray-400">{{ now()->translatedFormat('d M Y') }}</span>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold sm:hidden">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 sm:px-6 lg:px-8 py-6">
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm" id="flash-success">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600">&times;</button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('overlay').classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    const flash = document.getElementById('flash-success');
    if (flash) {
        setTimeout(() => { flash.style.transition = 'opacity 0.5s'; flash.style.opacity = '0'; setTimeout(() => flash.remove(), 500); }, 3000);
    }
});
</script>
@else
    @yield('content')
@endif

@stack('scripts')
</body>
</html>
