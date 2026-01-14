<x-app-layout title="Peminjaman Barang">
    <div class="max-w-[1600px] mx-auto py-2" x-data="borrowingPage()">

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
                            class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/20">Equipment
                            Flow</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-3 leading-none">Peminjaman
                        Barang</h1>
                    <p class="text-blue-100 font-medium text-base opacity-90 max-w-xl leading-relaxed">Kelola dan pantau
                        pendataan peminjaman aset di setiap laboratorium secara akurat.</p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex bg-white/10 backdrop-blur-md p-1 rounded-2xl border border-white/20">
                        <a href="{{ route('borrowings.index', ['status' => 'all']) }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status', 'all') == 'all' ? 'bg-white text-blue-600' : 'text-white hover:bg-white/10' }}">
                            Semua
                        </a>
                        <a href="{{ route('borrowings.index', ['status' => 'pending']) }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == 'pending' ? 'bg-white text-blue-600' : 'text-white hover:bg-white/10' }}">
                            Menunggu
                        </a>
                        <a href="{{ route('borrowings.index', ['status' => 'returned']) }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == 'returned' ? 'bg-white text-blue-600' : 'text-white hover:bg-white/10' }}">
                            Kembali
                        </a>
                    </div>

                    @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                        <button @click="openCreateModal()"
                            class="bg-white text-blue-600 px-8 py-4 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] flex items-center gap-3 shadow-2xl shadow-blue-900/10 hover:bg-slate-900 hover:text-white transition-all active:scale-95 group/btn">
                            <span
                                class="material-symbols-outlined text-xl group-hover/btn:rotate-90 transition-transform">add</span>
                            Catat Peminjaman
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10 items-start px-2">
            {{-- SIDEBAR: FILTER & CALENDAR --}}
            <aside class="w-full lg:w-96 flex-shrink-0 lg:sticky lg:top-8">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden ring-1 ring-slate-100/50">
                    
                    {{-- CALENDAR SIDEBAR --}}
                    <div class="px-8 py-7 bg-slate-50/30 border-b border-slate-50">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-base">calendar_month</span>
                            </div>
                            <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Jadwal Pengembalian</h2>
                        </div>
                        <div class="rounded-2xl bg-white border border-gray-100 shadow-sm p-2
                            [&_.flatpickr-calendar]:w-full
                            [&_.flatpickr-calendar]:border-0
                            [&_.flatpickr-calendar]:shadow-none

                            /* HEADER ROW */
                            [&_.flatpickr-months]:flex
                            [&_.flatpickr-months]:items-center
                            [&_.flatpickr-months]:justify-between
                            [&_.flatpickr-months]:mb-3

                            [&_.flatpickr-prev-month]:static
                            [&_.flatpickr-next-month]:static
                            [&_.flatpickr-prev-month]:p-1
                            [&_.flatpickr-next-month]:p-1

                            /* BULAN + TAHUN */
                            [&_.flatpickr-current-month]:flex
                            [&_.flatpickr-current-month]:items-center
                            [&_.flatpickr-current-month]:gap-1
                            [&_.flatpickr-current-month]:flex-1
                            [&_.flatpickr-current-month]:justify-center

                            [&_.cur-month]:text-base
                            [&_.cur-month]:font-semibold
                            [&_.cur-month]:leading-none
                            [&_.cur-month]:text-gray-800

                            [&_.cur-year]:text-base
                            [&_.cur-year]:font-semibold
                            [&_.cur-year]:leading-none
                            [&_.cur-year]:text-gray-800
                            [&_.cur-year]:bg-transparent
                            [&_.cur-year]:border-0
                            [&_.cur-year]:pointer-events-none

                            /* DOT MARKER */
                            [&_.return-dot]:absolute
                            [&_.return-dot]:bottom-1
                            [&_.return-dot]:left-1/2
                            [&_.return-dot]:-translate-x-1/2
                            [&_.return-dot]:w-1
                            [&_.return-dot]:h-1
                            [&_.return-dot]:rounded-full
                            [&_.return-dot]:bg-rose-500
                        ">
                            <div id="borrowing-sidebar-calendar"></div>
                        </div>
                    </div>

                    <div class="px-8 py-7 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-base">filter_alt</span>
                            </div>
                            <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Filter & Pencarian</h2>
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-[20px]">search</span>
                                <input type="text" x-model="searchTerm" placeholder="Cari prodi, lab, atau peminjam..." 
                                    class="w-full bg-white border-slate-100 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300 shadow-inner">
                            </div>
                            <div class="relative">
                                <select x-model="filterStatus" class="w-full bg-white border-slate-100 rounded-2xl pl-4 pr-10 py-3.5 text-sm font-bold focus:ring-2 focus:ring-blue-500 transition-all shadow-inner text-slate-600 appearance-none cursor-pointer">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Menunggu</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="returned">Kembali</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">filter_list</span>
                            </div>
                        </div>
                    </div>
                    
                    @if(auth()->user()->role === 'superadmin')
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
                                            <div
                                                class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 group-hover/prodi:bg-blue-100 group-hover/prodi:text-blue-600 flex items-center justify-center transition-colors shadow-sm">
                                                <span class="material-symbols-outlined text-[20px]">school</span>
                                            </div>
                                            <div class="font-black text-sm text-slate-700 tracking-tight"
                                                x-text="prodi.name"></div>
                                        </div>
                                        <span
                                            class="material-symbols-outlined text-slate-300 transition-transform duration-300"
                                            :class="expandedProdiId === prodi.id ? 'rotate-180' : ''">expand_more</span>
                                    </div>

                                    <div x-show="expandedProdiId === prodi.id || searchTerm.length > 0"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 -translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1">
                                        <template x-for="lab in prodi.labs" :key="lab.id">
                                            <div @click="selectedLabId = lab.id"
                                                :class="selectedLabId === lab.id ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-white hover:shadow-md hover:text-blue-600'"
                                                class="ml-12 mr-2 p-3.5 rounded-2xl cursor-pointer transition-all flex items-center gap-3 group/lab border border-transparent hover:border-slate-100">
                                                <span
                                                    class="material-symbols-outlined text-[18px] opacity-40">door_front</span>
                                                <div class="text-[13px] font-black tracking-tight truncate"
                                                    x-text="lab.name"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endif
                </div>
            </aside>

            <div class="flex-1 min-w-0">
                {{-- Table Card --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden ring-1 ring-slate-100/50">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Status</label>
                            <select x-model="selectedStatus" class="rounded-2xl px-4 py-2 border bg-white text-sm font-bold">
                                <option value="all">Semua Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                                <option value="returned">Kembali</option>
                            </select>
                        </div>
                        <div class="text-sm text-slate-400 font-medium">
                            <span x-text="filteredBorrowings.length + ' entri'"></span>
                        </div>
                    </div>
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
                                <template x-for="b in filteredBorrowings" :key="b.id">
                                    <tr class="hover:bg-blue-50/30 transition-all duration-300 group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-white shadow-inner flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-500">
                                                    <span class="material-symbols-outlined text-2xl">person</span>
                                                </div>
                                                <div>
                                                    <p class="font-black text-slate-900 tracking-tight leading-none mb-1.5" x-text="b.nama_peminjam"></p>
                                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="b.nim"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div>
                                                <p class="font-black text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors mb-1.5" x-text="b.asset?.nama ?? 'Aset dihapus'"></p>
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-base text-blue-500 opacity-60">door_front</span>
                                                    <span class="text-[11px] font-bold text-slate-500 truncate max-w-[150px]" x-text="b.lab?.name"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="space-y-1.5">
                                                <div class="flex items-center gap-2 text-slate-600">
                                                    <span class="material-symbols-outlined text-base text-emerald-500">calendar_today</span>
                                                    <span class="text-[13px] font-black tracking-tight" x-text="formatDate(b.borrow_date)"></span>
                                                </div>
                                                <div class="flex items-center gap-2 text-slate-400">
                                                    <span class="material-symbols-outlined text-base">history</span>
                                                    <span class="text-[11px] font-bold italic tracking-tight" x-text="'Kembali: ' + formatDate(b.return_date)"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border shadow-sm"
                                                :class="getStatusColor(b.status)"
                                                x-text="getStatusLabel(b.status)">
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                                                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                                    <button @click="openEditModal(b)"
                                                        class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-[20px]">edit_note</span>
                                                    </button>
                                                    <form action="{{ route('borrowings.destroy', 0) }}" method="POST"
                                                        onsubmit="return confirm('Hapus data peminjaman ini?')"
                                                        :action="'/borrowings/' + b.id">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all flex items-center justify-center">
                                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filteredBorrowings.length === 0" x-cloak>
                                    <td colspan="5" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center group">
                                            <div class="w-24 h-24 rounded-[2rem] bg-slate-50 flex items-center justify-center mb-6 text-slate-200 group-hover:scale-110 transition-transform duration-700">
                                                <span class="material-symbols-outlined text-5xl opacity-30 group-hover:text-blue-500 group-hover:opacity-100 transition-all duration-700">inventory</span>
                                            </div>
                                            <h3 class="text-slate-900 font-black text-xl tracking-tighter mb-2">Tidak Ada Data Peminjaman</h3>
                                            <p class="text-slate-400 font-medium text-sm max-w-[320px] mx-auto tracking-tight leading-relaxed italic">Sesuaikan filter atau tambahkan data baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- ================= MODAL CREATE/EDIT ================= --}}
        <div x-show="openModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4" x-cloak>

            <div @click.outside="closeModal()" 
                class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative overflow-hidden ring-1 ring-black/5 animate-in fade-in zoom-in duration-300">
                
                {{-- Decorative Header --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 blur-2xl opacity-50"></div>
                
                {{-- Modal Header --}}
                <div class="px-8 pt-8 pb-6 border-b border-slate-50 relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tighter" x-text="editMode ? 'Edit Peminjaman' : 'Catat Peminjaman Baru'"></h2>
                            <p class="text-slate-400 text-sm font-medium tracking-tight mt-1">Lengkapi data berikut untuk pendataan aset.</p>
                        </div>
                        <button @click="closeModal()" class="w-11 h-11 rounded-2xl hover:bg-slate-50 text-slate-300 hover:text-rose-500 transition-all flex items-center justify-center group">
                            <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">close</span>
                        </button>
                    </div>
                </div>

                {{-- Form Content --}}
                <form :action="editMode ? `/borrowings/${form.id}` : '{{ route('borrowings.store') }}'" method="POST" class="px-8 py-8">
                    @csrf
                    <template x-if="editMode">
                        @method('PUT')
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        {{-- Peminjam --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Peminjam</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-xl">person</span>
                                <input type="text" name="nama_peminjam" x-model="form.nama_peminjam" required placeholder="Nama Lengkap"
                                    class="w-full pl-12 pr-4 py-3.5 bg-slate-50/50 border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-slate-700 shadow-inner">
                            </div>
                        </div>

                        {{-- NIM --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">NIM / ID</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-xl">badge</span>
                                <input type="text" name="nim" x-model="form.nim" required placeholder="Nomor Induk"
                                    class="w-full pl-12 pr-4 py-3.5 bg-slate-50/50 border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-slate-700 shadow-inner">
                            </div>
                        </div>

                        {{-- Laboratorium --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Laboratorium</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-xl">door_front</span>
                                <select name="lab_id" x-model="form.lab_id" required
                                    class="w-full pl-12 pr-10 py-3.5 bg-slate-50/50 border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-slate-700 appearance-none shadow-inner cursor-pointer">
                                    <option value="">Pilih Lab</option>
                                    <template x-for="lab in allLabs" :key="lab.id">
                                        <option :value="lab.id" x-text="lab.name" :selected="String(lab.id) === String(form.lab_id)"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- Aset --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Aset / Barang</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-xl">inventory_2</span>
                                <select name="asset_id" x-model="form.asset_id" required
                                    class="w-full pl-12 pr-10 py-3.5 bg-slate-50/50 border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-slate-700 appearance-none shadow-inner cursor-pointer">
                                    <option value="">Pilih Aset</option>
                                    <template x-for="asset in availableAssets" :key="asset.id">
                                        <option :value="asset.id" x-text="asset.nama" :selected="String(asset.id) === String(form.asset_id)"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- Tanggal Pinjam --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Pinjam</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-xl">calendar_today</span>
                                <input type="date" name="borrow_date" x-model="form.borrow_date" required
                                    class="w-full pl-12 pr-4 py-3.5 bg-slate-50/50 border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-slate-700 shadow-inner">
                            </div>
                        </div>

                        {{-- Tanggal Kembali --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Estimasi Kembali</label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-xl">history</span>
                                <input type="date" name="return_date" x-model="form.return_date" required
                                    class="w-full pl-12 pr-4 py-3.5 bg-slate-50/50 border-slate-100 rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-slate-700 shadow-inner">
                            </div>
                        </div>

                        {{-- Status (Only for Edit Mode) --}}
                        <div class="md:col-span-2 space-y-3" x-show="editMode" x-transition>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Status Peminjaman</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(label, val) in {'pending': 'Menunggu', 'approved': 'Disetujui', 'rejected': 'Ditolak', 'returned': 'Kembali'}" :key="val">
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="status" :value="val" x-model="form.status" class="peer sr-only">
                                        <div class="py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center border transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white border-slate-100 text-slate-400 hover:bg-slate-50 group-active:scale-95 shadow-sm">
                                            <span x-text="label"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Catatan Keperluan</label>
                            <textarea name="notes" x-model="form.notes" rows="3" placeholder="Opsional: Tuliskan informasi tambahan..."
                                class="w-full px-6 py-4 bg-slate-50/50 border-slate-100 rounded-3xl focus:bg-white focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all font-bold text-slate-700 shadow-inner resize-none"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-10 pt-8 border-t border-slate-50">
                        <button type="button" @click="closeModal()"
                            class="px-8 py-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-slate-600 transition-colors">Batal</button>
                        
                        <button type="submit"
                            class="px-10 py-4.5 bg-blue-600 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-200 hover:bg-slate-900 transition-all active:scale-95 flex items-center gap-3">
                            <span class="material-symbols-outlined text-xl" x-text="editMode ? 'save' : 'done_all'"></span>
                            <span x-text="editMode ? 'Simpan Perubahan' : 'Selesaikan Pendataan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const returnDates = @json($returnDates);

                flatpickr("#borrowing-sidebar-calendar", {
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

                        const dateStr = fp.formatDate(date, "Y-m-d");
                        if (returnDates.includes(dateStr)) {
                            const dot = document.createElement("span");
                            dot.className = "return-dot";
                            dayElem.appendChild(dot);
                        }
                    }
                });
            });

            function borrowingPage() {
                return {
                    openModal: false,
                    editMode: false,
                    searchTerm: '',
                    filterStatus: '',
                    selectedLabId: null,
                    selectedStatus: 'all',
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
                        let filtered = this.borrowings;

                        if (this.selectedLabId) {
                            filtered = filtered.filter(b => b.lab_id === this.selectedLabId);
                        }

                        if (this.selectedStatus !== 'all') {
                            filtered = filtered.filter(b => b.status === this.selectedStatus);
                        }

                        if (this.filterStatus) {
                            filtered = filtered.filter(b => b.status === this.filterStatus);
                        }

                        if (this.searchTerm) {
                            const term = this.searchTerm.toLowerCase();
                            filtered = filtered.filter(b => 
                                b.nama_peminjam.toLowerCase().includes(term) ||
                                b.nim.toLowerCase().includes(term) ||
                                b.asset?.nama?.toLowerCase().includes(term) ||
                                b.lab?.name?.toLowerCase().includes(term)
                            );
                        }

                        return filtered;
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

                        // Populate assets first
                        const selectedLab = this.allLabs.find(l => String(l.id) === String(borrowing.lab_id));
                        this.availableAssets = selectedLab ? selectedLab.assets : [];

                        // Format dates
                        const bDate = borrowing.borrow_date ? borrowing.borrow_date.split('T')[0] : '';
                        const rDate = borrowing.return_date ? borrowing.return_date.split('T')[0] : '';

                        this.form = {
                            ...borrowing,
                            lab_id: String(borrowing.lab_id),
                            asset_id: borrowing.asset_id ? String(borrowing.asset_id) : '',
                            borrow_date: bDate,
                            return_date: rDate
                        };

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