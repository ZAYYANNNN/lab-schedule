<x-app-layout>
    <div class="max-w-[1600px] mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{
        selectedLabId: '{{ $labs->first()->id ?? '' }}',
        showModal: false,
        editMode: false,
        formData: {
            id: '',
            lab_id: '',
            nama: '',
            kode_aset: '',
            jumlah: 1
        },
        assets: {{ $assets->toJson() }},
        labs: {{ $labs->toJson() }},

        get filteredAssets() {
            return this.assets.filter(a => a.lab_id === this.selectedLabId);
        },
        get selectedLab() {
            return this.labs.find(l => l.id === this.selectedLabId);
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
                kode_aset: '',
                jumlah: 1
            };
            this.showModal = true;
        },
        openEditModal(asset) {
            this.editMode = true;
            this.formData = {
                id: asset.id,
                lab_id: asset.lab_id,
                nama: asset.nama,
                kode_aset: asset.kode_aset,
                jumlah: asset.jumlah
            };
            this.showModal = true;
        }
    }">

        <div class="flex flex-col md:flex-row gap-8 min-h-[700px]">
            {{-- SIDEBAR: LIST LABS --}}
            <aside class="w-full md:w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-8">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="font-bold text-slate-800 flex items-center">
                            <span class="material-symbols-outlined mr-2 text-blue-600">door_front</span>
                            Daftar Lab
                        </h2>
                    </div>
                    <div class="max-h-[600px] overflow-y-auto scrollbar-thin select-none">
                        @forelse($labs as $lab)
                            <div @click="selectedLabId = '{{ $lab->id }}'"
                                :class="selectedLabId === '{{ $lab->id }}' ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-slate-50 border-l-4 border-transparent'"
                                class="p-4 cursor-pointer transition-all group">
                                <div class="flex items-center gap-2">
                                    <div class="font-bold text-sm truncate"
                                        :class="selectedLabId === '{{ $lab->id }}' ? 'text-blue-700' : 'text-slate-700'">
                                        {{ $lab->name }}
                                    </div>
                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                        <button @click.stop="openCreateModal('{{ $lab->id }}')" 
                                            class="opacity-0 group-hover:opacity-100 transition-opacity p-1 hover:bg-blue-100 rounded-lg text-blue-600 flex items-center justify-center flex-shrink-0"
                                            title="Tambah Aset ke {{ $lab->name }}">
                                            <span class="material-symbols-outlined text-[20px]">add</span>
                                        </button>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $lab->lokasi }}</div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 italic text-sm">
                                Belum ada lab.
                            </div>
                        @endforelse
                    </div>
                </div>
            </aside>

            {{-- MAIN AREA: ASSETS --}}
            <main class="flex-1 min-w-0">
                {{-- HEADER LAB AKTIF --}}
                <template x-if="selectedLab">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h1 class="text-2xl font-black text-slate-800 tracking-tight" x-text="selectedLab.name">
                                </h1>
                                <span
                                    class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest"
                                    x-text="selectedLab.kode_lab"></span>
                            </div>
                            <p class="text-slate-500 text-sm flex items-center font-medium">
                                <span class="material-symbols-outlined text-xs mr-1 text-slate-400">location_on</span>
                                <span x-text="selectedLab.lokasi"></span>
                                <span class="mx-2 text-slate-300">•</span>
                                <span class="material-symbols-outlined text-xs mr-1 text-slate-400">groups</span>
                                <span x-text="selectedLab.kapasitas + ' Kapasitas'"></span>
                            </p>
                        </div>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                            <button @click="openCreateModal()"
                                class="bg-slate-900 border border-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center shadow-lg shadow-slate-200 hover:bg-slate-800 transition active:scale-95">
                                <span class="material-symbols-outlined mr-2 text-[20px]">add</span>
                                Tambah Aset
                            </button>
                        @endif
                    </div>
                </template>

                {{-- GRID ASSET --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="asset in filteredAssets" :key="asset.id">
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-all group relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-500 transition-colors">
                                    <span class="material-symbols-outlined">inventory_2</span>
                                </div>
                                <div class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-black"
                                    x-text="'Stok: ' + asset.jumlah"></div>
                            </div>

                            <h3 class="font-bold text-slate-800 mb-1 group-hover:text-blue-600 transition-colors"
                                x-text="asset.nama"></h3>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-4"
                                x-text="'KODE: ' + (asset.kode_aset || '-')"></p>

                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                <div class="flex items-center gap-2 pt-4 border-t border-slate-50">
                                    <button @click="openEditModal(asset)"
                                        class="flex-1 text-[11px] font-bold py-2 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[16px] mr-1.5">edit</span>
                                        Edit
                                    </button>
                                    <form :action="'/assets/' + asset.id" method="POST"
                                        onsubmit="return confirm('Hapus aset ini?')">
                                        @csrf @method('DELETE')
                                        <button
                                            class="p-2 rounded-lg bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-600 transition">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </template>
                </div>

                {{-- EMPTY STATE --}}
                <template x-if="filteredAssets.length === 0">
                    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-20 text-center">
                        <div
                            class="w-20 h-20 rounded-full bg-white shadow-sm flex items-center justify-center mx-auto mb-4 text-slate-200">
                            <span class="material-symbols-outlined text-[40px]">inventory</span>
                        </div>
                        <h3 class="text-slate-800 font-bold text-lg">Belum ada aset</h3>
                        <p class="text-slate-400 text-sm max-w-xs mx-auto mt-1">Silahkan tambahkan aset baru untuk lab
                            ini untuk mulai melakukan pendataan.</p>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                            <button @click="openCreateModal()"
                                class="mt-6 text-blue-600 font-bold text-sm hover:underline">Tambah Sekarang</button>
                        @endif
                    </div>
                </template>
            </main>
        </div>

        {{-- MODAL CREATE/EDIT --}}
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-cloak>
            {{-- Backdrop --}}
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showModal = false"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

            {{-- Modal Content --}}
            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden relative z-10">

                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-800" x-text="editMode ? 'Edit Aset' : ('Tambah Aset Baru ' + (selectedLab ? 'ke ' + selectedLab.name : ''))">
                    </h2>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form :action="editMode ? '/assets/' + formData.id : '{{ route('assets.store') }}'" method="POST"
                    class="p-6 space-y-5">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <input type="hidden" name="lab_id" x-model="formData.lab_id">

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama
                            Aset</label>
                        <input type="text" name="nama" x-model="formData.nama" required
                            class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-slate-800 font-semibold focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-300"
                            placeholder="Contoh: Monitor LG 24 Inch">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Kode
                            Aset (Opsional)</label>
                        <input type="text" name="kode_aset" x-model="formData.kode_aset"
                            class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-slate-800 font-semibold focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-300"
                            placeholder="Contoh: AST-001">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Jumlah</label>
                        <input type="number" name="jumlah" x-model="formData.jumlah" required min="1"
                            class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-slate-800 font-semibold focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-300">
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showModal = false"
                            class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-2xl hover:bg-slate-200 transition active:scale-95">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition active:scale-95 flex items-center justify-center">
                            <span class="material-symbols-outlined mr-2 text-[20px]">save</span>
                            <span x-text="editMode ? 'Simpan Perubahan' : 'Tambahkan Aset'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>