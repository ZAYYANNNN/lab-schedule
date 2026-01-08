<x-app-layout>
    <div class="w-full px-4 py-6 overflow-x-hidden" x-data="{ 
        showModal: {{ $errors->any() ? 'true' : 'false' }}, 
        showDetail: false,
        selectedSchedule: null,
        editMode: {{ old('_method') === 'PUT' ? 'true' : 'false' }},
        formAction: '{{ old('_method') === 'PUT' ? url('/schedules/' . old('id')) : route('schedules.store') }}',
        prodi_id: '{{ old('prodi_id') }}',
        formData: {
            id: '{{ old('id') }}',
            lab_id: '{{ old('lab_id') }}',
            activity: '{{ old('activity') }}',
            date: '{{ old('date', $selectedDate) }}',
            start_time: '{{ old('start_time') }}',
            end_time: '{{ old('end_time') }}'
        },
        openCreateModal() {
            this.editMode = false;
            this.formAction = '{{ route('schedules.store') }}';
            this.formData = { id: '', lab_id: '', activity: '', date: '{{ $selectedDate }}', start_time: '', end_time: '' };
            this.prodi_id = '';
            this.showModal = true;
        },
        openDetailModal(schedule) {
            this.selectedSchedule = schedule;
            this.showDetail = true;
        },
        openEditModalFromDetail() {
            if (!this.selectedSchedule) return;
            this.openEditModal(this.selectedSchedule);
            this.showDetail = false;
        },
        prodiThemes: {
            'Teknik Informatika': { bg: 'bg-blue-50', bg100: 'bg-blue-100', text700: 'text-blue-700', icon: 'text-blue-600' },
            'Teknik Sipil': { bg: 'bg-green-50', bg100: 'bg-green-100', text700: 'text-green-700', icon: 'text-green-600' },
            'Teknik Mesin': { bg: 'bg-red-50', bg100: 'bg-red-100', text700: 'text-red-700', icon: 'text-red-600' },
            'Teknik Elektro': { bg: 'bg-amber-50', bg100: 'bg-amber-100', text700: 'text-amber-700', icon: 'text-amber-600' },
            'Hukum': { bg: 'bg-purple-50', bg100: 'bg-purple-100', text700: 'text-purple-700', icon: 'text-purple-600' },
            'Default': { bg: 'bg-slate-50', bg100: 'bg-slate-100', text700: 'text-slate-700', icon: 'text-slate-600' }
        },
        getTheme(prodi) {
            const name = (typeof prodi === 'object' && prodi !== null) ? prodi.name : prodi;
            return this.prodiThemes[name] || this.prodiThemes['Default'];
        },
        openEditModal(schedule) {
            this.editMode = true;
            this.formAction = '/schedules/' + schedule.id;
            this.formData = {
                id: schedule.id,
                lab_id: schedule.lab_id,
                activity: schedule.activity,
                date: schedule.date,
                start_time: schedule.start_time.substring(0, 5),
                end_time: schedule.end_time.substring(0, 5)
            };
            this.prodi_id = schedule.lab?.prodi_id || '';
            this.showModal = true;
        }
    }">

        @php
        function getProdiTheme($prodi) {
            $name = is_object($prodi) ? $prodi->name : $prodi;
            return match($name) {
                'Teknik Informatika' => [
                    'bg' => 'bg-blue-50',
                    'bgHover' => 'hover:bg-blue-100',
                    'border' => 'border-blue-500',
                    'borderLight' => 'border-blue-200',
                    'text' => 'text-blue-900',
                    'textMuted' => 'text-blue-700/70',
                    'textBold' => 'text-blue-700',
                    'icon' => 'text-blue-600',
                    'tag' => 'bg-blue-100 text-blue-700'
                ],
                'Teknik Sipil' => [
                    'bg' => 'bg-green-50',
                    'bgHover' => 'hover:bg-green-100',
                    'border' => 'border-green-500',
                    'borderLight' => 'border-green-200',
                    'text' => 'text-green-900',
                    'textMuted' => 'text-green-700/70',
                    'textBold' => 'text-green-700',
                    'icon' => 'text-green-600',
                    'tag' => 'bg-green-100 text-green-700'
                ],
                'Teknik Mesin' => [
                    'bg' => 'bg-red-50',
                    'bgHover' => 'hover:bg-red-100',
                    'border' => 'border-red-500',
                    'borderLight' => 'border-red-200',
                    'text' => 'text-red-900',
                    'textMuted' => 'text-red-700/70',
                    'textBold' => 'text-red-700',
                    'icon' => 'text-red-600',
                    'tag' => 'bg-red-100 text-red-700'
                ],
                'Teknik Elektro' => [
                    'bg' => 'bg-amber-50',
                    'bgHover' => 'hover:bg-amber-100',
                    'border' => 'border-amber-500',
                    'borderLight' => 'border-amber-200',
                    'text' => 'text-amber-900',
                    'textMuted' => 'text-amber-700/70',
                    'textBold' => 'text-amber-700',
                    'icon' => 'text-amber-600',
                    'tag' => 'bg-amber-100 text-amber-700'
                ],
                'Hukum' => [
                    'bg' => 'bg-purple-50',
                    'bgHover' => 'hover:bg-purple-100',
                    'border' => 'border-purple-500',
                    'borderLight' => 'border-purple-200',
                    'text' => 'text-purple-900',
                    'textMuted' => 'text-purple-700/70',
                    'textBold' => 'text-purple-700',
                    'icon' => 'text-purple-600',
                    'tag' => 'bg-purple-100 text-purple-700'
                ],
                default => [
                    'bg' => 'bg-slate-50',
                    'bgHover' => 'hover:bg-slate-100',
                    'border' => 'border-slate-500',
                    'borderLight' => 'border-slate-200',
                    'text' => 'text-slate-900',
                    'textMuted' => 'text-slate-700/70',
                    'textBold' => 'text-slate-700',
                    'icon' => 'text-slate-600',
                    'tag' => 'bg-slate-100 text-slate-700'
                ]
            };
        }
        @endphp

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Board Jadwal Lab</h1>
                <p class="text-gray-500">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <form action="{{ route('schedules.index') }}" method="GET" class="flex items-center space-x-2">
                    {{-- Prodi Filter --}}
                    @if(auth()->user()->role === 'superadmin')
                        <select name="prodi_id" onchange="this.form.submit()" 
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5">
                            <option value="">Semua Prodi</option>
                            @foreach($allProdis as $prodi)
                                <option value="{{ $prodi->id }}" {{ $selectedProdi == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    {{-- Date Filter --}}
                    <input type="date" name="date" value="{{ $selectedDate }}" 
                           onchange="this.form.submit()"
                           class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-1.5">
                </form>

                <button type="button" @click="openCreateModal()"
                    class="bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 transition flex items-center shadow-sm text-sm font-semibold">
                    <span class="material-symbols-outlined text-[20px] mr-2">add</span>
                    Tambah Jadwal
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-auto scrollbar-modern" style="max-height: 800px;"> 
                @php
                    $labCount = count($labs);
                    $gridCols = "80px repeat($labCount, minmax(250px, 1fr))";
                    // 1 jam dibagi 4 slot (15 menit). Total 11 jam (07:00 - 18:00) = 44 slot + 1 header
                    $totalSlots = 44; 
                @endphp

                <div class="grid relative" 
                     style="grid-template-columns: {{ $gridCols }}; 
                            grid-template-rows: 50px repeat({{ $totalSlots }}, 1.75rem);">
                    
                    {{-- 1. HEADER WAKTU (POJOK) --}}
                    <div class="sticky top-0 left-0 z-50 bg-slate-50 border-r border-b border-gray-200 flex items-center justify-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Waktu
                    </div>
                    
                    {{-- 2. HEADER NAMA LAB --}}
                    @foreach($labs as $lab)
                        <div class="sticky top-0 z-40 bg-slate-50/95 backdrop-blur-sm px-4 py-1 border-r border-b border-gray-200 flex flex-col justify-center">
                            <h3 class="font-bold text-slate-800 text-sm truncate uppercase tracking-tight leading-tight">
                                {{ $lab->name }}
                            </h3>
                            <p class="mt-1 text-[9px] text-blue-600 font-bold truncate opacity-80 leading-tight">
                                {{ $lab->prodi }}
                            </p>
                        </div>
                    @endforeach


                    {{-- 3. BACKGROUND GRID & LABEL JAM --}}
                    @for($i = 0; $i < 11; $i++)
                        @php $currentHour = 7 + $i; @endphp
                        {{-- Label Jam --}}
                        <div class="sticky left-0 z-30 bg-slate-50 border-r border-gray-200 flex items-start justify-center pt-1 text-[11px] font-semibold text-slate-500"
                             style="grid-row: {{ ($i * 4) + 2 }} / span 4;">
                            {{ sprintf('%02d:00', $currentHour) }}
                        </div>

                        {{-- Garis Horizontal (Tiap Jam) --}}
                        <div class="col-start-2 col-end-[-1] border-t border-slate-300"
                             style="grid-row: {{ ($i * 4) + 2 }};"></div>
                        {{-- Garis halus per 30 menit --}}
                        <div class="col-start-2 col-end-[-1] border-t border-slate-200 border-dashed"
                             style="grid-row: {{ ($i * 4) + 4 }};"></div>
                    @endfor

                    {{-- 4. ITEMS JADWAL --}}
                    @foreach($schedules as $schedule)
                        @php
                            $labIndex = $labs->search(fn($l) => $l->id === $schedule->lab_id);
                            if ($labIndex === false) continue;

                            $start = \Carbon\Carbon::parse($schedule->start_time);
                            $end = \Carbon\Carbon::parse($schedule->end_time);
                            
                            // Hitung posisi berdasarkan slot 15 menit
                            $startSlot = (($start->hour - 7) * 4) + floor($start->minute / 15);
                            $endSlot = (($end->hour - 7) * 4) + floor($end->minute / 15);
                            
                            $startRow = $startSlot + 2; 
                            $span = max(1, $endSlot - $startSlot);
                            $colIndex = $labIndex + 2;
                        @endphp

                        <div class="z-10 px-1.5 py-0.5"
                            style="grid-column: {{ $colIndex }}; grid-row: {{ $startRow }} / span {{ $span }};">
                                                        @php
                                $lab = $labs[$labIndex];
                                $theme = getProdiTheme($lab->prodi);
                            @endphp

                            <div @click="openDetailModal({{ $schedule->toJson() }})"
                                class="
                                    h-full w-full rounded-lg
                                    border-l-4 {{ $theme['border'] }}
                                    {{ $theme['bg'] }}
                                    p-2 flex flex-col justify-between
                                    shadow-sm ring-1 ring-black/5
                                    {{ $theme['bgHover'] }} hover:shadow-md
                                    cursor-pointer transition
                                ">

                                <div class="overflow-hidden">
                                    <h4 class="font-bold text-[11px] leading-tight truncate uppercase tracking-tight {{ $theme['text'] }}">
                                        {{ $schedule->activity }}
                                    </h4>

                                    <div class="flex items-center mt-1 text-[9px] font-medium {{ $theme['textMuted'] }}">
                                        <span class="material-symbols-outlined text-[10px] mr-1">person</span>
                                        {{ $schedule->creator->name ?? 'Admin' }}
                                    </div>
                                </div>

                                <div class="mt-1 pt-1 border-t {{ $theme['borderLight'] }} text-[9px] font-bold {{ $theme['textBold'] }}">
                                    <span class="material-symbols-outlined text-[10px] mr-1">schedule</span>
                                    {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                </div>
                            </div>

                        </div>
                    @endforeach

                    {{-- Garis Vertikal Pembatas Lab --}}
                    @foreach($labs as $index => $lab)
                        <div class="pointer-events-none border-r border-gray-200"
                             style="grid-column: {{ $index + 2 }}; grid-row: 2 / span {{ $totalSlots + 1 }};">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Detail Modal --}}
        <div x-show="showDetail" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDetail" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" @click="showDetail = false">
                    <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showDetail" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    
                    <template x-if="selectedSchedule">
                        <div class="p-6">
                            @php
                                // We use a JS function for dynamic color in Alpine, but we can set up the theme classes here
                            @endphp
                            <div class="flex justify-between items-start mb-6">
                                <div :class="`p-3 rounded-xl ${getTheme(selectedSchedule.lab?.prodi).bg}`">
                                    <span :class="`material-symbols-outlined ${getTheme(selectedSchedule.lab?.prodi).icon}`">event_note</span>
                                </div>
                                <button @click="showDetail = false" class="text-gray-400 hover:text-gray-600 transition">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-1" x-text="selectedSchedule.activity"></h3>
                                <p class="text-sm text-gray-500" x-text="'Dibuat oleh: ' + (selectedSchedule.creator?.name || 'Admin')"></p>
                                <template x-if="selectedSchedule.lab?.prodi">
                                    <span :class="`inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${getTheme(selectedSchedule.lab?.prodi).bg100} ${getTheme(selectedSchedule.lab?.prodi).text700}`" 
                                          x-text="(typeof selectedSchedule.lab.prodi === 'object') ? selectedSchedule.lab.prodi.name : selectedSchedule.lab.prodi"></span>
                                </template>
                            </div>

                            <div class="space-y-4 bg-gray-50 rounded-2xl p-4 mb-6">
                                <div class="flex items-center space-x-3 text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400">meeting_room</span>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1">Ruangan Lab</p>
                                        <p class="text-sm font-semibold" x-text="selectedSchedule.lab?.name"></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400">calendar_today</span>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1">Tanggal</p>
                                        <p class="text-sm font-semibold" x-text="new Date(selectedSchedule.date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 text-gray-700">
                                    <span class="material-symbols-outlined text-gray-400">schedule</span>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1">Waktu</p>
                                        <p class="text-sm font-semibold" x-text="selectedSchedule.start_time.substring(0,5) + ' - ' + selectedSchedule.end_time.substring(0,5)"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <button @click="openEditModalFromDetail()" 
                                    class="flex-1 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-50 transition font-bold text-sm flex items-center justify-center space-x-2">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    <span>Edit</span>
                                </button>
                                
                                <form :action="'{{ url('/schedules') }}/' + selectedSchedule.id" method="POST" class="flex-1"
                                    onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="w-full bg-red-50 text-red-600 px-4 py-2.5 rounded-xl hover:bg-red-100 transition font-bold text-sm flex items-center justify-center space-x-2">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Form Modal (Create/Edit) --}}
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" @click="showModal = false">
                    <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form :action="formAction" method="POST">
                        @csrf
                        <template x-if="editMode">
                            <div>
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" :value="formData.id">
                            </div>
                        </template>

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" x-text="editMode ? 'Edit Jadwal' : 'Tambah Jadwal Baru'"></h3>
                            
                            <div class="space-y-4">
                                <div class="flex space-x-4">
                                    {{-- PRODI --}}
                                    <div class="w-1/2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Prodi</label>
                                        <select name="prodi_id" x-model="prodi_id" class="shadow border rounded w-full py-2 px-3 text-sm" required>
                                            <option value="">-- Pilih Prodi --</option>
                                            @foreach($allProdis as $prodi)
                                                <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('prodi_id') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- LAB --}}
                                    <div class="w-1/2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Lab</label>
                                        <select name="lab_id" x-model="formData.lab_id" 
                                                class="shadow border rounded w-full py-2 px-3 text-sm" 
                                                :disabled="!prodi_id" required>
                                            <option value="">-- Pilih Lab --</option>
                                            @foreach($allLabs as $lab)
                                                <template x-if="prodi_id == '{{ $lab->prodi_id }}'">
                                                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                                </template>
                                            @endforeach
                                        </select>
                                        @error('lab_id') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Activity</label>
                                    <input type="text" name="activity" x-model="formData.activity" 
                                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm" required>
                                    @error('activity') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex space-x-4">
                                    <div class="w-full">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                                        <input type="date" name="date" x-model="formData.date" 
                                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm" required>
                                        @error('date') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="flex space-x-4">
                                    <div class="w-1/2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Start Time</label>
                                        <input type="time" name="start_time" x-model="formData.start_time" 
                                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm" required>
                                        @error('start_time') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="w-1/2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">End Time</label>
                                        <input type="time" name="end_time" x-model="formData.end_time" 
                                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm" required>
                                        @error('end_time') <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <style>
            [x-cloak] { display: none !important; }
            
            /* Styling Scrollbar Modern */
            .scrollbar-modern::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            .scrollbar-modern::-webkit-scrollbar-track {
                background: #f8fafc;
            }
            .scrollbar-modern::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
                border: 2px solid #f8fafc;
            }
            .scrollbar-modern::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Efek bayangan halus pada kolom waktu yang sticky */
            .sticky.left-0 {
                box-shadow: 2px 0 5px -2px rgba(0,0,0,0.05);
            }
        </style>
    @endpush
</x-app-layout>