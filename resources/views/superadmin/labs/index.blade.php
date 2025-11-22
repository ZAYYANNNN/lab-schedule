<x-app-layout title="Daftar Lab"
              header="Daftar Laboratorium"
              subheader="Kelola seluruh laboratorium">

    {{-- Google Icon --}}
    @push('head')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900">
            Daftar Laboratorium
        </h2>
        <p class="text-gray-600">
            @if(auth()->user()->role === 'superadmin')
                Kelola semua laboratorium di kampus
            @else
                Kelola laboratorium Prodi {{ auth()->user()->prodi->nama ?? '-' }}
            @endif
        </p>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- BUTTON TAMBAH LAB --}}
            @if(auth()->user()->role === 'superadmin')
                <a href="{{ route('superadmin.labs.create') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition w-fit">
                    <span class="material-symbols-outlined">add</span>
                    Tambah Lab
                </a>
            @endif

            {{-- SEARCH --}}
            <div class="bg-white p-4 rounded-xl shadow-sm">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        search
                    </span>
                    <input type="text"
                           id="searchInput"
                           onkeyup="filterLabs()"
                           placeholder="Cari lab berdasarkan nama atau lokasi..."
                           class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            {{-- GRID LAB --}}
            <div id="labsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse ($labs as $lab)
                    <div class="lab-card bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition border border-gray-200"
                         data-name="{{ strtolower($lab->name) }}"
                         data-lokasi="{{ strtolower($lab->lokasi) }}">

                        {{-- FOTO --}}
                        <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=400&q=80"
                             class="w-full h-48 object-cover">

                        <div class="p-5 space-y-4">

                            {{-- Atas --}}
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="text-gray-900 font-semibold text-lg">{{ $lab->name }}</h3>

                                    <div class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                                        <span>{{ $lab->lokasi }}</span>
                                    </div>

                                    @if(auth()->user()->role === 'superadmin')
                                        <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs rounded-md mt-2">
                                            {{ $lab->prodi->nama ?? 'Tanpa Prodi' }}
                                        </span>
                                    @endif
                                </div>

                                {{-- STATUS --}}
                                <span class="px-3 py-1 rounded-full text-xs
                                    @if($lab->status === 'Tersedia') bg-green-100 text-green-700
                                    @elseif($lab->status === 'Digunakan') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif
                                ">
                                    {{ $lab->status }}
                                </span>
                            </div>

                            {{-- Kapasitas --}}
                            <div class="flex justify-between text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">group</span>
                                    Kapasitas
                                </div>
                                <span class="text-gray-900">{{ $lab->kapasitas }} orang</span>
                            </div>

                            {{-- PJ --}}
                            <div class="pt-2 border-t border-gray-100">
                                <p class="text-xs text-gray-500">Penanggung Jawab</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $lab->pj }}</p>
                            </div>

                            {{-- ACTION --}}
                            @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
                                <div class="flex gap-2 pt-3 border-t border-gray-200">

                                    {{-- EDIT --}}
                                    <a href="{{ route('superadmin.labs.edit', $lab->id) }}"
                                       class="flex-1 flex items-center justify-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    @if(auth()->user()->role === 'superadmin')
                                        <form action="{{ route('superadmin.labs.destroy', $lab->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus lab ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg flex items-center">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            @endif

                        </div>
                    </div>
                @empty
                    <p class="col-span-3 text-center text-gray-500 py-10">Belum ada lab terdaftar.</p>
                @endforelse

            </div>

        </div>
    </div>

    {{-- FILTER JS --}}
    @push('scripts')
        <script>
            function filterLabs() {
                const input = document.getElementById("searchInput").value.toLowerCase();
                const cards = document.querySelectorAll(".lab-card");

                cards.forEach(card => {
                    const name = card.dataset.name;
                    const lokasi = card.dataset.lokasi;

                    card.style.display =
                        name.includes(input) || lokasi.includes(input)
                            ? "block"
                            : "none";
                });
            }
        </script>
    @endpush

</x-app-layout>
