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
                        <select name="prodi_id" class="w-full border p-2 rounded" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodis as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div>
                        <label>Password</label>
                        <input name="password" type="password" class="w-full border p-2 rounded" required>
                    </div>

                </div>

                <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan Admin
                </button>

            </form>
        </div>

        {{-- LIST ADMIN --}}
        <div class="bg-white p-5 rounded shadow">
            <h2 class="font-semibold text-lg mb-3">Daftar Admin</h2>

            @forelse($admins as $admin)
                <div class="border-b py-3 flex justify-between items-center">
                    <div>
                        <span class="font-medium">{{ $admin->name }}</span>
                        <span class="text-gray-500 text-sm mx-2">—</span>
                        <span class="text-gray-600">{{ $admin->email }}</span>
                        @php
                            $prodiName = \App\Models\Prodi::find($admin->prodi_id)?->name ?? '-';
                        @endphp
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded ml-2">{{ $prodiName }}</span>
                    </div>

                    <div class="flex gap-2">
                        <button @click="openEditModal({{ $admin }})"
                            class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>

                        <form action="{{ route('users.destroy', $admin->id) }}" method="POST"
                            onsubmit="return confirm('Hapus admin ini?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Belum ada admin.</p>
            @endforelse
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
                        <select name="prodi_id" class="w-full border p-2 rounded" x-model="editData.prodi_id" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodis as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
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
                    this.editData = { ...user };
                    this.openEdit = true;
                }
            }
        }
    </script>

</x-app-layout>