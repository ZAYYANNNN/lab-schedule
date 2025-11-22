<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lab Schedule - Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white">
    <!-- HERO -->
    <section class="min-h-screen flex flex-col justify-center items-center text-center px-6">
        <h1 class="text-5xl font-bold mb-4">Lab Schedule Management</h1>
        <p class="text-gray-300 max-w-2xl mb-8 text-lg">Sistem penjadwalan laboratorium untuk kampus Anda. Kelola lab, aset, dan jadwal secara efisien dengan akses berbasis role.</p>
        <a href="{{ route('login') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg">
            Login
        </a>

    </section>

    <!-- FEATURES -->
    <section class="py-20 px-6 max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">Fitur Utama</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="bg-gray-900 p-6 rounded-2xl shadow-xl border border-gray-800">
                <h3 class="text-xl font-semibold mb-2">Manajemen Lab</h3>
                <p class="text-gray-400 text-sm">Kelola daftar laboratorium, informasi detail, dan akses setiap prodi.</p>
            </div>
            <div class="bg-gray-900 p-6 rounded-2xl shadow-xl border border-gray-800">
                <h3 class="text-xl font-semibold mb-2">Manajemen Aset</h3>
                <p class="text-gray-400 text-sm">Pantau aset lab, kondisi, dan updating data oleh admin lab.</p>
            </div>
            <div class="bg-gray-900 p-6 rounded-2xl shadow-xl border border-gray-800">
                <h3 class="text-xl font-semibold mb-2">Penjadwalan</h3>
                <p class="text-gray-400 text-sm">Atur jadwal penggunaan lab berdasarkan prodi dengan approval admin.</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-8 text-center text-gray-500 border-t border-gray-800">
        <p>© 2025 Lab Schedule System</p>
    </footer>
</body>
</html>
