<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Pemantauan Pendidikan Anak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        body { font-family: 'Inter', sans-serif; }
        .bg-animated {
            background: linear-gradient(-45deg, #1e3a5f, #2d1b69, #1a365d, #4c1d95);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .float-anim { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="h-full bg-animated">

    {{-- Decorative Elements --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-blue-400/5 rounded-full blur-2xl"></div>
    </div>

    <div class="min-h-full flex items-center justify-center px-4 py-12 relative z-10">
        <div class="w-full max-w-md">

            {{-- Logo & Header --}}
            <div class="text-center mb-8">
                <div class="float-anim inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 rounded-3xl shadow-2xl shadow-orange-500/30 mb-4">
                    <span class="text-4xl">🏫</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-2 drop-shadow-lg">Pemantauan Pendidikan</h1>
                <p class="text-blue-200 text-sm font-medium">Sistem Monitoring Pendidikan Anak SD</p>
            </div>

            {{-- Login Card --}}
            <div class="card-glass rounded-3xl shadow-2xl shadow-black/20 p-8 sm:p-10">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Selamat Datang 👋</h2>
                    <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                {{-- Error Display --}}
                @if($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-2">
                        <span class="text-red-500 text-sm">⚠️</span>
                        <div>
                            @foreach($errors->all() as $error)
                                <p class="text-red-600 text-xs">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-2">
                        <span class="text-red-500 text-sm">⚠️</span>
                        <p class="text-red-600 text-xs">{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('status'))
                    <div class="mb-5 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 flex items-center gap-2">
                        <span class="text-blue-500 text-sm">ℹ️</span>
                        <p class="text-blue-600 text-xs">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">📧 Alamat Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition-all duration-200"
                        />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">🔒 Kata Sandi</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Masukkan kata sandi"
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50/50 text-sm text-gray-800 placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition-all duration-200"
                        />
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <input
                                type="checkbox"
                                id="remember_me"
                                name="remember"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                            />
                            <span class="text-sm text-gray-600 group-hover:text-gray-800 transition">Ingat saya</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 hover:from-blue-700 hover:via-blue-800 hover:to-indigo-800 text-white font-semibold text-sm rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <span>Masuk</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <p class="text-center text-blue-300/60 text-xs mt-6">
                &copy; {{ date('Y') }} Sistem Pemantauan Pendidikan Anak
            </p>
        </div>
    </div>

</body>
</html>
