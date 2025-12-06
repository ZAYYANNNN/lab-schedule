<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Labs -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Labs</h2>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalLabs }}</p>
            </div>

            <!-- Total Assets -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Assets</h2>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalAssets }}</p>
            </div>

            <!-- Total Schedules -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Schedules</h2>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalSchedules }}</p>
            </div>
        </div>
    </div>
</x-app-layout>