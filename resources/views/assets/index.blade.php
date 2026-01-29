<x-app-layout title="Manajemen Aset Lab">
    <div class="max-w-[1600px] mx-auto py-2" x-data="{
        selectedLabId: '{{ $labs->first()->id ?? '' }}',
        showModal: false,
        showDetailModal: false,
        editMode: false,
        searchTerm: '',
        expandedProdiId: null,
        selectedAsset: null,
        formData: {
            id: '',
            lab_id: '',
            nama: '',
            kategori: '',
            kode_aset: '',
            jumlah: 1,
            maintenance_count: 0
        },
        assets: {{ $assets->toJson() }},
        labs: {{ $labs->toJson() }},

        get filteredAssets() {
            return this.assets.filter(a => a.lab_id === this.selectedLabId);
        },
        get selectedLab() {
            return this.labs.find(l => l.id === this.selectedLabId);
        },
        get filteredLabsList() {
            const term = this.searchTerm.toLowerCase();
            if (!term) return this.labs;
            return this.labs.filter(lab => lab.name.toLowerCase().includes(term));
        },
        
        openCreateModal(labId = null) {
            this.editMode = false;
            if (labId) {
                this.selectedLabId = labId;
            }
            this.formData = {
                id: '',
                lab_id: this.selectedLabId,
                nama: '',
                kategori: '',
                kode_aset: '',
                jumlah: 1,
                maintenance_count: 0
            };
            this.showModal = true;
        },
        
        openEditModal(asset) {
            this.editMode = true;
            this.formData = {
                id: asset.id,
                lab_id: asset.lab_id,
                nama: asset.nama,
                kategori: asset.kategori || '',
                kode_aset: asset.kode_aset,
                jumlah: asset.jumlah,
                maintenance_count: asset.maintenance_count || 0
            };
            this.showModal = true;
        },

        openDetailModal(asset) {
            this.selectedAsset = asset;
            this.showDetailModal = true;
        }
    }">

        {{-- Modern Gradient Header --}}
        <div
            class="bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 rounded-[2rem] p-6 sm:p-10 mb-8 shadow-2xl shadow-blue-200 relative overflow-hidden transition-all duration-700 hover:shadow-blue-300/50">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400/20 rounded-full -ml-10 -mb-10 blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/20">Inventory
                            System</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-3 leading-none">Inventaris
                        Lab</h1>
                    <p class="text-blue-100 font-medium text-base opacity-90 max-w-xl leading-relaxed">Kelola dan pantau
                        seluruh aset perlengkapan di setiap laboratorium secara terpusat.</p>
                </div>
                <div
                    class="flex items-center gap-4 bg-white/10 backdrop-blur-xl p-4 sm:p-5 rounded-[1.5rem] border border-white/20 shadow-inner group hover:bg-white/20 transition-all duration-500 hover:scale-105">
                    <div
                        class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-xl group-hover:rotate-12 transition-transform duration-500">
                        <span class="material-symbols-outlined text-2xl">inventory_2</span>
                    </div>
                    <div class="pr-4">
                        <p class="text-xs font-black text-blue-200 uppercase tracking-widest mb-1 opacity-70">Total Aset
                        </p>
                        <p class="text-white font-black text-xl tracking-tight">
                            {{ number_format($assets->sum('jumlah')) }} Items
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10 items-start px-2">
            {{-- SIDEBAR: LIST LABS --}}
            <aside class="w-full lg:w-96 flex-shrink-0 lg:sticky lg:top-8">
                <div
                    class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden ring-1 ring-slate-100/50">

                    <div class="px-8 py-7 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-base">domain</span>
                            </div>
                            <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Daftar Laboratorium
                            </h2>
                        </div>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-[20px]">search</span>
                            <input type="text" x-model="searchTerm" placeholder="Cari lab..."
                                class="w-full bg-white border-slate-100 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300 shadow-inner">
                        </div>
                    </div>

                    <div class="max-h-[600px] overflow-y-auto scrollbar-none select-none p-4 space-y-2">
                        <template x-for="lab in filteredLabsList" :key="lab.id">
                            <div @click="selectedLabId = lab.id"
                                :class="selectedLabId === lab.id ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600'"
                                class="p-4 cursor-pointer transition-all rounded-[1.5rem] border border-transparent flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                                        :class="selectedLabId === lab.id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-500'">
                                        <span class="material-symbols-outlined text-[20px]">door_front</span>
                                    </div>
                                    <div class="font-black text-sm tracking-tighter truncate" x-text="lab.name"></div>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredLabsList.length === 0"
                            class="p-12 text-center text-slate-400 italic text-sm">
                            <span class="material-symbols-outlined text-4xl mb-4 opacity-20 block">search_off</span>
                            Tidak ada hasil untuk "<span x-text="searchTerm" class="font-bold"></span>"
                        </div>
                    </div>
                </div>
            </aside>

            {{-- MAIN AREA: ASSETS --}}
            <main class="flex-1 min-w-0">
                {{-- HEADER LAB AKTIF --}}
                <template x-if="selectedLab">
                    <div
                        class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-6 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 relative overflow-hidden group">
                        {{-- Decorative background glow --}}
                        <div
                            class="absolute -right-10 -top-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity duration-700">
                        </div>

                        <div class="relative z-10 flex items-center gap-6">
                            <div
                                class="w-20 h-20 rounded-[2rem] bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center text-blue-600 shadow-inner border border-white">
                                <span class="material-symbols-outlined text-4xl">home_storage</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h1 class="text-2xl font-black text-slate-900 tracking-tighter"
                                        x-text="selectedLab.name"></h1>
                                    <span
                                        class="px-3 py-1 rounded-full bg-blue-600 text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-200"
                                        x-text="selectedLab.kode_lab"></span>
                                </div>
                                <div
                                    class="flex flex-wrap items-center gap-y-2 gap-x-6 text-slate-400 font-bold text-xs uppercase tracking-widest">
                                    <p class="flex items-center gap-2">
                                        <span
                                            class="material-symbols-outlined text-base text-blue-500">location_on</span>
                                        <span x-text="selectedLab.lokasi" class="text-slate-600"></span>
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base text-indigo-500">groups</span>
                                        <span x-text="selectedLab.kapasitas + ' Kapasitas'"
                                            class="text-slate-600"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Button removed as per request -->
                         {{-- Create Button (Moved here) --}}
                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <button @click="openCreateModal(selectedLabId)"
                        class="bg-blue-600 text-white-600 px-6 py-4 rounded-[1.5rem] font-white text-[11px] uppercase tracking-[0.2em] flex items-center gap-3 shadow-2xl shadow-blue-900/20 hover:text-white transition-all active:scale-95 group/btn h-full whitespace-nowrap">
                        <span
                            class="material-symbols-outlined text-xl group-hover/btn:rotate-90 transition-transform">add</span>
                        Tambah Aset
                    </button>
                @endif
                    </div>
                </template>

                {{-- GRID ASSET REDESIGNED --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="asset in filteredAssets" :key="asset.id">
                        <div @click="openDetailModal(asset)"
                            class="group bg-white rounded-[1.5rem] border-2 transition-all duration-500 hover:shadow-2xl overflow-hidden flex flex-col h-full cursor-pointer transform hover:-translate-y-1"
                            :class="(asset.jumlah - (asset.borrowed_count || 0) - (asset.maintenance_count || 0)) <= 5 
                                ? 'border-amber-100 hover:border-amber-400' 
                                : 'border-emerald-50 hover:border-emerald-400'">

                            <div class="p-6 flex-grow">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-grow min-w-0">
                                        <h3 class="font-black text-slate-800 text-xl tracking-tighter leading-tight truncate pr-2"
                                            x-text="asset.nama"></h3>
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1"
                                            x-text="asset.kode_aset || 'NO-ID'"></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm"
                                            :class="(asset.jumlah - (asset.borrowed_count || 0) - (asset.maintenance_count || 0)) <= 5 
                                                ? 'bg-gradient-to-r from-amber-400 to-orange-500 text-white' 
                                                : 'bg-gradient-to-r from-emerald-400 to-teal-500 text-white'">
                                            <span
                                                x-text="(asset.jumlah - (asset.borrowed_count || 0) - (asset.maintenance_count || 0)) <= 5 ? 'Low Stock' : 'Available'"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 mb-6">
                                    <div
                                        class="flex items-center gap-1.5 text-blue-600 font-black text-[10px] uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        <span x-text="selectedLab.name"></span>
                                    </div>
                                    <div class="text-slate-400 font-bold text-[10px] uppercase tracking-wider bg-slate-50 px-2 py-0.5 rounded-md"
                                        x-text="asset.kategori || 'Uncategorized'"></div>
                                </div>

                                <div class="flex justify-between items-end mb-4">
                                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.1em]">Total
                                        Units</span>
                                    <span class="text-2xl font-black text-slate-800 tracking-tighter"
                                        x-text="asset.jumlah"></span>
                                </div>

                                <div class="grid grid-cols-3 gap-2 mb-6">
                                    <div
                                        class="bg-emerald-50 rounded-2xl p-3 flex flex-col items-center justify-center border border-emerald-100/50">
                                        <span class="text-emerald-600 font-black text-lg leading-none"
                                            x-text="asset.jumlah - (asset.borrowed_count || 0) - (asset.maintenance_count || 0)"></span>
                                        <span
                                            class="text-[9px] font-black text-emerald-600/60 uppercase mt-1">Available</span>
                                    </div>
                                    <div
                                        class="bg-blue-50 rounded-2xl p-3 flex flex-col items-center justify-center border border-blue-100/50">
                                        <span class="text-blue-600 font-black text-lg leading-none"
                                            x-text="asset.borrowed_count || 0"></span>
                                        <span
                                            class="text-[9px] font-black text-blue-600/60 uppercase mt-1">Borrowed</span>
                                    </div>
                                    <div
                                        class="bg-slate-50 rounded-2xl p-3 flex flex-col items-center justify-center border border-slate-200/50">
                                        <span class="text-slate-600 font-black text-lg leading-none"
                                            x-text="asset.maintenance_count || 0"></span>
                                        <span
                                            class="text-[9px] font-black text-slate-600/60 uppercase mt-1">Maint'</span>
                                    </div>
                                </div>

                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="h-full transition-all duration-1000 ease-out"
                                        :class="(asset.jumlah - (asset.borrowed_count || 0) - (asset.maintenance_count || 0)) <= 5 ? 'bg-amber-500' : 'bg-emerald-500'"
                                        :style="'width: ' + ((asset.jumlah - (asset.borrowed_count || 0) - (asset.maintenance_count || 0)) / asset.jumlah * 100) + '%'">
                                    </div>
                                </div>
                            </div>

                            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center gap-3">
                                    <button @click.stop="openEditModal(asset)"
                                        class="flex-1 text-[10px] font-black py-3 rounded-xl bg-white text-slate-600 border border-slate-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-2 shadow-sm">
                                        <span class="material-symbols-outlined text-[18px]">edit_note</span>
                                        Edit
                                    </button>
                                    <form :action="'/assets/' + asset.id" method="POST"
                                        onsubmit="return confirm('Hapus aset ini?')" class="flex-none">
                                        @csrf @method('DELETE')
                                        <button @click.stop
                                            class="w-10 h-10 rounded-xl bg-white text-slate-300 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 border border-slate-200 transition-all flex items-center justify-center shadow-sm">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </template>
                </div>

                {{-- EMPTY STATE --}}
                <template x-if="filteredAssets.length === 0">
                    <div
                        class="bg-white rounded-[3rem] p-24 text-center shadow-xl shadow-slate-200/30 border border-slate-100 mt-8 group">
                        <div
                            class="w-32 h-32 rounded-[2.5rem] bg-slate-50 flex items-center justify-center mx-auto mb-8 text-slate-200 group-hover:scale-110 transition-transform duration-700">
                            <span
                                class="material-symbols-outlined text-6xl opacity-30 group-hover:text-blue-500 group-hover:opacity-100 transition-all duration-700">inventory</span>
                        </div>
                        <h3 class="text-slate-900 font-black text-2xl tracking-tighter mb-2">Belum Ada Aset Ditemukan
                        </h3>
                        <p
                            class="text-slate-400 font-medium text-sm max-w-[320px] mx-auto tracking-tight mb-10 leading-relaxed">
                            Laboratorium ini belum memiliki data inventaris yang terdaftar dalam sistem.</p>
                    </div>
                </template>
            </main>
        </div>

        {{-- MODAL CREATE/EDIT --}}
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Backdrop --}}
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showModal = false"
                    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showModal" x-transition:enter="ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white">

                    <form :action="editMode ? '/assets/' + formData.id : '{{ route('assets.store') }}'" method="POST"
                        class="p-8 sm:p-10">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="mb-10 flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-black text-slate-900 tracking-tighter"
                                    x-text="editMode ? 'Edit Aset' : 'Tambah Aset'"></h2>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1"
                                    x-text="selectedLab ? 'Ke: ' + selectedLab.name : ''"></p>
                            </div>
                            <div
                                class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                                <span class="material-symbols-outlined text-3xl"
                                    x-text="editMode ? 'edit_square' : 'add_box'"></span>
                            </div>
                        </div>

                        {{-- LAB SELECTION --}}
                        <div class="mb-8">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Pilih
                                Laboratorium</label>
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">door_front</span>
                                <select name="lab_id" x-model="formData.lab_id" required
                                    class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                                    <option value="">-- Pilih Lab --</option>
                                    <template x-for="lab in labs" :key="lab.id">
                                        <option :value="lab.id" x-text="lab.name"
                                            :selected="lab.id === formData.lab_id"></option>
                                    </template>
                                </select>
                                <span
                                    class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                            </div>
                            <template x-if="labs.length === 0">
                                <p class="text-rose-500 text-[10px] mt-2 font-bold ml-1 tracking-tight">Peringatan:
                                    Tidak ada Lab yang tersedia di Prodi ini. Silakan buat Lab terlebih dahulu.</p>
                            </template>
                        </div>

                        <div class="space-y-8">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Nama
                                    Aset</label>
                                <div class="relative group">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">inventory_2</span>
                                    <input type="text" name="nama" x-model="formData.nama" required
                                        placeholder="Contoh: Monitor LG 24 Inch"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Kategori
                                    (Opsional)</label>
                                <div class="relative group">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">category</span>
                                    <input type="text" name="kategori" x-model="formData.kategori"
                                        placeholder="Contoh: Computer, Monitor, dll"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Kode
                                        Aset (Opsional)</label>
                                    <div class="relative group">
                                        <span
                                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">qr_code</span>
                                        <input type="text" name="kode_aset" x-model="formData.kode_aset"
                                            placeholder="AST-XXX"
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Total
                                        Unit</label>
                                    <div class="relative group">
                                        <span
                                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">inventory</span>
                                        <input type="number" name="jumlah" x-model="formData.jumlah" required min="1"
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Dalam
                                    Maintenance</label>
                                <div class="relative group">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">engineering</span>
                                    <input type="number" name="maintenance_count" x-model="formData.maintenance_count"
                                        required min="0"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex gap-4">
                            <button type="button" @click="showModal = false"
                                class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-[2] px-6 py-4 rounded-2xl bg-blue-600 text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg"
                                    x-text="editMode ? 'save' : 'add_circle'"></span>
                                <span x-text="editMode ? 'Simpan Perubahan' : 'Tambahkan Aset'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL ASSET --}}
        <div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Backdrop --}}
                <div x-show="showDetailModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @click="showDetailModal = false"
                    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showDetailModal" x-transition:enter="ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white">

                    <div class="absolute top-4 right-4 z-10">
                        <button @click="showDetailModal = false"
                            class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-slate-500">close</span>
                        </button>
                    </div>

                    <div class="p-8">
                        <template x-if="selectedAsset">
                            <div class="text-center">
                                <div
                                    class="w-24 h-24 rounded-[2rem] bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6 shadow-md">
                                    <span class="material-symbols-outlined text-5xl">inventory_2</span>
                                </div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tighter leading-tight"
                                    x-text="selectedAsset.nama"></h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1"
                                    x-text="selectedAsset.kode_aset || 'NO-ID'"></p>
                                <span
                                    class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider mt-3"
                                    x-text="selectedAsset.kategori || 'Uncategorized'"></span>

                                <div class="grid grid-cols-3 gap-4 mt-8">
                                    <div class="bg-indigo-50 p-4 rounded-2xl">
                                        <p
                                            class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">
                                            Total</p>
                                        <p class="text-2xl font-black text-indigo-700" x-text="selectedAsset.jumlah">
                                        </p>
                                    </div>
                                    <div class="bg-emerald-50 p-4 rounded-2xl">
                                        <p
                                            class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">
                                            Ready</p>
                                        <p class="text-2xl font-black text-emerald-700"
                                            x-text="selectedAsset.jumlah - (selectedAsset.borrowed_count || 0) - (selectedAsset.maintenance_count || 0)">
                                        </p>
                                    </div>
                                    <div class="bg-blue-50 p-4 rounded-2xl">
                                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">
                                            Dipakai</p>
                                        <p class="text-2xl font-black text-blue-700"
                                            x-text="selectedAsset.borrowed_count || 0"></p>
                                    </div>
                                </div>

                                <div class="mt-6 text-left bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-slate-400">Laboratorium</span>
                                        <span class="text-xs font-bold text-slate-700"
                                            x-text="selectedLab ? selectedLab.name : '-'"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-slate-400">Maintenance</span>
                                        <span class="text-xs font-bold text-slate-700"
                                            x-text="(selectedAsset.maintenance_count || 0) + ' Unit'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                flatpickr("#asset-sidebar-calendar", {
                    inline: true,
                    locale: {
                        firstDayOfWeek: 1,
                        weekdays: {
                            shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                            longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
                        },
                        months: {
                            shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                            longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
                        }
                    },
                    defaultDate: new Date(),
                    dateFormat: "Y-m-d",
                    disableMobile: true,
                    showMonths: 1,
                    monthSelectorType: "static",

                    onDayCreate: function (_, __, fp, dayElem) {
                        const date = dayElem.dateObj;
                        const day = date.getDay();

                        if (day === 0 || day === 6) {
                            dayElem.classList.add('holiday');
                        }
                    }
                });
            });
        </script>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush
</x-app-layout>