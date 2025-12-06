<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Icons --}}
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @include('admin.partials.sidebar')

        {{-- CONTENT --}}
        <main class="flex-1 p-8">

            {{-- HEADER --}}
            @isset($header)
                <h1 class="text-2xl font-semibold mb-3">{{ $header }}</h1>
                @isset($subheader)
                    <p class="text-gray-600 mb-6">{{ $subheader }}</p>
                @endisset
            @endisset

            @yield('content')

        </main>
    </div>

    @stack('scripts')
</body>
</html>
