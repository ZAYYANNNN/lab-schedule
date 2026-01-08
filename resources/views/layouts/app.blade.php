<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Lab Schedule') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">
        @include('partials.sidebar')

        <main class="flex-1 ml-64 p-6 min-w-0">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center shadow-sm"
                    role="alert">
                    <span class="material-symbols-outlined mr-2">check_circle</span>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex items-center shadow-sm"
                    role="alert">
                    <span class="material-symbols-outlined mr-2">error</span>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm"
                    role="alert">
                    <div class="flex items-center mb-1">
                        <span class="material-symbols-outlined mr-2">warning</span>
                        <span class="font-bold">Ada beberapa masalah:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>


    @stack('scripts')
</body>

</html>