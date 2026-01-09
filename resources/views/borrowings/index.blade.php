<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4" x-data="borrowingPage()">

        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Peminjaman Barang</h1>
                <p class="text-gray-500 mt-1">Kelola peminjaman aset laboratorium di prodi Anda.</p>
            </div>
            <button @click="openCreateModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center gap-2 font-semibold">
                <span class="material-symbols-outlined">add</span>
                Catat Peminjaman
            </button>
        </header>

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Aset & Lab
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal
                                Pinjam</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($borrowings as $b)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                            <span class="material-symbols-outlined text-lg">person</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $b->user->name }}</p>
                                            <p class="text-xs text-gray-400 font-medium">{{ $b->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $b->asset->nama ?? 'Aset dihapus' }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span
                                                class="material-symbols-outlined text-[14px] text-gray-400">meeting_room</span>
                                            <span class="text-xs text-gray-500 font-medium">{{ $b->lab->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <p class="font-semibold text-gray-700">{{ $b->borrow_date->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">Kembali: {{ $b->return_date->format('d M Y') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            'returned' => 'bg-green-100 text-green-700',
                                        ];
                                        $labels = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'returned' => 'Kembali',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-widest {{ $colors[$b->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $labels[$b->status] ?? $b->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openEditModal({{ $b }})"
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-md">edit</span>
                                        </button>
                                        <form action="{{ route('borrowings.destroy', $b->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus data peminjaman ini?')">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                                <span class="material-symbols-outlined text-md">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="material-symbols-outlined text-5xl text-gray-200 mb-4 font-light">shopping_bag</span>
                                        <p class="text-gray-400 font-medium italic">Belum ada data peminjaman.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal --}}
        <div x-show="openModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500/30 backdrop-blur-sm"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="closeModal()"
                    class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-8">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900"
                            x-text="editMode ? 'Edit Peminjaman' : 'Catat Peminjaman Baru'"></h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <form :action="editMode ? `/borrowings/${form.id}` : '{{ route('borrowings.store') }}'"
                        method="POST" class="space-y-5">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Peminjam</label>
                            <select name="user_id" x-model="form.user_id"
                                class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-medium py-2.5"
                                required>
                                <option value="">Pilih Peminjam</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Lab</label>
                                <select name="lab_id" x-model="form.lab_id" @change="updateAssets()"
                                    class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-medium py-2.5"
                                    required>
                                    <option value="">Pilih Lab</option>
                                    @foreach($labs as $l)
                                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Aset</label>
                                <select name="asset_id" x-model="form.asset_id"
                                    class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-medium py-2.5"
                                    required>
                                    <option value="">Pilih Aset</option>
                                    <template x-for="asset in availableAssets" :key="asset.id">
                                        <option :value="asset.id" x-text="asset.nama"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Mulai Pinjam</label>
                                <input type="date" name="borrow_date" x-model="form.borrow_date"
                                    class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-medium py-2.5"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Rencana Kembali</label>
                                <input type="date" name="return_date" x-model="form.return_date"
                                    class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-medium py-2.5"
                                    required>
                            </div>
                        </div>

                        <template x-if="editMode">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Status</label>
                                <select name="status" x-model="form.status"
                                    class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-medium py-2.5"
                                    required>
                                    <option value="pending">Menunggu</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="returned">Sudah Kembali</option>
                                </select>
                            </div>
                        </template>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Catatan</label>
                            <textarea name="notes" x-model="form.notes" rows="3"
                                class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                                placeholder="Opsional..."></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-50">
                            <button type="button" @click="closeModal()"
                                class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all">Batal</button>
                            <button type="submit"
                                class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-200 transition-all"
                                x-text="editMode ? 'Simpan Perubahan' : 'Catat Peminjaman'"></button>
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
                    form: {
                        id: '',
                        user_id: '',
                        lab_id: '',
                        asset_id: '',
                        borrow_date: '',
                        return_date: '',
                        status: 'pending',
                        notes: ''
                    },
                    labs: @json($labs),
                    availableAssets: [],

                    init() {
                        this.$watch('form.lab_id', (val) => this.updateAssets());
                    },

                    updateAssets() {
                        const selectedLab = this.labs.find(l => l.id === this.form.lab_id);
                        this.availableAssets = selectedLab ? selectedLab.assets : [];
                        // Reset asset if no longer in list
                        if (!this.availableAssets.find(a => a.id === this.form.asset_id)) {
                            this.form.asset_id = '';
                        }
                    },

                    openCreateModal() {
                        this.editMode = false;
                        this.form = {
                            id: '',
                            user_id: '',
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
                        // Format dates for input[type="date"]
                        const bDate = borrowing.borrow_date ? borrowing.borrow_date.split('T')[0] : '';
                        const rDate = borrowing.return_date ? borrowing.return_date.split('T')[0] : '';

                        this.form = {
                            ...borrowing,
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