<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SmartLab</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/umy.png') }}">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-slate-50">
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute top-0 left-0 w-full h-full -z-10 bg-slate-50"></div>
        <div class="absolute -top-[20%] -right-[10%] w-[70vw] h-[70vw] bg-blue-600/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-[20%] -left-[10%] w-[60vw] h-[60vw] bg-indigo-600/5 rounded-full blur-3xl"></div>

        {{-- Main Container Card --}}
        <div
            class="w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden grid grid-cols-1 md:grid-cols-2 min-h-[600px]">

            {{-- Left Column: Summary & Branding --}}
            <div class="bg-blue-600 p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden text-white">
                {{-- Decorative Shapes --}}
                <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                    <div class="absolute top-10 right-10 w-32 h-32 rounded-full border-4 border-white"></div>
                    <div class="absolute bottom-[-50px] left-[-50px] w-64 h-64 rounded-full bg-white blur-3xl"></div>
                </div>

                <div class="relative z-10">
                    {{-- Logo --}}
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-8">
                        <img src="{{ asset('images/umy.png') }}" alt="UMY Logo" class="w-12 h-12 object-contain">
                    </div>

                    <h1 class="text-4xl sm:text-5xl font-black tracking-tight leading-tight mb-4">
                        SmartLab
                        <span class="block text-blue-200 text-2xl font-bold mt-1">Ecosystem</span>
                    </h1>

                    <p class="text-blue-100 text-lg leading-relaxed font-medium max-w-md">
                        Sistem Informasi Manajemen Laboratorium Universitas Muhammadiyah Yogyakarta.
                    </p>

                    <div class="mt-8 space-y-4">
                        <div
                            class="flex items-center gap-4 bg-blue-500/30 p-4 rounded-xl backdrop-blur-sm border border-blue-400/30">
                            <span class="material-symbols-outlined text-3xl">calendar_month</span>
                            <div>
                                <h3 class="font-bold">Penjadwalan</h3>
                                <p class="text-xs text-blue-100">Kelola jadwal praktikum dengan mudah</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-4 bg-blue-500/30 p-4 rounded-xl backdrop-blur-sm border border-blue-400/30">
                            <span class="material-symbols-outlined text-3xl">inventory_2</span>
                            <div>
                                <h3 class="font-bold">Inventaris</h3>
                                <p class="text-xs text-blue-100">Manajemen aset dan peminjaman alat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-12">
                    <p class="text-xs font-semibold text-blue-200 uppercase tracking-widest">
                        &copy; {{ date('Y') }} UMY. All Rights Reserved.
                    </p>
                </div>
            </div>

            {{-- Right Column: Login Form --}}
            <div class="p-8 sm:p-12 flex flex-col justify-center bg-white">
                <div class="w-full max-w-md mx-auto">
                    <div class="mb-8">
                        <h2 class="text-3xl font-black text-slate-800 mb-2">Selamat Datang</h2>
                        <p class="text-slate-500 font-medium">Silakan masuk menggunakan akun Anda untuk mengakses.</p>
                    </div>

                    <x-auth-session-status class="mb-6" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email"
                                class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-600 transition-colors">mail</span>
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                    placeholder="nama@email.com"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 pl-12 pr-4 text-slate-700 font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                            </div>
                            <x-input-error :messages="$errors->get('email')"
                                class="mt-2 text-xs font-bold text-rose-500 ml-1" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <div class="flex justify-between items-center mb-2 ml-1">
                                <label for="password"
                                    class="block text-xs font-black text-slate-400 uppercase tracking-widest">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest">Lupa
                                        Password?</a>
                                @endif
                            </div>
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-600 transition-colors">lock</span>
                                <input id="password" type="password" name="password" required placeholder="••••••••"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 pl-12 pr-4 text-slate-700 font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                            </div>
                            <x-input-error :messages="$errors->get('password')"
                                class="mt-2 text-xs font-bold text-rose-500 ml-1" />
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center ml-1">
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember"
                                    class="w-5 h-5 rounded-md border-slate-300 text-blue-600 focus:ring-blue-500 shadow-sm transition-all cursor-pointer">
                                <span
                                    class="ms-3 text-sm font-semibold text-slate-500 group-hover:text-slate-700 transition-colors">Ingat
                                    saya</span>
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-4">
                            <span>Masuk Sekarang</span>
                            <span class="material-symbols-outlined text-xl">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(-5%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: translateY(0);
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }
    </style>
</body>

</html>