<x-app-layout title="Peminjaman Barang">
    <div class="max-w-[1600px] mx-auto py-2" x-data="borrowingPage()">

        {{-- Modern Gradient Header --}}
        <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 rounded-[2rem] p-6 sm:p-10 mb-8 shadow-2xl shadow-blue-200 relative overflow-hidden transition-all duration-700 hover:shadow-blue-300/50">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400/20 rounded-full -ml-10 -mb-10 blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/20">Equipment Flow</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-3 leading-none">Peminjaman Barang</h1>
                    <p class="text-blue-100 font-medium text-base opacity-90 max-w-xl leading-relaxed">Kelola dan pantau pendataan peminjaman aset di setiap laboratorium secara akurat.</p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                        <button @click="openCreateModal()"
                            class="bg-white text-blue-600 px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] flex items-center gap-3 shadow-2xl shadow-blue-900/10 hover:bg-slate-900 hover:text-white transition-all active:scale-95 group/btn">
                            <span class="material-symbols-outlined text-xl group-hover/btn:rotate-90 transition-transform">add</span>
                            Catat Peminjaman
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10 items-start px-2">
            {{-- Superadmin Sidebar: Filter Lab --}}
            @if(auth()->user()->role === 'superadmin')
                <aside class="w-full lg:w-96 flex-shrink-0 lg:sticky lg:top-8">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden ring-1 ring-slate-100/50">
                        <div class="px-8 py-7 border-b border-slate-50 bg-slate-50/50">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-base">filter_alt</span>
                                </div>
                                <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Filter Laboratorium</h2>
                            </div>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-[20px]">search</span>
                                <input type="text" x-model="searchTerm" placeholder="Cari prodi atau lab..." 
                                    class="w-full bg-white border-slate-100 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300 shadow-inner">
                            </div>
                        </div>
                        
                        <div class="max-h-[600px] overflow-y-auto scrollbar-none select-none p-4 space-y-2">
                            <div @click="selectedLabId = null"
                                :class="!selectedLabId ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600'"
                                class="p-4 cursor-pointer transition-all rounded-[1.5rem] border border-transparent flex items-center gap-4 font-black text-sm tracking-tight">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                                    :class="!selectedLabId ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-400'">
                                    <span class="material-symbols-outlined text-[20px]">list_alt</span>
                                </div>
                                Semua Peminjaman
                            </div>

                            <template x-for="prodi in filteredGroupedLabs" :key="prodi.id">
                                <div class="group">
                                    <div @click="expandedProdiId = (expandedProdiId === prodi.id ? null : prodi.id)"
                                        class="p-4 flex items-center justify-between cursor-pointer rounded-2xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 group/prodi">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 group-hover/prodi:bg-blue-100 group-hover/prodi:text-blue-600 flex items-center justify-center transition-colors shadow-sm">
                                                <span class="material-symbols-outlined text-[20px]">school</span>
                                            </div>
                                            <div class="font-black text-sm text-slate-700 tracking-tight" x-text="prodi.name"></div>
                                        </div>
                                        <span class="material-symbols-outlined text-slate-300 transition-transform duration-300"
                                            :class="expandedProdiId === prodi.id ? 'rotate-180' : ''">expand_more</span>
                                    </div>

                                    <div x-show="expandedProdiId === prodi.id || searchTerm.length > 0" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 -translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="mt-1 space-y-1">
                                        <template x-for="lab in prodi.labs" :key="lab.id">
                                            <div @click="selectedLabId = lab.id"
                                                :class="selectedLabId === lab.id ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-white hover:shadow-md hover:text-blue-600'"
                                                class="ml-12 mr-2 p-3.5 rounded-2xl cursor-pointer transition-all flex items-center gap-3 group/lab border border-transparent hover:border-slate-100">
                                                <span class="material-symbols-outlined text-[18px] opacity-40">door_front</span>
                                                <div class="text-[13px] font-black tracking-tight truncate" x-text="lab.name"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </aside>
            @endif

            <div class="flex-1">

            <div class="flex-1 min-w-0">
                {{-- Table Card --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden ring-1 ring-slate-100/50">
                    <div class="overflow-x-auto scrollbar-none">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Peminjam</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Informasi Aset</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Waktu Pinjam</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($borrowings as $b)
                                    <tr class="hover:bg-blue-50/30 transition-all duration-300 group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-white shadow-inner flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-500">
                                                    <span class="material-symbols-outlined text-2xl">person</span>
                                                </div>
                                                <div>
                                                    <p class="font-black text-slate-900 tracking-tight leading-none mb-1.5">{{ $b->nama_peminjam }}</p>
                                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $b->nim }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div>
                                                <p class="font-black text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors mb-1.5">{{ $b->asset->nama ?? 'Aset dihapus' }}</p>
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-base text-blue-500 opacity-60">door_front</span>
                                                    <span class="text-[11px] font-bold text-slate-500 truncate max-w-[150px]">{{ $b->lab->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="space-y-1.5">
                                                <div class="flex items-center gap-2 text-slate-600">
                                                    <span class="material-symbols-outlined text-base text-emerald-500">calendar_today</span>
                                                    <span class="text-[13px] font-black tracking-tight">{{ $b->borrow_date->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-slate-400">
                                                    <span class="material-symbols-outlined text-base">history</span>
                                                    <span class="text-[11px] font-bold italic tracking-tight">Kembali: {{ $b->return_date->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            @php
                                                $colors = [
                                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'approved' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                    'returned' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                ];
                                                $labels = [
                                                    'pending' => 'Menunggu',
                                                    'approved' => 'Disetujui',
                                                    'rejected' => 'Ditolak',
                                                    'returned' => 'Kembali',
                                                ];
                                            @endphp
                                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border shadow-sm {{ $colors[$b->status] ?? 'bg-slate-50 text-slate-600 border-slate-100' }}">
                                                {{ $labels[$b->status] ?? $b->status }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                                                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                                    <button @click="openEditModal({{ $b }})"
                                                        class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-[20px]">edit_note</span>
                                                    </button>
                                                    <form action="{{ route('borrowings.destroy', $b->id) }}" method="POST"
                                                        onsubmit="return confirm('Hapus data peminjaman ini?')">
                                                        @csrf @method('DELETE')
                                                        <button class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all flex items-center justify-center">
                                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-24 text-center">
                                            <div class="flex flex-col items-center group">
                                                <div class="w-24 h-24 rounded-[2rem] bg-slate-50 flex items-center justify-center mb-6 text-slate-200 group-hover:scale-110 transition-transform duration-700">
                                                    <span class="material-symbols-outlined text-5xl opacity-30 group-hover:text-blue-500 group-hover:opacity-100 transition-all duration-700">inventory</span>
                                                </div>
                                                <h3 class="text-slate-900 font-black text-xl tracking-tighter mb-2">Tidak Ada Data Peminjaman</h3>
                                                <p class="text-slate-400 font-medium text-sm max-w-[320px] mx-auto tracking-tight leading-relaxed italic">Catatan peminjaman barang belum tersedia dalam sistem saat ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- Modal CREATE/EDIT --}}
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Backdrop --}}
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="closeModal()"
                    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="openModal" x-transition:enter="ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white">

                    <form :action="editMode ? `/borrowings/${form.id}` : '{{ route('borrowings.store') }}'" method="POST" class="p-8 sm:p-10">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="mb-10 flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-black text-slate-900 tracking-tighter"
                                    x-text="editMode ? 'Edit Peminjaman' : 'Catat Peminjaman'"></h2>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Lengkapi data formulir berikut.</p>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                                <span class="material-symbols-outlined text-3xl" x-text="editMode ? 'edit_document' : 'app_registration'"></span>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Nama Peminjam</label>
                                    <div class="relative group">
                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">person</span>
                                        <input type="text" name="nama_peminjam" x-model="form.nama_peminjam" required
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">NIM</label>
                                    <div class="relative group">
                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">id_card</span>
                                        <input type="text" name="nim" x-model="form.nim" required
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Pilih Lab</label>
                                    <div class="relative group">
                                        <select name="lab_id" x-model="form.lab_id" @change="updateAssets()" required
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all appearance-none cursor-pointer text-sm">
                                            <option value="">-- Lab --</option>
                                            <template x-for="l in allLabs" :key="l.id">
                                                <option :value="String(l.id)" x-text="l.name"></option>
                                            </template>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Pilih Aset</label>
                                    <div class="relative group">
                                        <select name="asset_id" x-model="form.asset_id" required
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all appearance-none cursor-pointer text-sm">
                                            <option value="">-- Aset --</option>
                                            <template x-for="asseta in availableAssets" :key="asseta.id">
                                                <option :value="asseta.id" x-text="asseta.nama"></option>
                                            </template>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Mulai Pinjam</label>
                                    <input type="date" name="borrow_date" x-model="form.borrow_date" required
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Rencana Kembali</label>
                                    <input type="date" name="return_date" x-model="form.return_date" required
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm">
                                </div>
                            </div>

                            <template x-if="editMode">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Update Status</label>
                                    <div class="relative group">
                                        <select name="status" x-model="form.status" required
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all appearance-none cursor-pointer text-sm">
                                            <option value="pending">Menunggu</option>
                                            <option value="approved">Disetujui</option>
                                            <option value="rejected">Ditolak</option>
                                            <option value="returned">Sudah Kembali</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">sync_alt</span>
                                    </div>
                                </div>
                            </template>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Catatan Tambahan</label>
                                <textarea name="notes" x-model="form.notes" rows="3" placeholder="Contoh: Kondisi barang saat dipinjam..."
                                    class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-12 flex gap-4">
                            <button type="button" @click="closeModal()"
                                class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-[2] px-6 py-4 rounded-2xl bg-blue-600 text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg" x-text="editMode ? 'save' : 'add_circle'"></span>
                                <span x-text="editMode ? 'Simpan Perubahan' : 'Catat Peminjaman'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function borrowingPage() {
                return {
                    openModal: false,
                    editMode: false,
                    searchTerm: '',
                    selectedLabId: null,
                    expandedProdiId: null,
                    form: {
                        id: '',
                        nama_peminjam: '',
                        nim: '',
                        lab_id: '',
                        asset_id: '',
                        borrow_date: '',
                        return_date: '',
                        status: 'pending',
                        notes: ''
                    },
                    borrowings: @json($borrowings),
                    prodis: @json($prodis),
                    labs: @json($labs),
                    availableAssets: [],

                    init() {
                        this.$watch('form.lab_id', (val) => this.updateAssets());
                    },

                    get allLabs() {
                        if (this.prodis && this.prodis.length > 0) {
                            return this.prodis.flatMap(p => p.labs);
                        }
                        return this.labs;
                    },

                    get filteredBorrowings() {
                        if (!this.selectedLabId) return this.borrowings;
                        return this.borrowings.filter(b => b.lab_id === this.selectedLabId);
                    },

                    get filteredGroupedLabs() {
                        if (!this.prodis) return [];
                        const term = this.searchTerm.toLowerCase();
                        
                        return this.prodis.map(prodi => {
                            const matchingLabs = prodi.labs.filter(lab => 
                                lab.name.toLowerCase().includes(term) || 
                                prodi.name.toLowerCase().includes(term)
                            );
                            if (matchingLabs.length > 0 || prodi.name.toLowerCase().includes(term)) {
                                return { ...prodi, labs: matchingLabs };
                            }
                            return null;
                        }).filter(p => p !== null);
                    },

                    updateAssets() {
                        const selectedLab = this.allLabs.find(l => String(l.id) === String(this.form.lab_id));
                        this.availableAssets = selectedLab ? selectedLab.assets : [];
                    },

                    formatDate(dateStr) {
                        if (!dateStr) return '-';
                        const options = { day: '2-digit', month: 'short', year: 'numeric' };
                        return new Date(dateStr).toLocaleDateString('id-ID', options);
                    },

                    getStatusColor(status) {
                        const colors = {
                            'pending': 'bg-amber-100 text-amber-700',
                            'approved': 'bg-blue-100 text-blue-700',
                            'rejected': 'bg-red-100 text-red-700',
                            'returned': 'bg-green-100 text-green-700',
                        };
                        return colors[status] || 'bg-gray-100 text-gray-700';
                    },

                    getStatusLabel(status) {
                        const labels = {
                            'pending': 'Menunggu',
                            'approved': 'Disetujui',
                            'rejected': 'Ditolak',
                            'returned': 'Kembali',
                        };
                        return labels[status] || status;
                    },

                    openCreateModal() {
                        this.editMode = false;
                        this.form = {
                            id: '',
                            nama_peminjam: '',
                            nim: '',
                            lab_id: '',
                            asset_id: '',
                            borrow_date: new Date().toISOString().split('T')[0],
                            return_date: '',
                            status: 'pending',
                            notes: ''
                        };
                        this.openModal = true;
                    },

                    openEditModal(borrowing) {
                        this.editMode = true;
                        
                        // 1. Populate assets first using computed allLabs
                        const selectedLab = this.allLabs.find(l => String(l.id) === String(borrowing.lab_id));
                        this.availableAssets = selectedLab ? selectedLab.assets : [];

                        // 2. Format dates
                        const bDate = borrowing.borrow_date ? borrowing.borrow_date.split('T')[0] : '';
                        const rDate = borrowing.return_date ? borrowing.return_date.split('T')[0] : '';

                        // 3. Update form
                        this.$nextTick(() => {
                            this.form = {
                                ...borrowing,
                                lab_id: String(borrowing.lab_id),
                                asset_id: borrowing.asset_id ? String(borrowing.asset_id) : '',
                                borrow_date: bDate,
                                return_date: rDate
                            };

                        });
                        
                        this.openModal = true;
                    },

                    closeModal() {
                        this.openModal = false;
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>