<x-app-layout>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    {{-- ROOT ALPINE INSTANCE --}}
    {{-- TAMBAHKAN: ml-64 untuk menggeser konten utama dari sidebar fixed --}}
    <div x-data="labPage()" x-cloak class="py-6">

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
            @if(auth()->user()->role === 'superadmin')
                Kelola semua laboraturium
            @else
                Kelola laboratorium {{ auth()->user()->prodi }}
            @endif
        </p>


        {{-- ================= SEARCH ================= --}}
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="mb-5 bg-white-800 rounded-2xl shadow-sm border border-gray-200 p-4 mt-2">

                    <div class="relative w-full rounded-xl">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            search
                        </span>

                        <input type="text" x-model="keyword" @input.debounce.350ms="doSearch()"
                            placeholder="Cari berdasarkan Nama, Kode, atau Lokasi Lab..."
                            class="w-full border border-gray-300 pl-11 pr-4 py-2.5 rounded-lg">
                    </div>
                </div>


                <hr class="mb-6 border-gray-200">


                {{-- ================= GRID CARD (Gaya Mirip Contoh Gambar) ================= --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                    <template x-if="labs.length === 0">
                        <p class="text-gray-500 col-span-full">Tidak ada hasil.</p>
                    </template>

                    <template x-for="lab in labs" :key="lab.id">
                        {{-- TAMBAH CLASS "group" pada container card --}}
                        <div
                            class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-200 overflow-hidden border border-gray-100 flex flex-col group">

                            {{-- FOTO & STATUS BADGE --}}
                            <div class="relative w-full h-40 flex-shrink-0">
                                <img :src="lab.foto ? ('/storage/' + lab.foto) : 'https://placehold.co/400x200/fafafa/D4D4D4?text=LAB+Image'"
                                    class="w-full h-full object-cover border-b border-gray-100">

                                <template x-if="lab.prodi">
                                    <span
                                        class="absolute top-2 left-2 px-3 py-1 text-xs font-semibold rounded-full shadow-md bg-blue-300 text-gray-800">
                                        <span x-text="lab.prodi"></span>
                                    </span>
                                </template>
                                {{-- Status Badge --}}
                                <span
                                    class="absolute top-2 right-2 px-3 py-1 text-xs font-semibold rounded-full shadow-md"
                                    :class="{
                            'bg-green-300 text-gray-800': lab.status=='Tersedia',
                            'bg-red-300 text-gray-800': lab.status=='Maintenance',
                            'bg-yellow-300 text-gray-800': lab.status=='Digunakan'
                        }" x-text="lab.status"></span>
                            </div>

                            <div class="p-4 flex flex-col flex-grow">

                                {{-- NAMA LAB --}}
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition leading-snug mb-3"
                                    x-text="lab.name"></h3>

                                {{-- LOKASI (Gedung) --}}
                                <p class="text-gray-600 text-sm flex items-center gap-1 mb-3">
                                    <span class="material-symbols-outlined text-gray-500 text-base">location_on</span>
                                    <span x-text="lab.lokasi"></span>
                                </p>

                                {{-- INFORMASI P.J., KAPASITAS, DAN PRODI (di dalam box latar belakang) --}}
                                <div class="bg-gray-50 rounded-lg p-3 space-y-2 flex-grow">

                                    <div class="flex items-center justify-between"> {{-- Menggunakan justify-between
                                        untuk Prodi di sisi kanan --}}

                                        {{-- Kapasitas & PJ --}}
                                        <div class="flex items-center gap-4">
                                            <p class="flex items-center gap-2 text-gray-700 text-sm">
                                                <span
                                                    class="material-symbols-outlined text-gray-500 text-base">group</span>
                                                <span class="font-medium" x-text="lab.kapasitas"></span>
                                            </p>

                                            <div class="h-4 w-px bg-gray-300"></div>

                                            <div class="text-sm text-gray-700">
                                                <span class="text-xs text-gray-500 block -mb-0.5">Penanggung
                                                    Jawab</span>
                                                <span class="font-medium" x-text="lab.pj"></span>
                                            </div>
                                        </div>

                                        {{-- PRODI (Di Sisi Kanan) --}}
                                        <template x-if="lab.prodi">
                                            <p class="text-gray-600 text-sm flex items-center gap-1.5 flex-shrink-0">
                                            <div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>
                                            <span x-text="lab.prodi"></span>
                                            </p>
                                        </template>

                                    </div>
                                </div>

                                {{-- Catatan: Blok PRODI yang sebelumnya di luar box telah dihapus --}}


                                {{-- AKSI TOMBOL --}}
                                <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100">

                                    {{-- EDIT Button (Full width) --}}
                                    <button @click="openEditModal(lab)" class="flex items-center gap-2 text-blue-700 font-medium bg-blue-50 
                                hover:bg-blue-100 
                                px-4 py-2 rounded-lg transition w-full justify-center text-sm border border-blue-200">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                        Edit
                                    </button>

                                    {{-- DELETE Button (Icon only) --}}
                                    <form :action="`/labs/${lab.id}`" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus laboratorium ' + lab.name + '?')">
                                        @csrf @method('DELETE')

                                        <button class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 
                                    p-2 rounded-lg transition border border-red-200 flex-shrink-0">
                                            <span class="material-symbols-outlined text-lg">delete</span>
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
        <div x-show="openCreate" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.outside="openCreate = false" class="bg-white w-[34rem] p-6 rounded-xl shadow-lg">

                <h2 class="text-xl font-bold mb-4">Tambah Laboratorium Baru</h2>

                <form action="{{ route('labs.store') }}" method="POST" enctype="multipart/form-data">
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
                        @if(auth()->user()->role === 'superadmin')
                            <div class="relative">
                                <label class="block">Prodi</label>
                                {{-- Input ID tersembunyi --}}
                                <input type="hidden" name="prodi_id" x-model="selectedProdiId">

                                {{-- Input Pencarian Nama --}}
                                <input type="text" class="w-full border p-2 rounded mb-3" x-model="searchProdi"
                                    @input="filterProdi()" @focus="filterProdi()" placeholder="Ketik nama prodi..."
                                    autocomplete="off">

                                <ul x-show="filteredProdi.length > 0" @click.outside="filteredProdi = []"
                                    class="absolute z-50 bg-white border w-full rounded shadow max-h-32 overflow-y-auto">
                                    <template x-for="item in filteredProdi" :key="item.id">
                                        <li @click="selectProdi(item)"
                                            class="px-3 py-2 hover:bg-blue-600 hover:text-white cursor-pointer text-sm"
                                            x-text="item.name"></li>
                                    </template>
                                </ul>
                            </div>
                        @else
                            {{-- Admin Biasa: Prodi otomatis di backend, tidak perlu input --}}
                        @endif


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
                        <button type="button" @click="openCreate = false" class="px-4 py-2 bg-gray-300 rounded">
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
        <div x-show="openEdit" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div @click.outside="openEdit = false" class="bg-white w-[34rem] p-6 rounded-xl shadow-lg">

                <h2 class="text-xl font-bold mb-4">Edit Laboratorium</h2>

                <form :action="`/labs/${editData.id}`" method="POST" enctype="multipart/form-data">
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
                        @if(auth()->user()->role === 'superadmin')
                            <div class="relative">
                                <label class="block">Prodi</label>
                                <input type="hidden" name="prodi_id" x-model="editData.prodi_id">

                                <input type="text" class="w-full border p-2 rounded mb-3" x-model="editData.prodi"
                                    @input="filterProdi(true)" @focus="filterProdi(true)" placeholder="Ketik nama prodi..."
                                    autocomplete="off">

                                <ul x-show="filteredProdi.length > 0" @click.outside="filteredProdi = []"
                                    class="absolute z-50 bg-white border w-full rounded shadow max-h-32 overflow-y-auto">
                                    <template x-for="item in filteredProdi" :key="item.id">
                                        <li @click="selectProdi(item, true)"
                                            class="px-3 py-2 hover:bg-blue-600 hover:text-white cursor-pointer text-sm"
                                            x-text="item.name"></li>
                                    </template>
                                </ul>
                            </div>
                        @endif


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
                            <img :src="'/storage/' + editData.foto" class="w-40 h-28 object-cover rounded border mt-1">
                        </div>
                    </template>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-300 rounded">
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

            const baseURL = "{{ route('labs.index') }}";

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
                    const q = (this.keyword || "").trim();

                    // Jika keyword tidak ada, tampilkan data awal (tanpa filter)
                    // Jika Anda ingin fetch data awal dari server, ganti logika ini.
                    // Untuk saat ini, kita anggap data awal ada di this.labs saat inisialisasi.
                    if (q === "") {
                        this.labs = @json($labs);
                        return;
                    }

                    if (q.length < 2) return;

                    if (q in this.cache) {
                        this.labs = this.cache[q];
                        return;
                    }

                    if (this.currentAbortController) {
                        this.currentAbortController.abort();
                    }

                    this.currentAbortController = new AbortController();
                    this.loading = true;

                    try {
                        // Catatan: Pastikan route('labs.index') Anda dapat menerima query parameter 'search' dan 'ajax'
                        const res = await fetch(`${baseURL}?ajax=1&search=${encodeURIComponent(q)}`, {
                            signal: this.currentAbortController.signal,
                            headers: { 'Accept': 'application/json' }
                        });

                        if (!res.ok) throw new Error("Network error");

                        const json = await res.json();
                        this.cache[q] = json;
                        this.labs = json;

                    } catch (err) {
                        if (err.name !== "AbortError") console.error(err);
                    } finally {
                        this.loading = false;
                        this.currentAbortController = null;
                    }
                },

                openEditModal(lab) {
                    // Membuat salinan data agar perubahan di modal tidak langsung mempengaruhi tampilan grid sebelum disubmit
                    this.editData = { ...lab };
                    this.openEdit = true;
                },

                searchProdi: "",
                selectedProdiId: "", // Untuk create form
                filteredProdi: [],
                // Data dari controller sekarang adalah array of objects {id, name}
                allProdi: @json($prodiList),


                filterProdi(isEdit = false) {
                    const q = isEdit ? this.editData.prodi : this.searchProdi;
                    if (!q && q !== "") {
                        this.filteredProdi = [];
                        return;
                    }

                    // Show all if empty or on focus
                    if (q === "") {
                        this.filteredProdi = this.allProdi.slice(0, 10);
                        return;
                    }

                    const term = q.toLowerCase();
                    this.filteredProdi = this.allProdi.filter(p =>
                        p.name.toLowerCase().includes(term)
                    ).slice(0, 10);
                },

                selectProdi(item, isEdit = false) {
                    if (isEdit) {
                        this.editData.prodi = item.name;
                        this.editData.prodi_id = item.id;
                    } else {
                        this.searchProdi = item.name;
                        this.selectedProdiId = item.id;
                    }
                    this.filteredProdi = [];
                },

                init() {
                    // Inisialisasi awal
                    this.labs = @json($labs);
                }

            };
        }
    </script>


</x-app-layout>