@forelse ($labs as $lab)
    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">
        {{-- FOTO --}}
        <img src="{{ $lab->foto ? asset('storage/' . $lab->foto) : '/mnt/data/59c97410-9833-4b89-a29f-84a48c461076.png' }}"
            class="w-full h-40 object-cover">

        <div class="p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $lab->name }}
                </h3>

                <span class="inline-block px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                    {{ $lab->prodi }}
                </span>

                <span class="inline-block px-3 py-1 text-xs rounded-full
                    @if($lab->status == 'Tersedia') bg-green-100 text-green-700
                    @elseif($lab->status == 'Maintenance') bg-red-100 text-red-700
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ $lab->status }}
                </span>
            </div>

            <p class="text-gray-600 mt-1">{{ $lab->kode_lab }}</p>

            <p class="text-gray-600 text-sm mt-3 flex items-center gap-1">
                <span class="material-symbols-outlined text-gray-500 text-[12px]">location_on</span>
                {{ $lab->lokasi }}
            </p>

            <p class="text-gray-700 text-sm mt-3 flex items-center justify-between">
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-gray-500 text-xs leading-none">group</span>
                    Kapasitas
                </span>

                <span>{{ $lab->kapasitas }} orang</span>
            </p>

            <p class="text-gray-700 text-lg mt-3">
                <span class="font-normal text-xs">Penanggung Jawab</span><br>
                {{ $lab->pj }}
            </p>

            <div class="flex justify-end gap-2 mt-5 pt-3 border-t border-gray-200">
                <button
                    @click="
                        openEdit = true;
                        editData = {
                            id: '{{ $lab->id }}',
                            name: '{{ $lab->name }}',
                            kode_lab: '{{ $lab->kode_lab }}',
                            lokasi: '{{ $lab->lokasi }}',
                            prodi: '{{ $lab->prodi }}',
                            kapasitas: '{{ $lab->kapasitas }}',
                            pj: '{{ $lab->pj }}',
                            status: '{{ $lab->status }}',
                            foto: '{{ $lab->foto }}'
                        }
                    "
                    class="flex items-center gap-2 text-blue-600 hover:text-blue-800
                        border border-transparent hover:border-blue-300 hover:bg-blue-50
                        px-3 py-2 rounded-lg transition w-full justify-center">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    <span>Edit</span>
                </button>

                <form action="{{ route('superadmin.labs.destroy', $lab->id) }}" method="POST">
                    @csrf @method('DELETE')

                    <button class="flex items-center gap-1 text-red-600 hover:text-red-800 
                        border border-transparent hover:border-red-300 hover:bg-red-50 
                        px-2 py-1 rounded-lg transition">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@empty
    <p class="text-gray-500 col-span-full">Tidak ada hasil.</p>
@endforelse
