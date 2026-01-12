<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Lab Schedule') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased selection:bg-blue-100 selection:text-blue-700">

    <div class="flex min-h-screen relative overflow-hidden">
        {{-- Background Soft Glows --}}
        <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-blue-400/5 rounded-full blur-[120px] -z-10"></div>
        <div class="fixed bottom-0 left-64 w-[500px] h-[500px] bg-indigo-400/5 rounded-full blur-[120px] -z-10"></div>

        @include('partials.sidebar')

        <main class="flex-1 ml-72 p-8 min-w-0">
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-700 px-5 py-4 rounded-2xl flex items-center shadow-sm animate-fade-in-down"
                    role="alert">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    </div>
                    <span class="block sm:inline font-bold tracking-tight">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 bg-rose-50 border border-rose-100 text-rose-700 px-5 py-4 rounded-2xl flex items-center shadow-sm animate-fade-in-down"
                    role="alert">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center mr-3">
                        <span class="material-symbols-outlined text-[20px]">error</span>
                    </div>
                    <span class="block sm:inline font-bold tracking-tight">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 bg-rose-50 border border-rose-100 text-rose-700 px-6 py-5 rounded-2xl shadow-sm animate-fade-in-down"
                    role="alert">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center mr-3">
                            <span class="material-symbols-outlined text-[20px]">warning</span>
                        </div>
                        <span class="font-black uppercase tracking-widest text-xs">Peringatan Input</span>
                    </div>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center text-sm font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-300 mr-2"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out forwards;
        }
    </style>

    @stack('scripts')
</body>

</html>