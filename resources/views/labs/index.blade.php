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


        </div>

        <p class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-1 text-gray-600 text-base font-normal -mt-1">
            @if(auth()->user()->role === 'superadmin')
                Kelola semua laboraturium
            @else
                Kelola laboratorium {{ auth()->user()->prodi }}
            @endif
        </p>

        {{-- Flash Messages --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-1 mt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        </div>


        {{-- ================= SEARCH ================= --}}
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="mb-5 bg-white-800 rounded-2xl shadow-sm border border-gray-200 p-4 mt-2">

                    <div class="relative w-full rounded-xl">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            search
                        </span>

                        <input type="text" x-model="keyword" @input.debounce.350ms="doSearch()"
                            placeholder="Cari berdasarkan Nama, Kode, Lokasi, atau Tipe Lab..."
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

                                {{-- Status & Type Badge --}}
                                <div class="absolute top-2 right-2 flex flex-col gap-1 items-end">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-md" :class="{
                                            'bg-green-300 text-gray-800': lab.status?.slug == 'tersedia',
                                            'bg-red-300 text-gray-800': lab.status?.slug == 'maintenance',
                                            'bg-yellow-300 text-gray-800': lab.status?.slug == 'digunakan'
                                        }" x-text="lab.status?.name || '-'"></span>
                                </div>
                            </div>

                            <div class="p-4 flex flex-col flex-grow">

                                {{-- NAMA LAB --}}
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition leading-snug mb-3"
                                    x-text="lab.name"></h3>

                                {{-- LOKASI & TIPE --}}
                                <div class="space-y-1 mb-3">
                                    <p class="text-gray-600 text-sm flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-gray-400 text-lg">location_on</span>
                                        <span x-text="lab.lokasi"></span>
                                    </p>
                                    <p class="text-gray-600 text-sm flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-gray-400 text-lg">category</span>
                                        <span class="capitalize" x-text="lab.type?.name || '-'"></span>
                                    </p>
                                </div>

                                {{-- INFORMASI P.J., KAPASITAS, DAN PRODI (di dalam box latar belakang) --}}
                                <div class="bg-gray-50 rounded-lg p-3 space-y-2 flex-grow">

                                    {{-- Kapasitas & PJ --}}
                                    <div class="flex items-center gap-4">
                                        <p class="flex items-center gap-2 text-gray-700 text-sm">
                                            <span class="material-symbols-outlined text-gray-500 text-base">group</span>
                                            <span class="font-medium" x-text="lab.kapasitas"></span>
                                        </p>

                                        <div class="h-4 w-px bg-gray-300"></div>

                                        <div class="text-sm text-gray-700">
                                            <span class="text-xs text-gray-500 block -mb-0.5">Penanggung Jawab</span>
                                            <span class="font-medium" x-text="lab.admin?.name || '-'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- AKSI TOMBOL --}}
                            <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100">

                                {{-- EDIT Button (Full width) --}}
                                <button type="button" @click.stop="openEditModal(lab)"
                                    class="flex items-center gap-2 text-blue-700 font-medium bg-blue-50 
                                        hover:bg-blue-100 hover:text-blue-800
                                        px-4 py-2 rounded-lg transition-all duration-200 w-full justify-center text-sm border border-blue-200 shadow-sm active:scale-95">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                    <span>Edit Lab</span>
                                </button>

                                {{-- DELETE Button (Icon only) --}}
                                <form :action="`/labs/${lab.id}`" method="POST"
                                    on_submit="return confirm('Apakah Anda yakin ingin menghapus laboratorium ' + lab.name + '?')"
                                    class="flex-shrink-0">
                                    @csrf @method('DELETE')

                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 
                                            p-2 rounded-lg transition-all duration-200 border border-red-200 shadow-sm active:scale-95 flex items-center justify-center">
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
    <div x-show="openCreate" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

        <div @click.outside="openCreate = false" class="bg-white w-[34rem] p-6 rounded-xl shadow-lg">

            <h2 class="text-xl font-bold mb-4">Tambah Laboratorium Baru</h2>

            <form action="{{ route('labs.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                    {{-- Nama Lab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Nama
                            Laboratorium</label>
                        <input name="name" required placeholder="Contoh: Lab Komputer 1"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                    </div>

                    {{-- Kode Lab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Kode
                            Lab</label>
                        <input name="kode_lab" required placeholder="Contoh: LK-01"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                    </div>

                    {{-- Kapasitas --}}
                    <div class="space-y-1">
                        <label
                            class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Kapasitas</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">groups</span>
                            <input type="number" name="kapasitas" required placeholder="0"
                                class="w-full pl-10 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                        </div>
                    </div>

                    {{-- Tipe Lab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Tipe
                            Lab</label>
                        <select name="type_id" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm appearance-none">
                            <option value="">Pilih Tipe Lab</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Lokasi /
                            Gedung</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">apartment</span>
                            <input name="lokasi" required placeholder="Gedung A, Lantai 2"
                                class="w-full pl-10 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                        </div>
                    </div>

                    {{-- Penanggung Jawab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Penanggung
                            Jawab (Admin)</label>
                        <select name="admin_id" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm appearance-none">
                            <option value="">Pilih Admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1">
                        <label
                            class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Status</label>
                        <select name="status_id" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm appearance-none">
                            <option value="">Pilih Status</option>
                            @foreach($labStatuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Foto Lab --}}
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Foto
                            Lab</label>
                        <div
                            class="mt-0.5 flex justify-center px-4 py-3 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50">
                            <div class="space-y-0.5 text-center">
                                <span class="material-symbols-outlined text-2xl text-gray-400">image</span>
                                <div class="flex text-xs text-gray-600">
                                    <label
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input name="foto" type="file" class="sr-only" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" @click="openCreate = false"
                        class="px-5 py-2 text-sm text-gray-600 font-semibold hover:bg-gray-100 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 text-sm bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition active:scale-95">
                        Simpan Lab
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                    {{-- Nama Lab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Nama
                            Laboratorium</label>
                        <input name="name" x-model="editData.name" required placeholder="Contoh: Lab Komputer 1"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                    </div>

                    {{-- Kode Lab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Kode
                            Lab</label>
                        <input name="kode_lab" x-model="editData.kode_lab" required placeholder="Contoh: LK-01"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                    </div>

                    {{-- Kapasitas --}}
                    <div class="space-y-1">
                        <label
                            class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Kapasitas</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">groups</span>
                            <input type="number" name="kapasitas" x-model="editData.kapasitas" required placeholder="0"
                                class="w-full pl-10 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                        </div>
                    </div>

                    {{-- Tipe Lab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Tipe
                            Lab</label>
                        <select name="type_id" x-model="editData.type_id" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm appearance-none">
                            <option value="">Pilih Tipe Lab</option>
                            @foreach($labTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Lokasi /
                            Gedung</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">apartment</span>
                            <input name="lokasi" x-model="editData.lokasi" required placeholder="Gedung A, Lantai 2"
                                class="w-full pl-10 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm">
                        </div>
                    </div>

                    {{-- Penanggung Jawab --}}
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">P.
                            Jawab (Admin)</label>
                        <select name="admin_id" x-model="editData.admin_id" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm appearance-none">
                            <option value="">Pilih Admin</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-1">
                        <label
                            class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Status</label>
                        <select name="status_id" x-model="editData.status_id" required
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-sm appearance-none">
                            <option value="">Pilih Status</option>
                            @foreach($labStatuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Foto Lab --}}
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-semibold text-gray-700 ml-0.5 uppercase tracking-wider">Foto
                            Lab</label>

                        <template x-if="editData.foto">
                            <div class="mb-2 flex items-center gap-3 bg-blue-50 p-2 rounded-lg border border-blue-100">
                                <img :src="'/storage/' + editData.foto"
                                    class="w-12 h-12 object-cover rounded shadow-sm">
                                <div class="text-[10px]">
                                    <p class="font-semibold text-blue-800 uppercase tracking-tight">Foto Saat Ini
                                    </p>
                                </div>
                            </div>
                        </template>

                        <div
                            class="mt-0.5 flex justify-center px-4 py-2 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50">
                            <div class="space-y-0.5 text-center">
                                <span class="material-symbols-outlined text-xl text-gray-400">upload_file</span>
                                <div class="flex text-xs text-gray-600 text-center justify-center">
                                    <label
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload baru</span>
                                        <input name="foto" type="file" class="sr-only" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" @click="openEdit = false"
                        class="px-5 py-2 text-sm text-gray-600 font-semibold hover:bg-gray-100 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 text-sm bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition active:scale-95">
                        Update Lab
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
                newType: "praktikum", // State untuk input tipe lab baru
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

                init() {
                    // Inisialisasi awal
                    this.labs = @json($labs);
                }

            };
        }
    </script>


</x-app-layout>