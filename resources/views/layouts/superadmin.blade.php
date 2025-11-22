<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <div class="flex">

        {{-- SIDEBAR --}}
        @include('superadmin.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-10">

            @isset($header)
                <h1 class="text-2xl font-semibold mb-6">{{ $header }}</h1>
            @endisset

            {{ $slot }}

        </main>

    </div>

</body>
</html>
