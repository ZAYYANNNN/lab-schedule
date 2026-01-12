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

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-slate-50">
    <div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">
        {{-- Background Decorations --}}
        <div
            class="absolute top-0 left-0 w-full h-full -z-10 bg-[radial-gradient(circle_at_TOP_LEFT,_var(--tw-gradient-stops))] from-blue-100/50 via-slate-50 to-indigo-100/30">
        </div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>

        <div class="w-full max-w-lg">
            {{-- Branding --}}
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 shadow-xl shadow-blue-200 mb-4 animate-bounce-slow">
                    <span class="material-symbols-outlined text-white text-3xl">science</span>
                </div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">SmartLab</h1>
                <p class="text-slate-500 font-medium">Integrated Laboratory & Asset Management</p>
            </div>

            {{-- Login Card --}}
            <div
                class="bg-white/70 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-blue-900/5 border border-white p-8 sm:p-12 relative overflow-hidden">
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-1">Selamat Datang!</h2>
                    <p class="text-slate-400 text-sm font-medium">Silakan masuk untuk mengelola laboratorium Anda.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Alamat
                            Email</label>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">alternate_email</span>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                placeholder="nama@prodi.com"
                                class="w-full bg-slate-100/50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-semibold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold ml-1" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between items-center mb-2 ml-1">
                            <label for="password"
                                class="block text-xs font-black text-slate-400 uppercase tracking-widest tracking-widest">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-[10px] font-black text-blue-600 hover:text-blue-700 uppercase tracking-widest">Lupa
                                    Password?</a>
                            @endif
                        </div>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">lock</span>
                            <input id="password" type="password" name="password" required placeholder="••••••••"
                                class="w-full bg-slate-100/50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-semibold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold ml-1" />
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between ml-1">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-500 shadow-sm transition-all cursor-pointer">
                            <span
                                class="ms-3 text-sm font-bold text-slate-500 group-hover:text-slate-700 transition-colors">Ingat
                                saya</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2 tracking-wide uppercase text-sm">
                        Masuk Ke Dashboard
                        <span class="material-symbols-outlined text-white">login</span>
                    </button>

                    <p class="text-center text-[11px] text-slate-300 font-bold uppercase tracking-[0.2em] mt-8">
                        &copy; {{ date('Y') }} SMARTLAB ECOSYSTEM
                    </p>
                </form>
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