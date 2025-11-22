<x-superadmin-layout :title="'Dashboard'" :header="'Dashboard'">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card 1 --}}
        <div class="p-6 bg-white shadow rounded-xl">
            <h3 class="text-xl font-semibold mb-2">Total Lab</h3>
            <p class="text-3xl font-bold text-blue-600">12</p>
        </div>

        {{-- Card 2 --}}
        <div class="p-6 bg-white shadow rounded-xl">
            <h3 class="text-xl font-semibold mb-2">Total Aset</h3>
            <p class="text-3xl font-bold text-blue-600">384</p>
        </div>

        {{-- Card 3 --}}
        <div class="p-6 bg-white shadow rounded-xl">
            <h3 class="text-xl font-semibold mb-2">Peminjaman Aktif</h3>
            <p class="text-3xl font-bold text-blue-600">27</p>
        </div>

    </div>

</x-superadmin-layout>
