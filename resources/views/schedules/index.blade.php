<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Jadwal Lab</h1>
            <a href="{{ route('schedules.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Schedule</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($schedules as $schedule)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ $schedule->activity }}</h2>
                            <p class="text-gray-600 text-sm mt-1">{{ $schedule->lab->name }}</p>
                            @if(auth()->user()->role === 'superadmin' && $schedule->lab->prodi)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $schedule->lab->prodi->name ?? $schedule->lab->prodi }}</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-blue-600">
                                {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <a href="{{ route('schedules.edit', $schedule->id) }}"
                            class="text-blue-500 hover:text-blue-700 text-sm">Edit</a>
                        <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>