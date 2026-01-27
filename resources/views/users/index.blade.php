<x-app-layout>

    <div class="max-w-5xl mx-auto py-6" x-data="userPage()">

        <h1 class="text-2xl font-bold mb-4">Kelola Admin</h1>

        {{-- FORM TAMBAH ADMIN --}}
        <div class="bg-white p-5 rounded shadow mb-6">
            <h2 class="font-semibold text-lg mb-3">Tambah Admin Baru</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4">

                    <div class="mb-3">
                        <label class="block font-semibold">Nama Admin</label>
                        <input name="name" type="text" class="w-full border p-2 rounded" required
                            placeholder="Nama Lengkap">
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold">Email Admin</label>
                        <input name="email" type="email" class="w-full border p-2 rounded" required
                            placeholder="email@contoh.com">
                    </div>



                    <div>
                        <label class="block font-semibold">Password</label>
                        <input name="password" type="password" class="w-full border p-2 rounded" required>
                    </div>

                </div>

                <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded shadow-sm hover:bg-blue-700 transition">
                    Simpan Admin
                </button>

            </form>
        </div>

        {{-- LIST ADMIN --}}
        <div class="bg-white p-5 rounded shadow mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">Daftar Admin</h2>
                <span
                    class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-500 font-bold uppercase tracking-wider">{{ count($admins) }}
                    Total</span>
            </div>

            <div class="space-y-2">
                @forelse($admins as $admin)
                    <div class="border rounded-lg p-3 flex justify-between items-center hover:bg-gray-50 transition">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $admin->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $admin->email }}</div>
                            </div>

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

        {{-- MODAL EDIT --}}
        <div x-show="openEdit" x-cloak
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" x-cloak>
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
                        ...user
                    };
                    this.openEdit = true;
                },

            }
        }

    </script>


</x-app-layout>