<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Edit Lab
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

            <form action="{{ route('superadmin.labs.update', $lab) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1">Nama Lab</label>
                    <input type="text" name="name" value="{{ $lab->name }}"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Kode Lab</label>
                    <input type="text" name="kode" value="{{ $lab->kode }}"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Update
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
