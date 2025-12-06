<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Peminjaman Barang</h1>
            <a href="{{ route('borrowings.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Borrowing</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Peminjam</th>
                        <th class="py-3 px-6 text-left">Barang</th>
                        <th class="py-3 px-6 text-left">Lab</th>
                        <th class="py-3 px-6 text-center">Tgl Pinjam</th>
                        <th class="py-3 px-6 text-center">Tgl Kembali</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($borrowings as $borrowing)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $borrowing->user->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $borrowing->asset->nama }}</td>
                            <td class="py-3 px-6 text-left">{{ $borrowing->lab->name }}</td>
                            <td class="py-3 px-6 text-center">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                            <td class="py-3 px-6 text-center">{{ $borrowing->return_date->format('d M Y') }}</td>
                            <td class="py-3 px-6 text-center">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                            @if($borrowing->status == 'approved') bg-green-200 text-green-800
                                            @elseif($borrowing->status == 'pending') bg-yellow-200 text-yellow-800
                                            @elseif($borrowing->status == 'returned') bg-blue-200 text-blue-800
                                            @else bg-red-200 text-red-800 @endif">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <a href="{{ route('borrowings.edit', $borrowing->id) }}"
                                        class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('borrowings.destroy', $borrowing->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-4 transform hover:text-red-500 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>