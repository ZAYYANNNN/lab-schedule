<x-app-layout title="Peminjaman Barang">
    {{-- CSS Fixes for Animations & Cloak --}}
    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }

            @keyframes fade-in-down {
                0% {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-down {
                animation: fade-in-down 0.5s ease-out forwards;
            }
        </style>
    @endpush

    <div class="max-w-[1600px] mx-auto py-6 px-4 sm:px-6 lg:px-8" x-data="borrowingPage()">

        {{-- Modern Gradient Header --}}
        <div
            class="bg-gradient-to-br from-blue-700 via-indigo-600 to-blue-700 rounded-[2rem] p-6 sm:p-10 mb-8 shadow-2xl shadow-blue-200 relative overflow-hidden transition-all duration-700 hover:shadow-blue-300/50">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400/20 rounded-full -ml-10 -mb-10 blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/20">Aset
                            Management</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-3 leading-none">Peminjaman
                        Barang</h1>
                    <p class="text-blue-100 font-medium text-base opacity-90 max-w-xl leading-relaxed">Kelola dan pantau
                        pendataan peminjaman aset di setiap laboratorium secara akurat.</p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
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

        {{-- Main Grid Container --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- SECTION 1: CALENDAR --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-base">calendar_month</span>
                        </div>
                        <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Kalender</h2>
                    </div>
                    {{-- Calendar Container --}}
                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-2
                        [&_.flatpickr-calendar]:w-full [&_.flatpickr-calendar]:border-0 [&_.flatpickr-calendar]:shadow-none
                        [&_.flatpickr-months]:flex [&_.flatpickr-months]:items-center [&_.flatpickr-months]:justify-between [&_.flatpickr-months]:mb-2
                        [&_.flatpickr-current-month]:flex [&_.flatpickr-current-month]:items-center [&_.flatpickr-current-month]:justify-center
                        [&_.cur-month]:text-sm [&_.cur-month]:font-bold [&_.cur-year]:text-sm [&_.cur-year]:font-bold
                        [&_.return-dot]:absolute [&_.return-dot]:bottom-1 [&_.return-dot]:left-1/2 [&_.return-dot]:-translate-x-1/2 [&_.return-dot]:w-1 [&_.return-dot]:h-1 [&_.return-dot]:rounded-full [&_.return-dot]:bg-rose-500
                    ">
                        <div id="borrowing-sidebar-calendar"></div>
                    </div>
                    {{-- Clear Date Filter --}}
                    <div x-show="filterDate" x-transition class="mt-4 flex justify-center" x-cloak>
                        <button @click="clearDateFilter()"
                            class="text-xs font-bold text-rose-500 hover:text-rose-600 flex items-center gap-1 bg-rose-50 px-3 py-1.5 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                            Reset Tanggal (<span x-text="formatDate(filterDate)"></span>)
                        </button>
                    </div>
                </div>

                {{-- SECTION 2: JATUH TEMPO & KETERLAMBATAN --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-5 flex flex-col h-[300px]">
                    <div class="flex items-center gap-3 mb-4 flex-shrink-0">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-base">warning</span>
                        </div>
                        <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Jatuh Tempo &
                            Keterlambatan</h2>
                    </div>

                    <div class="space-y-3 overflow-y-auto scrollbar-thin flex-1 pr-1">
                        {{-- Loop Attention Items --}}
                        @forelse($attentionItems as $item)
                                            <div @click="filterDate = '{{ $item->return_date->format('Y-m-d') }}'; searchTerm = '{{ $item->nama_peminjam }}'"
                                                class="flex items-start gap-3 p-3 rounded-2xl border cursor-pointer transition-colors group
                                                                                                {{ $item->status === 'returned' && $item->getLateDuration() ? 'bg-orange-50 border-orange-100 hover:bg-orange-100' :
                            ($item->isOverdue() ? 'bg-rose-50 border-rose-100 hover:bg-rose-100' : 'bg-amber-50 border-amber-100 hover:bg-amber-100') }}">

                                                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 group-hover:scale-125 transition-transform
                                                                                                    {{ $item->status === 'returned' && $item->getLateDuration() ? 'bg-orange-500' :
                            ($item->isOverdue() ? 'bg-rose-500' : 'bg-amber-500') }}">
                                                </div>

                                                <div class="w-full">
                                                    <div class="flex justify-between items-start">
                                                        <p class="text-[10px] font-black uppercase tracking-wider mb-0.5
                                                                                                            {{ $item->status === 'returned' && $item->getLateDuration() ? 'text-orange-700' :
                            ($item->isOverdue() ? 'text-rose-700' : 'text-amber-700') }}">
                                                            @if($item->status === 'returned')
                                                                Telat {{ $item->getLateDuration() }}
                                                            @elseif($item->isOverdue())
                                                                Terlambat
                                                            @else
                                                                Hari Ini
                                                            @endif
                                                        </p>
                                                        <span class="text-[9px] font-bold opacity-70">
                                                            {{ \Carbon\Carbon::parse($item->return_time)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs font-bold text-slate-700 line-clamp-1 group-hover:text-slate-900">
                                                        {{ $item->nama_peminjam }}
                                                    </p>
                                                    <p class="text-[10px] text-slate-500">{{ $item->asset->nama ?? 'Aset dihapus' }}</p>
                                                </div>
                                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-center text-slate-400">
                                <span class="material-symbols-outlined text-3xl mb-2 opacity-50">check_circle</span>
                                <p class="text-[10px] font-bold">Tidak ada peringatan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- SECTION 3: LAB LIST --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-5 flex flex-col h-[300px]">
                    <div class="flex items-center gap-3 mb-4 flex-shrink-0">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-base">domain</span>
                        </div>
                        <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Daftar Lab</h2>
                    </div>

                    <div class="overflow-y-auto scrollbar-thin space-y-2 flex-1 pr-1">
                        <div @click="selectedLabId = null"
                            :class="!selectedLabId ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600'"
                            class="p-3 cursor-pointer transition-all rounded-xl border border-transparent flex items-center gap-3 font-bold text-xs">
                            <span class="truncate">Semua Lab</span>
                        </div>

                        @foreach($allLabsFlat as $lab)
                            <div @click="selectedLabId = '{{ $lab->id }}'"
                                :class="selectedLabId === '{{ $lab->id }}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600'"
                                class="p-3 cursor-pointer transition-all rounded-xl border border-transparent flex items-center gap-3 font-bold text-xs group">
                                <span class="truncate">{{ $lab->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- SECTION 4: FILTERS --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                    <div class="flex flex-col md:flex-row md:items-center gap-4 justify-between">
                        <div class="flex-1">
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-[20px]">search</span>
                                <input type="text" x-model="searchTerm" placeholder="Cari nama, aset, atau lab..."
                                    class="w-full bg-slate-50 border-transparent rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-300">
                            </div>
                        </div>
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <div class="relative w-full md:w-48">
                                <select x-model="filterStatus"
                                    class="w-full bg-slate-50 border-transparent rounded-2xl pl-4 pr-10 py-3.5 text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-600 appearance-none cursor-pointer">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Menunggu</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="returned">Kembali</option>
                                </select>
                                <span
                                    class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">filter_list</span>
                            </div>
                        </div>
                    </div>
                    {{-- SECTION 5: MAIN TABLE --}}
                    <div
                        class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden ring-1 ring-slate-100/50">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="font-black text-slate-800 uppercase text-xs tracking-[0.2em]">Data Peminjaman
                            </h2>
                            <div class="text-sm text-slate-400 font-medium">
                                <span x-text="filteredBorrowings.length + ' entri'"></span>
                            </div>
                        </div>
                        <div class="overflow-x-auto scrollbar-none">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100">
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Peminjam</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Aset</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Waktu</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <template x-for="b in filteredBorrowings" :key="b.id">
                                        <tr class="hover:bg-blue-50/30 transition-all duration-300 group animate-fade-in-down"
                                            :class="{'bg-rose-50/30': isOverdue(b)}">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-xl">person</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-black text-slate-900 tracking-tight leading-none mb-1"
                                                            x-text="b.nama_peminjam"></p>
                                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"
                                                            x-text="b.nim"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <p class="font-black text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors mb-1"
                                                        x-text="b.asset?.nama ?? 'Aset dihapus'"></p>
                                                    <div class="flex items-center gap-1.5">
                                                        <span
                                                            class="material-symbols-outlined text-sm text-blue-400">door_front</span>
                                                        <span
                                                            class="text-[10px] font-bold text-slate-500 truncate max-w-[120px]"
                                                            x-text="b.lab?.name"></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-1.5 text-slate-600">
                                                        <span class="text-[11px] font-black tracking-tight"
                                                            x-text="formatDate(b.borrow_date) + ' ' + formatTime(b.borrow_time)"></span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5"
                                                        :class="isOverdue(b) ? 'text-rose-500' : 'text-slate-400'">
                                                        <span class="text-[10px] font-bold italic tracking-tight"
                                                            x-text="'Sampai ' + formatDate(b.return_date) + ' ' + formatTime(b.return_time)"></span>
                                                        <template x-if="isOverdue(b)">
                                                            <span
                                                                class="material-symbols-outlined text-[10px] animate-pulse">warning</span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border shadow-sm"
                                                    :class="getStatusColor(b)" x-text="getStatusLabel(b.status)">
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div
                                                    class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                                    @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                                        <button @click="openEditModal(b)"
                                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center">
                                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                                        </button>
                                                        <form action="{{ route('borrowings.destroy', 0) }}" method="POST"
                                                            onsubmit="return confirm('Hapus data peminjaman ini?')"
                                                            :action="'/borrowings/' + b.id">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center">
                                                                <span
                                                                    class="material-symbols-outlined text-[16px]">delete</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="filteredBorrowings.length === 0" x-cloak>
                                        <td colspan="5" class="px-8 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <span
                                                    class="material-symbols-outlined text-4xl text-slate-200 mb-3">inbox</span>
                                                <h3 class="text-slate-900 font-bold text-sm">Tidak Ada Data</h3>
                                                <p class="text-slate-400 text-xs mt-1">Sesuaikan filter pencarian.</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- SECTION 6: OVERDUE / LATE TABLE --}}
                    <div
                        class="bg-white rounded-[2.5rem] shadow-xl shadow-rose-100/50 border border-slate-100 overflow-hidden ring-1 ring-rose-100/50 mt-8">
                        <div
                            class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-rose-50/30">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-rose-500">warning</span>
                                <h2 class="font-black text-rose-800 uppercase text-xs tracking-[0.2em]">Keterlambatan
                                    Pengembalian</h2>
                            </div>
                            <div class="text-sm text-rose-400 font-medium">
                                <span x-text="filteredLateBorrowings.length + ' entri'"></span>
                            </div>
                        </div>
                        <div class="overflow-x-auto scrollbar-none">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100">
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Peminjam</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Aset</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Waktu Seharusnya</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            Keterlambatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <template x-for="b in filteredLateBorrowings" :key="'late-'+b.id">
                                        <tr class="hover:bg-rose-50/30 transition-all duration-300">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-base">person</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 text-xs mb-0.5"
                                                            x-text="b.nama_peminjam">
                                                        </p>
                                                        <p class="text-[10px] font-bold text-slate-400" x-text="b.nim">
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="font-bold text-slate-700 text-xs"
                                                    x-text="b.asset?.nama"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-xs font-bold text-slate-600"
                                                    x-text="formatDate(b.return_date) + ' ' + formatTime(b.return_time)"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-wider"
                                                    :class="b.status === 'returned' ? 'bg-orange-100 text-orange-700' : 'bg-rose-100 text-rose-700'">
                                                    <span
                                                        x-text="b.status === 'returned' ? 'Dikembalikan Terlambat' : 'Belum Kembali'"></span>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-xs font-black text-rose-600"
                                                    x-text="getLateDuration(b)"></span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="filteredLateBorrowings.length === 0" x-cloak>
                                        <td colspan="5" class="px-8 py-8 text-center">
                                            <p class="text-slate-400 text-xs font-bold">Tidak ada data keterlambatan.
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ================= MODAL ================= --}}
            <div x-show="openModal" x-transition:enter="ease-out duration-300"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4" x-cloak>
                <div @click.outside="closeModal()"
                    class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative overflow-hidden ring-1 ring-black/5 animate-in fade-in zoom-in duration-300">
                    <div class="px-8 pt-8 pb-6 border-b border-slate-50 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tighter"
                                x-text="editMode ? 'Edit Peminjaman' : 'Catat Peminjaman'"></h2>
                        </div>
                        <button @click="closeModal()"
                            class="w-10 h-10 rounded-xl hover:bg-slate-50 text-slate-300 hover:text-rose-500 transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <form :action="editMode ? `/borrowings/${form.id}` : '{{ route('borrowings.store') }}'"
                        method="POST" class="px-8 py-8">
                        @csrf
                        <template x-if="editMode">
                            @method('PUT')
                        </template>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Peminjam</label>
                                <input type="text" name="nama_peminjam" x-model="form.nama_peminjam" required
                                    class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">NIM</label>
                                <input type="text" name="nim" x-model="form.nim" required
                                    class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Lab</label>
                                <select name="lab_id" x-model="form.lab_id" required
                                    class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all appearance-none">
                                    <option value="">Pilih Lab</option>
                                    <template x-for="lab in allLabs" :key="lab.id">
                                        <option :value="lab.id" x-text="lab.name"
                                            :selected="String(lab.id) === String(form.lab_id)"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Aset</label>
                                <select name="asset_id" x-model="form.asset_id" required
                                    class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all appearance-none">
                                    <option value="">Pilih Aset</option>
                                    <template x-for="asset in availableAssets" :key="asset.id">
                                        <option :value="asset.id" x-text="asset.nama"
                                            :selected="String(asset.id) === String(form.asset_id)"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Date & Time - Borrow --}}
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Tgl &
                                    Jam
                                    Pinjam</label>
                                <div class="flex gap-2">
                                    <input type="date" name="borrow_date" x-model="form.borrow_date" required
                                        class="w-2/3 px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                                    <input type="time" name="borrow_time" x-model="form.borrow_time" required
                                        class="w-1/3 px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                                </div>
                            </div>

                            {{-- Date & Time - Return --}}
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Tgl &
                                    Jam
                                    Kembali</label>
                                <div class="flex gap-2">
                                    <input type="date" name="return_date" x-model="form.return_date" required
                                        class="w-2/3 px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                                    <input type="time" name="return_time" x-model="form.return_time" required
                                        class="w-1/3 px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all">
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-2" x-show="editMode">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Status</label>
                                <div class="flex gap-2">
                                    <template
                                        x-for="(label, val) in {'pending': 'Menunggu', 'approved': 'Disetujui', 'rejected': 'Ditolak', 'returned': 'Kembali'}"
                                        :key="val">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" :value="val" x-model="form.status"
                                                class="peer sr-only">
                                            <div
                                                class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase border transition-all peer-checked:bg-blue-600 peer-checked:text-white border-slate-100 text-slate-400 mb-1 hover:bg-slate-50">
                                                <span x-text="label"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Catatan</label>
                                <textarea name="notes" x-model="form.notes" rows="2"
                                    class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl text-sm font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 transition-all resize-none"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-8">
                            <button type="button" @click="closeModal()"
                                class="px-6 py-3 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                            <button type="submit"
                                class="px-6 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold shadow-lg shadow-blue-200 hover:bg-slate-800">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
            <script>
                // Define global function directly on window BEFORE Alpine initializes
                window.borrowingPage = function () {
                    return {
                        openModal: false,
                        editMode: false,
                        searchTerm: '',
                        filterStatus: '',
                        filterDate: '',
                        selectedLabId: null,
                        form: {
                            id: '',
                            nama_peminjam: '',
                            nim: '',
                            lab_id: '',
                            asset_id: '',
                            borrow_date: '',
                            borrow_time: '08:00',
                            return_date: '',
                            return_time: '17:00',
                            status: 'pending',
                            notes: ''
                        },
                        borrowings: @json($borrowings ?? []),
                        lateReportItemsRaw: @json($lateReportItems ?? []),
                        labs: @json($allLabsFlat ?? []),
                        availableAssets: [],
                        returnDates: @json($returnDates ?? []),

                        get allLabs() {
                            return this.labs || [];
                        },

                        get filteredBorrowings() {
                            let filtered = this.borrowings || [];

                            if (this.selectedLabId) {
                                filtered = filtered.filter(b => String(b.lab_id) === String(this.selectedLabId));
                            }
                            if (this.filterStatus) {
                                filtered = filtered.filter(b => b.status === this.filterStatus);
                            }
                            if (this.filterDate) {
                                filtered = filtered.filter(b => b.return_date && b.return_date.split('T')[0] === this.filterDate);
                            }
                            if (this.searchTerm) {
                                const term = this.searchTerm.toLowerCase();
                                filtered = filtered.filter(b =>
                                    (b.nama_peminjam && b.nama_peminjam.toLowerCase().includes(term)) ||
                                    (b.nim && b.nim.toLowerCase().includes(term)) ||
                                    (b.asset && b.asset.nama && b.asset.nama.toLowerCase().includes(term)) ||
                                    (b.lab && b.lab.name && b.lab.name.toLowerCase().includes(term))
                                );
                            }
                            return filtered;
                        },

                        get filteredLateBorrowings() {
                            // Independent list for the report table
                            // Supports search, but ignores status filter (since it's a specific report)
                            let filtered = this.lateReportItemsRaw || [];

                            // We might want to respect Lab filter if it's set
                            if (this.selectedLabId) {
                                filtered = filtered.filter(b => String(b.lab_id) === String(this.selectedLabId));
                            }

                            if (this.searchTerm) {
                                const term = this.searchTerm.toLowerCase();
                                filtered = filtered.filter(b =>
                                    (b.nama_peminjam && b.nama_peminjam.toLowerCase().includes(term)) ||
                                    (b.nim && b.nim.toLowerCase().includes(term)) ||
                                    (b.asset && b.asset.nama && b.asset.nama.toLowerCase().includes(term)) ||
                                    (b.lab && b.lab.name && b.lab.name.toLowerCase().includes(term))
                                );
                            }

                            return filtered;
                        },

                        init() {
                            console.log('borrowingPage initialized');
                            this.$watch('form.lab_id', (val) => this.updateAssets());

                            // Initialize calendar after a short delay to ensure flatpickr is loaded
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    if (typeof flatpickr !== 'undefined') {
                                        this.initCalendar();
                                    }
                                }, 100);
                            });

                            setInterval(() => {
                                // in a real reactive system we might update a 'now' variable
                            }, 60000);
                        },

                        initCalendar() {
                            const calendarEl = document.getElementById("borrowing-sidebar-calendar");
                            if (!calendarEl) return;

                            if (calendarEl._flatpickr) {
                                calendarEl._flatpickr.destroy();
                            }

                            flatpickr(calendarEl, {
                                inline: true,
                                locale: {
                                    firstDayOfWeek: 1,
                                    weekdays: { shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"], longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"] },
                                    months: { shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"], longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"] }
                                },
                                defaultDate: new Date(),
                                dateFormat: "Y-m-d",
                                disableMobile: true,
                                showMonths: 1,
                                monthSelectorType: "static",
                                onDayCreate: (dObj, dStr, fp, dayElem) => {
                                    const date = dayElem.dateObj;
                                    const dateStrFormatted = fp.formatDate(date, "Y-m-d");
                                    if (this.returnDates && this.returnDates.includes(dateStrFormatted)) {
                                        const dot = document.createElement("span");
                                        dot.className = "return-dot";
                                        dayElem.appendChild(dot);
                                    }
                                },
                                onChange: (selectedDates, dateStr) => {
                                    this.filterDate = dateStr;
                                }
                            });
                        },

                        updateAssets() {
                            if (!this.form.lab_id) {
                                this.availableAssets = [];
                                return;
                            }
                            const selectedLab = this.allLabs.find(l => String(l.id) === String(this.form.lab_id));
                            this.availableAssets = selectedLab ? selectedLab.assets : [];
                        },

                        formatDate(dateStr) {
                            if (!dateStr) return '-';
                            const dateOnly = dateStr.includes('T') ? dateStr.split('T')[0] : dateStr.split(' ')[0];
                            const options = { day: '2-digit', month: 'short', year: 'numeric' };
                            return new Date(dateOnly).toLocaleDateString('id-ID', options);
                        },

                        formatTime(timeStr) {
                            if (!timeStr) return '';
                            return timeStr.substring(0, 5);
                        },

                        getStatusColor(item) {
                            if (item.status !== 'returned' && item.status !== 'rejected' && this.isOverdue(item)) {
                                return 'bg-rose-100 text-rose-700';
                            }

                            const colors = { 'pending': 'bg-amber-100 text-amber-700', 'approved': 'bg-blue-100 text-blue-700', 'rejected': 'bg-red-100 text-red-700', 'returned': 'bg-green-100 text-green-700' };
                            return colors[item.status] || 'bg-gray-100 text-gray-700';
                        },

                        getStatusLabel(status) {
                            const labels = { 'pending': 'Menunggu', 'approved': 'Disetujui', 'rejected': 'Ditolak', 'returned': 'Kembali' };
                            return labels[status] || status;
                        },

                        isOverdue(item) {
                            if (item.status === 'returned' || item.status === 'rejected') return false;
                            if (!item.return_date) return false;

                            const returnDateStr = item.return_date.split('T')[0];
                            const returnTimeStr = item.return_time || '23:59:59';

                            const returnDateTime = new Date(`${returnDateStr}T${returnTimeStr}`);
                            const now = new Date();

                            return now > returnDateTime;
                        },

                        getLateDuration(item) {
                            // Use the exact same logic as backend if possible, or fallback to JS approximation
                            // But since we are displaying what backend sent essentially, we can reuse logic
                            // If item.actual_return_datetime is present, calculate exact

                            if (!item.actual_return_datetime) {
                                if (this.isOverdue(item)) {
                                    const returnDateStr = item.return_date.split('T')[0];
                                    const returnTimeStr = item.return_time || '23:59:59';
                                    const returnDateTime = new Date(`${returnDateStr}T${returnTimeStr}`);
                                    const now = new Date();

                                    const diffMs = now - returnDateTime;
                                    return this.msToDuration(diffMs);
                                }
                                return null;
                            }

                            const returnDateStr = item.return_date.split('T')[0];
                            const returnTimeStr = item.return_time || '23:59:59';
                            const expectedReturn = new Date(`${returnDateStr}T${returnTimeStr}`);
                            const actualReturn = new Date(item.actual_return_datetime);

                            if (actualReturn <= expectedReturn) return null;

                            const diffMs = actualReturn - expectedReturn;
                            return this.msToDuration(diffMs);
                        },

                        msToDuration(ms) {
                            // Simplified duration for JS display
                            const days = Math.floor(ms / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((ms % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((ms % (1000 * 60 * 60)) / (1000 * 60));

                            let parts = [];
                            if (days > 0) parts.push(`${days} hari`);
                            if (hours > 0) parts.push(`${hours} jam`);
                            if (minutes > 0 && days === 0) parts.push(`${minutes} menit`);

                            return parts.length > 0 ? parts.join(' ') : 'Baru saja';
                        },

                        openCreateModal() {
                            this.editMode = false;
                            const today = new Date().toISOString().split('T')[0];
                            this.form = {
                                id: '',
                                nama_peminjam: '',
                                nim: '',
                                lab_id: '',
                                asset_id: '',
                                borrow_date: today,
                                borrow_time: '08:00',
                                return_date: '',
                                return_time: '17:00',
                                status: 'pending',
                                notes: ''
                            };
                            this.availableAssets = [];
                            this.openModal = true;
                        },

                        openEditModal(borrowing) {
                            this.editMode = true;

                            const labId = String(borrowing.lab_id);
                            const selectedLab = this.allLabs.find(l => String(l.id) === labId);
                            this.availableAssets = selectedLab ? selectedLab.assets : [];

                            this.form = {
                                ...borrowing,
                                lab_id: labId,
                                asset_id: String(borrowing.asset_id),
                                borrow_date: borrowing.borrow_date ? borrowing.borrow_date.split('T')[0] : '',
                                borrow_time: borrowing.borrow_time ? borrowing.borrow_time.substring(0, 5) : '08:00',
                                return_date: borrowing.return_date ? borrowing.return_date.split('T')[0] : '',
                                return_time: borrowing.return_time ? borrowing.return_time.substring(0, 5) : '17:00'
                            };
                            this.openModal = true;
                        },

                        closeModal() {
                            this.openModal = false;
                        },

                        clearDateFilter() {
                            this.filterDate = '';
                        }
                    };
                };
            </script>
        @endpush
</x-app-layout>