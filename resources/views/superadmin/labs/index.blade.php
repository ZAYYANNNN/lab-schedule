<x-app-layout>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

{{-- ROOT ALPINE INSTANCE --}}
<div 
    x-data="labPage()" 
    x-cloak
    class="py-6"
>

    {{-- ================= HEADER ================= --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex items-center justify-between px-1">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-tight">
            Daftar Lab
        </h2>

        <button @click="openCreate = true"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition text-base font-medium">
            + Tambah Lab
        </button>
    </div>

    <p class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-1 text-gray-600 text-base font-normal -mt-1">
        Kelola semua laboraturium
    </p>


    {{-- ================= SEARCH ================= --}}
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-5 bg-white-800 rounded-2xl shadow-sm border border-gray-200 p-4 mt-2">

                <div class="relative w-full rounded-xl">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        search
                    </span>

                    <input 
                        type="text"
                        x-model="keyword"
                        @input.debounce.150ms="doSearch()"
                        placeholder="Cari berdasarkan Nama, Kode, atau Lokasi Lab..."
                        class="w-full border border-gray-300 pl-11 pr-4 py-2.5 rounded-lg"
                    >
                </div>

                <template x-if="loading">
                    <p class="text-sm text-gray-500 mt-2">Mencari...</p>
                </template>
            </div>


            <hr class="mb-6 border-gray-200">


            {{-- ================= GRID CARD ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <template x-if="labs.length === 0">
                    <p class="text-gray-500 col-span-full">Tidak ada hasil.</p>
                </template>

                <template x-for="lab in labs" :key="lab.id">
                    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">

                        {{-- FOTO --}}
                        <img 
                            :src="lab.foto ? ('/storage/' + lab.foto) : '/mnt/data/59c97410-9833-4b89-a29f-84a48c461076.png'"
                            class="w-full h-40 object-cover"
                        >

                        <div class="p-4">

                            <div class="flex items-center justify-between gap-1">
                                <h3 class="text-lg font-semibold text-gray-800" x-text="lab.name"></h3>

                                <span class="inline-block px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700"
                                    x-text="lab.prodi">
                                </span>

                                <span 
                                    class="inline-block px-3 py-1 text-xs rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-700': lab.status=='Tersedia',
                                        'bg-red-100 text-red-700': lab.status=='Maintenance',
                                        'bg-yellow-100 text-yellow-700': lab.status=='Digunakan'
                                    }"
                                    x-text="lab.status"
                                ></span>
                            </div>

                            <p class="text-gray-600 mt-1" x-text="lab.kode_lab"></p>

                            <p class="text-gray-600 text-sm mt-3 flex items-center gap-1">
                                <span class="material-symbols-outlined text-gray-500 text-[12px]">location_on</span>
                                <span x-text="lab.lokasi"></span>
                            </p>

                            <p class="text-gray-700 text-sm mt-3 flex items-center justify-between">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-gray-500 text-xs leading-none">group</span>
                                    Kapasitas
                                </span>

                                <span x-text="lab.kapasitas + ' orang'"></span>
                            </p>

                            <p class="text-gray-700 text-lg mt-3">
                                <span class="font-normal text-xs">Penanggung Jawab</span><br>
                                <span x-text="lab.pj"></span>
                            </p>

                            <div class="flex justify-end gap-2 mt-5 pt-3 border-t border-gray-200">

                                {{-- EDIT --}}
                                <button
                                    @click="openEditModal(lab)"
                                    class="flex items-center gap-2 text-blue-600 hover:text-blue-800
                                        border border-transparent hover:border-blue-300 hover:bg-blue-50
                                        px-3 py-2 rounded-lg transition w-full justify-center">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                    <span>Edit</span>
                                </button>

                                {{-- DELETE --}}
                                <form :action="`/superadmin/labs/${lab.id}`" method="POST"
                                      onsubmit="return confirm('Hapus lab ini?')">
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
                </template>

            </div>

        </div>
    </div>



    {{-- ================= MODAL CREATE ================= --}}
    <div x-show="openCreate"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div @click.outside="openCreate = false"
             class="bg-white w-[34rem] p-6 rounded-xl shadow-lg">

            <h2 class="text-xl font-bold mb-4">Tambah Laboratorium Baru</h2>

            <form action="{{ route('superadmin.labs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block">Nama Lab</label>
                        <input name="name" class="w-full border p-2 rounded mb-3">
                    </div>

                    <div>
                        <label class="block">Kode Lab</label>
                        <input name="kode_lab" class="w-full border p-2 rounded mb-3">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block">Prodi</label>
                        <select name="prodi" class="w-full border p-2 rounded mb-3">
                            @foreach(config('prodi') as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block">Kapasitas</label>
                        <input type="number" name="kapasitas" class="w-full border p-2 rounded mb-3">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block">Lokasi</label>
                        <input name="lokasi" class="w-full border p-2 rounded mb-3">
                    </div>

                    <div>
                        <label class="block">Status</label>
                        <select name="status" class="w-full border p-2 rounded mb-3">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Digunakan">Digunakan</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <label class="block">Penanggung Jawab</label>
                <input name="pj" class="w-full border p-2 rounded mb-3">

                <label class="block">Foto Lab</label>
                <input type="file" name="foto" accept="image/*" class="w-full border p-2 rounded mb-3">

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="openCreate = false"
                            class="px-4 py-2 bg-gray-300 rounded">
                        Batal
                    </button>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>



    {{-- ================= MODAL EDIT ================= --}}
    <div x-show="openEdit"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div @click.outside="openEdit = false"
             class="bg-white w-[34rem] p-6 rounded-xl shadow-lg">

            <h2 class="text-xl font-bold mb-4">Edit Laboratorium</h2>

            <form :action="`/superadmin/labs/${editData.id}`" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block">Nama Lab</label>
                        <input name="name" x-model="editData.name" class="w-full border p-2 rounded mb-3">
                    </div>

                    <div>
                        <label class="block">Kode Lab</label>
                        <input name="kode_lab" x-model="editData.kode_lab" class="w-full border p-2 rounded mb-3">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block">Prodi</label>
                        <select name="prodi" x-model="editData.prodi" class="w-full border p-2 rounded mb-3">
                            @foreach(config('prodi') as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block">Kapasitas</label>
                        <input name="kapasitas" x-model="editData.kapasitas" type="number"
                               class="w-full border p-2 rounded mb-3">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block">Lokasi</label>
                        <input name="lokasi" x-model="editData.lokasi" class="w-full border p-2 rounded mb-3">
                    </div>

                    <div>
                        <label class="block">Status</label>
                        <select name="status" x-model="editData.status" class="w-full border p-2 rounded mb-3">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Digunakan">Digunakan</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <label class="block">Penanggung Jawab</label>
                <input name="pj" x-model="editData.pj" class="w-full border p-2 rounded mb-3">

                <label class="block">Foto Baru (optional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full border p-2 rounded mb-3">

                <template x-if="editData.foto">
                    <div class="mt-1">
                        <p class="text-sm text-gray-600">Foto saat ini:</p>
                        <img :src="'/storage/' + editData.foto"
                             class="w-40 h-28 object-cover rounded border mt-1">
                    </div>
                </template>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-300 rounded">
                        Batal
                    </button>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>


</div>





<script>
function labPage() {
    return {
        openCreate: false,
        openEdit: false,
        keyword: "",
        loading: false,

        labs: @json($labs),
        editData: {},

        // untuk batalkan fetch sebelumnya
        currentAbortController: null,
        cache: {}, // cache response per keyword

        async doSearch() {
            const q = this.keyword ?? "";

            // jika ada di cache, pakai cache (instan)
            if (q in this.cache) {
                this.labs = this.cache[q];
                return;
            }

            // batalkan request lama
            if (this.currentAbortController) {
                this.currentAbortController.abort();
            }

            // jangan spam kalau kosong (opsional: aktifkan kalau mau cari mulai 1 huruf)
            // if (q.length === 0) { this.labs = @json($labs); return; }

            this.currentAbortController = new AbortController();
            this.loading = true;

            try {
                const res = await fetch(`/superadmin/labs?ajax=1&search=${encodeURIComponent(q)}`, {
                    signal: this.currentAbortController.signal,
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) throw new Error('Network error');

                const json = await res.json();

                // simpan cache (simple)
                this.cache[q] = json;
                this.labs = json;
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Search error', err);
                }
            } finally {
                this.loading = false;
                this.currentAbortController = null;
            }
        },

        openEditModal(lab) {
            this.editData = { ...lab };
            this.openEdit = true;
        }
    };
}
</script>


</x-app-layout>
