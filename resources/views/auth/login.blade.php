<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/UMY.png') }}">

    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>

<body class="antialiased bg-white h-screen overflow-hidden text-slate-800">

<!-- ROOT -->
<div class="w-full h-full grid grid-cols-1 lg:grid-cols-2">

    <!-- LEFT PANEL -->
    <div class="relative flex flex-col items-center justify-center bg-[#0a1120] text-white px-16 py-16">

        <!-- BRANDING CONTENT -->
        <div class="relative z-10 w-full max-w-md">
            <div class="flex items-center gap-6 mb-10">
                <img src="{{ asset('images/UMY.png') }}"
                     alt="UMY Logo"
                     class="w-16 h-16 object-contain opacity-90" />

                <div>
                    <h1 class="text-5xl font-black tracking-tight leading-none">
                        SmartLab
                    </h1>
                    <p class="text-xs font-bold tracking-[0.35em] text-slate-400 uppercase mt-2">
                        Ecosystem
                    </p>
                </div>
            </div>

            <p class="text-slate-400 text-lg font-medium leading-relaxed">
                Sistem Informasi Manajemen Laboratorium<br>
                Universitas Muhammadiyah Yogyakarta.
            </p>

            <!-- FEATURES -->
            <div class="space-y-6 mt-16">
                <div class="flex items-center gap-6 p-6 rounded-[2rem] bg-white/5 border border-white/5 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-4xl text-slate-500">
                        calendar_month
                    </span>
                    <div>
                        <p class="text-lg font-black text-white">
                            Jadwal Lab
                        </p>
                        <p class="text-sm text-slate-500 font-medium">
                            Kelola jadwal praktikum
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-6 p-6 rounded-[2rem] bg-white/5 border border-white/5 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-4xl text-slate-500">
                        inventory_2
                    </span>
                    <p class="text-lg font-black text-white leading-tight">
                        Kelola inventaris
                        <span class="text-slate-500 font-medium ml-1">laboratorium</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- DECOR (AMAN, TIDAK NGE-DORONG HEIGHT) -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/5 rounded-full blur-[140px]"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/5 rounded-full blur-[140px]"></div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="relative flex flex-col bg-white px-16 py-16">

        <!-- CENTER CONTENT -->
        <div class="flex-1 flex items-center justify-center">
            <div class="w-full max-w-md">

                <div class="mb-8">
                    <h2 class="text-5xl font-black tracking-tight leading-tight mb-3">
                        Selamat Datang
                    </h2>
                    <p class="text-slate-400 text-base font-medium">
                        Masuk untuk mengelola jadwal dan inventaris laboratorium.
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-7">
                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.35em] ml-2 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-blue-500 text-xl">
                                mail
                            </span>
                            <input type="email" name="email" required autofocus
                                   class="w-full bg-[#eff4fb] rounded-xl py-4 pl-14 pr-6 font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.35em] ml-2 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-xl">
                                lock
                            </span>
                            <input type="password" name="password" required
                                   class="w-full bg-[#eff4fb] rounded-xl py-4 pl-14 pr-6 font-bold outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <!-- REMEMBER -->
                    <label class="flex items-center gap-3 text-sm font-bold text-slate-500 ml-2">
                        <input type="checkbox" name="remember"
                               class="w-5 h-5 rounded-md border-slate-300 text-blue-500 focus:ring-blue-500/30">
                        Ingat saya
                    </label>

                    <!-- SUBMIT -->
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-3 py-4 rounded-xl bg-blue-600 text-white font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.98] transition-all">
                        Masuk Sekarang
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- FOOTER (SATU-SATUNYA) -->
        <div class="text-right mt-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.35em]">
                © 2026 UMY. ALL RIGHTS RESERVED.
            </p>
        </div>
    </div>

</div>
</body>
</html>
