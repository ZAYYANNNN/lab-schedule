<x-app-layout>

    <div class="max-w-5xl mx-auto py-6">

        <h1 class="text-2xl font-bold mb-4">Kelola Admin</h1>

        {{-- FORM TAMBAH ADMIN --}}
        <div class="bg-white p-5 rounded shadow mb-6">
            <h2 class="font-semibold text-lg mb-3">Tambah Admin Baru</h2>

            <form action="{{ route('superadmin.users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label>Nama</label>
                        <input name="name" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Email</label>
                        <input name="email" type="email" class="w-full border p-2 rounded">
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
                        <input name="password" type="password" class="w-full border p-2 rounded">
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
                <div class="border-b py-2">
                    {{ $admin->name }} — {{ $admin->email }} ({{ $admin->prodi->name ?? '-' }})
                </div>
            @empty
                <p class="text-gray-500">Belum ada admin.</p>
            @endforelse
        </div>

    </div>

</x-app-layout>
