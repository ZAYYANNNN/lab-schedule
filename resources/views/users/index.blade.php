<x-app-layout>

    <div class="max-w-5xl mx-auto py-6" x-data="userPage()">

        <h1 class="text-2xl font-bold mb-4">Kelola Admin</h1>

        {{-- FORM TAMBAH ADMIN --}}
        <div class="bg-white p-5 rounded shadow mb-6">
            <h2 class="font-semibold text-lg mb-3">Tambah Admin Baru</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label>Nama</label>
                        <input name="name" class="w-full border p-2 rounded" required>
                    </div>

                    <div>
                        <label>Email</label>
                        <input name="email" type="email" class="w-full border p-2 rounded" required>
                    </div>

                    <div>
                        <label>Prodi</label>

                        {{-- input autocomplete --}}
                        <input type="text" list="prodi-list" id="prodi_input" class="w-full border p-2 rounded"
                            placeholder="Ketik nama prodi..." required>

                        {{-- hidden id --}}
                        <input type="hidden" name="prodi_id" id="prodi_id">

                        <datalist id="prodi-list">
                            @foreach($prodis as $p)
                                <option data-id="{{ $p->id }}" value="{{ $p->name }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label>Password</label>
                        <input name="password" type="password" class="w-full border p-2 rounded" required>
                    </div>

                </div>

                <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded shadow-sm hover:bg-blue-700 transition">
                    Simpan Admin
                </button>

            </form>
        </div>

        {{-- FORM TAMBAH PRODI --}}
        <div class="bg-white p-5 rounded shadow mb-6">
            <h2 class="font-semibold text-lg mb-3">Tambah Prodi Baru</h2>
            <form action="{{ route('prodis.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <input name="names[]"
                            class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Nama Prodi 1">
                    </div>
                    <div>
                        <input name="names[]"
                            class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Nama Prodi 2">
                    </div>
                    <div>
                        <input name="names[]"
                            class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Nama Prodi 3">
                    </div>
                </div>
                <button
                    class="mt-4 bg-slate-800 text-white px-4 py-2 rounded shadow-sm hover:bg-slate-900 transition flex items-center">
                    <span class="material-symbols-outlined mr-2 text-[20px]">save</span>
                    Simpan Prodi
                </button>
            </form>
        </div>

        {{-- LIST ADMIN --}}
        <div class="bg-white p-5 rounded shadow mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Daftar Admin</h2>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-500 font-bold uppercase tracking-wider">{{ count($admins) }} Total</span>
            </div>

            <div class="space-y-2">
                @forelse($admins as $admin)
                    <div class="border rounded-lg p-3 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $admin->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $admin->email }}</div>
                            </div>
                            @php
                                $prodiName = \App\Models\Prodi::find($admin->prodi_id)?->name ?? '-';
                            @endphp
                            <span class="ml-4 px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest">{{ $prodiName }}</span>
                        </div>

                        <div class="flex gap-2">
                            <button @click="openEditModal({{ $admin }})"
                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </button>

                            <form action="{{ route('users.destroy', $admin->id) }}" method="POST"
                                onsubmit="return confirm('Hapus admin ini?');">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded transition">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic text-center py-4">Belum ada admin.</p>
                @endforelse
            </div>
        </div>

        {{-- LIST PRODI --}}
        <div class="bg-white p-5 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Daftar Prodi</h2>
                <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-500 font-bold uppercase tracking-wider">{{ count($prodis) }} Total</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($prodis as $p)
                    <div class="border rounded-lg p-3 flex justify-between items-center group hover:border-blue-200 hover:shadow-sm transition bg-white">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-500 mr-3">
                                <span class="material-symbols-outlined text-sm">school</span>
                            </div>
                            <span class="font-bold text-gray-800 text-sm">{{ $p->name }}</span>
                        </div>
                        <form action="{{ route('prodis.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus prodi ini?');">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-500 opacity-0 group-hover:opacity-100 hover:bg-red-50 rounded transition">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-4 text-gray-500 italic">Belum ada data prodi.</div>
                @endforelse
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="openEdit" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
            x-cloak>
            <div @click.outside="openEdit = false" class="bg-white w-[30rem] p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-bold mb-4">Edit Admin</h2>

                <form :action="`/users/${editData.id}`" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="block">Nama</label>
                        <input name="name" x-model="editData.name" class="w-full border p-2 rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block">Email</label>
                        <input name="email" type="email" x-model="editData.email" class="w-full border p-2 rounded"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block">Prodi</label>

                        <input type="text" list="prodi-edit-list" class="w-full border p-2 rounded"
                            x-model="editData.prodi_name" @input="syncProdiId($event.target.value)" required>

                        <input type="hidden" name="prodi_id" x-model="editData.prodi_id">

                        <datalist id="prodi-edit-list">
                            @foreach($prodis as $p)
                                <option data-id="{{ $p->id }}" value="{{ $p->name }}"></option>
                            @endforeach
                        </datalist>
                    </div>


                    <div class="mb-3">
                        <label class="block">Password Baru (Optional)</label>
                        <input name="password" type="password" class="w-full border p-2 rounded"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function userPage() {
            return {
                openEdit: false,
                editData: {},

                openEditModal(user) {
                    this.editData = {
                        ...user,
                        prodi_name: user.prodi_name ?? ''
                    };
                    this.openEdit = true;
                },

                syncProdiId(value) {
                    const options = document.querySelectorAll('#prodi-edit-list option');
                    this.editData.prodi_id = '';

                    options.forEach(opt => {
                        if (opt.value === value) {
                            this.editData.prodi_id = opt.dataset.id;
                        }
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('prodi_input');
            const hidden = document.getElementById('prodi_id');
            const options = document.querySelectorAll('#prodi-list option');

            input.addEventListener('input', function () {
                hidden.value = '';

                options.forEach(opt => {
                    if (opt.value === input.value) {
                        hidden.value = opt.dataset.id;
                    }
                });
            });
        });
    </script>


</x-app-layout>