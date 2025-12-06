<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Edit Borrowing</h1>

        <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
            <form action="{{ route('borrowings.update', $borrowing->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="user_id">Borrower</label>
                    <select name="user_id" id="user_id"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $borrowing->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->prodi }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="lab_id">Lab</label>
                    <select name="lab_id" id="lab_id"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        onchange="updateAssets()">
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ $borrowing->lab_id == $lab->id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('lab_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="asset_id">Asset</label>
                    <select name="asset_id" id="asset_id"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Asset</option>
                        <!-- Populated by JS -->
                    </select>
                    @error('asset_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex space-x-4 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="borrow_date">Borrow Date</label>
                        <input type="date" name="borrow_date" id="borrow_date"
                            value="{{ $borrowing->borrow_date->format('Y-m-d') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        @error('borrow_date') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="return_date">Return Date</label>
                        <input type="date" name="return_date" id="return_date"
                            value="{{ $borrowing->return_date->format('Y-m-d') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                        @error('return_date') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
                    <select name="status" id="status"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="pending" {{ $borrowing->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $borrowing->status == 'approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="rejected" {{ $borrowing->status == 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                        <option value="returned" {{ $borrowing->status == 'returned' ? 'selected' : '' }}>Returned
                        </option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">Notes</label>
                    <textarea name="notes" id="notes"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $borrowing->notes }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Update Borrowing
                    </button>
                    <a href="{{ route('borrowings.index') }}"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const labs = @json($labs);
        const currentAssetId = "{{ $borrowing->asset_id }}";

        function updateAssets() {
            const labId = document.getElementById('lab_id').value;
            const assetSelect = document.getElementById('asset_id');
            assetSelect.innerHTML = '<option value="">Select Asset</option>';

            if (labId) {
                const selectedLab = labs.find(l => l.id == labId);
                if (selectedLab && selectedLab.assets) {
                    selectedLab.assets.forEach(asset => {
                        const option = document.createElement('option');
                        option.value = asset.id;
                        option.text = asset.nama + (asset.kode_aset ? ' (' + asset.kode_aset + ')' : '');
                        if (asset.id == currentAssetId) {
                            option.selected = true;
                        }
                        assetSelect.appendChild(option);
                    });
                }
            }
        }

        // Initialize assets on load
        document.addEventListener('DOMContentLoaded', updateAssets);
    </script>
</x-app-layout>