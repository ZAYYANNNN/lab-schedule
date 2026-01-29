<x-app-layout>
    <div class="w-full px-4 py-6 overflow-x-hidden" x-data="schedulePage()">

        @php
            function getProdiTheme($prodi)
            {
                $name = is_object($prodi) ? $prodi->name : $prodi;
                return match ($name) {
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

            function getActivityTypeTheme($activityType)
            {
                $name = is_object($activityType) ? strtolower($activityType->name) : strtolower($activityType ?? '');
                return match (true) {
                    str_contains($name, 'kalibrasi') => [
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
                    str_contains($name, 'praktikum') => [
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
                    str_contains($name, 'pengujian') => [
                        'bg' => 'bg-yellow-50',
                        'bgHover' => 'hover:bg-yellow-100',
                        'border' => 'border-yellow-500',
                        'borderLight' => 'border-yellow-200',
                        'text' => 'text-yellow-900',
                        'textMuted' => 'text-yellow-700/70',
                        'textBold' => 'text-yellow-700',
                        'icon' => 'text-yellow-600',
                        'tag' => 'bg-yellow-100 text-yellow-700'
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

        <div class="flex flex-col lg:flex-row gap-6">
            {{-- SIDEBAR FILTER --}}
            <aside class="w-full lg:w-80 flex-shrink-0 space-y-6">
                <form action="{{ route('schedules.index') }}" method="GET" id="filterForm" class="space-y-6">
                    <input type="hidden" name="filter_submitted" value="1">
                    {{-- CALENDAR --}}
                    <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-2
                        [&_.flatpickr-calendar]:w-full
                        [&_.flatpickr-calendar]:border-0
                        [&_.flatpickr-calendar]:shadow-none

                        /* HEADER ROW */
                        [&_.flatpickr-months]:flex
                        [&_.flatpickr-months]:items-center
                        [&_.flatpickr-months]:justify-between
                        [&_.flatpickr-months]:mb-3

                        [&_.flatpickr-prev-month]:static
                        [&_.flatpickr-next-month]:static
                        [&_.flatpickr-prev-month]:p-1
                        [&_.flatpickr-next-month]:p-1

                        /* BULAN + TAHUN */
                        [&_.flatpickr-current-month]:flex
                        [&_.flatpickr-current-month]:items-center
                        [&_.flatpickr-current-month]:gap-1
                        [&_.flatpickr-current-month]:flex-1
                        [&_.flatpickr-current-month]:justify-center

                        [&_.cur-month]:text-base
                        [&_.cur-month]:font-semibold
                        [&_.cur-month]:leading-none
                        [&_.cur-month]:text-gray-800

                        [&_.cur-year]:text-base
                        [&_.cur-year]:font-semibold
                        [&_.cur-year]:leading-none
                        [&_.cur-year]:text-gray-800
                        [&_.cur-year]:bg-transparent
                        [&_.cur-year]:border-0
                        [&_.cur-year]:pointer-events-none

                        /* DOT MARKER */
                        [&_.schedule-dot]:absolute
                        [&_.schedule-dot]:bottom-1
                        [&_.schedule-dot]:left-1/2
                        [&_.schedule-dot]:-translate-x-1/2
                        [&_.schedule-dot]:w-1
                        [&_.schedule-dot]:h-1
                        [&_.schedule-dot]:rounded-full
                        [&_.schedule-dot]:bg-blue-500
                    ">

                        <div id="inline-calendar"></div>
                        <input type="hidden" name="date" id="dateInput" value="{{ $selectedDate }}">
                    </div>

                    {{-- PRODI/LAB FILTERS --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                        <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                            <span class="material-symbols-outlined text-blue-600 mr-2 text-[20px]">
                                meeting_room
                            </span>
                            Filter Lab
                        </h3>

                        {{-- Search Filter Lab --}}
                        <div class="mb-3 relative flex gap-2">
                            <div class="relative flex-1">
                                <span
                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                                <input type="text" x-model="labSearch" placeholder="Cari Lab..."
                                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            </div>
                            <div class="relative w-28">
                                <select x-model="labTypeFilter"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none cursor-pointer">
                                    <option value="">Semua</option>
                                    @foreach(\App\Models\LabType::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <span
                                    class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-thin">
                            <div class="space-y-2">
                                <template x-for="lab in filteredLabs" :key="lab.id">
                                    <label
                                        class="flex items-center cursor-pointer group p-2.5 hover:bg-blue-50/50 rounded-2xl border border-transparent hover:border-blue-100 transition-all duration-300">
                                        <input type="checkbox" name="lab_ids[]" :value="lab.id" x-model="selectedLabs"
                                            class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-500 shadow-sm transition-all">
                                        <span
                                            class="ml-3 text-sm font-bold text-slate-700 group-hover:text-blue-600 transition"
                                            x-text="lab.name"></span>
                                    </label>
                                </template>
                                <template x-if="filteredLabs.length === 0">
                                    <p class="text-xs text-center text-gray-400 py-2">Lab tidak ditemukan</p>
                                </template>
                            </div>
                        </div>

                        {{-- Apply Filter Button --}}
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-blue-600 text-white hover:bg-blue-700 transition-all font-bold text-xs uppercase tracking-wider shadow-lg shadow-blue-200">
                                <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                                Terapkan Filter
                            </button>
                        </div>

                        @if(!empty($selectedProdiIds) || !empty($selectedLabIds))
                            <div class="mt-8 pt-6 border-t border-slate-100">
                                <a href="{{ route('schedules.index', ['date' => $selectedDate]) }}"
                                    class="w-full flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all font-black text-[10px] uppercase tracking-[0.2em] border border-slate-100 hover:border-rose-100">
                                    <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                                    Reset Semua Filter
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </aside>

            {{-- MAIN BOARD --}}
            <div class="flex-1 min-w-0 min-h-[700px]">
                <div
                    class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/40 border border-slate-100 overflow-hidden ring-1 ring-slate-100/50">
                    <div class="overflow-auto scrollbar-none" style="max-height: 850px;">
                        @php
                            $labCount = count($labs);
                            $gridCols = "100px repeat(" . max(1, $labCount) . ", minmax(280px, 1fr))";
                            $totalSlots = 64; // Extended to 11 PM (16 hours * 4 slots per hour)
                        @endphp

                        <div class="grid relative bg-white" style="grid-template-columns: {{ $gridCols }}; 
                                            grid-template-rows: 70px repeat({{ $totalSlots }}, 1.25rem);">

                            {{-- 1. HEADER WAKTU (POJOK) --}}
                            <div
                                class="sticky top-0 left-0 z-[50] bg-slate-50/80 border-r border-b border-white-300 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] shadow-lg">
                                Waktu
                            </div>

                            {{-- 2. HEADER NAMA LAB --}}
                            @if($labCount > 0)
                                @foreach($labs as $lab)
                                    <div
                                        class="sticky top-0 z-[40] bg-white/95 backdrop-blur-xl px-8 py-3 border-r border-b border-slate-100 flex flex-col justify-center shadow-sm">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <h3 class="font-black text-slate-800 text-[13px] uppercase tracking-tight truncate">
                                                {{ $lab->name }}
                                            </h3>
                                        </div>
                                        <p
                                            class="text-[9px] text-blue-500 font-bold uppercase tracking-widest opacity-60 truncate">
                                            {{ is_object($lab->prodi) ? $lab->prodi->name : ($lab->prodi ?? '-') }}
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    class="sticky top-0 z-[40] bg-slate-50/50 backdrop-blur-sm border-b border-slate-100 flex items-center justify-center text-slate-400 p-12 text-center col-start-2">
                                    <div class="flex flex-col items-center mt-20 pt-20">
                                        <div
                                            class="w-20 h-20 rounded-3xl bg-white shadow-xl flex items-center justify-center text-slate-200 mb-6">
                                            <span class="material-symbols-outlined text-5xl">inventory_2</span>
                                        </div>
                                        <h4
                                            class="text-slate-800 font-black text-lg mb-2 leading-none uppercase tracking-widest">
                                            Tidak Ada Data</h4>
                                        <p class="text-sm font-medium text-slate-400 max-w-[250px]">Pilih Program Studi atau
                                            Lab
                                            untuk melihat timeline kegiatan.</p>
                                    </div>
                                </div>
                            @endif

                            {{-- 3. LABEL JAM & GRID LINES (07:00 - 23:00) --}}
                            @for($i = 0; $i < 16; $i++)
                                @php $currentHour = 7 + $i; @endphp
                                <div class="sticky left-0 z-[30] bg-slate-50/80 backdrop-blur-sm border-r border-slate-100 flex items-start justify-center pt-2 text-[11px] font-black text-slate-400 shadow-sm"
                                    style="grid-row: {{ ($i * 4) + 2 }} / span 4;">
                                    {{ sprintf('%02d:00', $currentHour) }}
                                </div>

                                {{-- Horizontal Lines --}}
                                <div class="col-start-2 col-end-[-1] border-b border-slate-300"
                                    style="grid-row: {{ ($i * 4) + 2 }};"></div>
                                <div class="col-start-2 col-end-[-1] border-b border-slate-300 border-dashed"
                                    style="grid-row: {{ ($i * 4) + 4 }};"></div>
                            @endfor

                            {{-- 4. ITEMS JADWAL --}}
                            @if($labCount > 0)
                                @foreach($schedules as $schedule)
                                    @php
                                        $labIndex = $labs->search(fn($l) => $l->id === $schedule->lab_id);
                                        if ($labIndex === false)
                                            continue;

                                        $start = \Carbon\Carbon::parse($schedule->start_time);
                                        $end = \Carbon\Carbon::parse($schedule->end_time);
                                        $startSlot = (($start->hour - 7) * 4) + floor($start->minute / 15);
                                        // Use ceil for end time so card extends properly to cover full duration
                                        $endSlot = (($end->hour - 7) * 4) + ceil($end->minute / 15);
                                        // If end minute is 0, don't add extra slot
                                        if ($end->minute == 0) {
                                            $endSlot = (($end->hour - 7) * 4);
                                        }
                                        $startRow = $startSlot + 2;
                                        $span = max(1, $endSlot - $startSlot);
                                        $colIndex = $labIndex + 2;

                                        $lab = $labs[$labIndex];
                                        $theme = getProdiTheme($lab->prodi);
                                        $activityTheme = getActivityTypeTheme($schedule->activityType);
                                    @endphp

                                    <div class="z-10 px-2 py-1"
                                        style="grid-column: {{ $colIndex }}; grid-row: {{ $startRow }} / span {{ $span }};">
                                        <div @click="openDetailModal({{ $schedule->toJson() }})"
                                            class="group h-full w-full rounded-2xl border-l-[6px] {{ $activityTheme['border'] }} {{ $theme['bg'] }} p-4 flex flex-col justify-between shadow-xl shadow-slate-200/20 ring-1 ring-black/5 hover:scale-[1.02] transform transition-all duration-300 cursor-pointer overflow-hidden relative">
                                            {{-- Decorative Glow inside item --}}
                                            <div
                                                class="absolute -right-4 -top-4 w-12 h-12 rounded-full opacity-10 blur-xl {{ $activityTheme['icon'] }}">
                                            </div>

                                            <div class="relative z-10">
                                                <h4
                                                    class="font-black text-[12px] leading-tight mb-2 tracking-tight uppercase {{ $theme['textBold'] }} line-clamp-2">
                                                    {{ $schedule->activity }}
                                                </h4>
                                                <div
                                                    class="flex items-center text-[10px] font-bold {{ $theme['textMuted'] }} opacity-80 mb-1 uppercase tracking-widest leading-none">
                                                    <span class="material-symbols-outlined text-[14px] mr-1.5">school</span>
                                                    {{ $schedule->creator->name ?? 'Dosen Pengampu' }}
                                                </div>
                                                {{-- Activity Type Badge --}}
                                                @if($schedule->activityType)
                                                    <div
                                                        class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 mt-24 border border-black/10 {{ $activityTheme['tag'] }}">
                                                        <span class="material-symbols-outlined text-[10px]">category</span>
                                                        <span
                                                            class="text-[12px] font-bold uppercase tracking-wider">{{ $schedule->activityType->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div
                                                class="flex items-center justify-between border-t {{ $theme['borderLight'] }} pt-3 mt-auto relative z-10">
                                                <div
                                                    class="flex items-center text-[10px] font-black {{ $theme['textBold'] }} opacity-90 uppercase tracking-[0.1em]">
                                                    <span class="material-symbols-outlined text-[14px] mr-1.5">schedule</span>
                                                    {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                                </div>
                                                <div
                                                    class="w-6 h-6 rounded-lg bg-white/50 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <span
                                                        class="material-symbols-outlined text-sm {{ $activityTheme['icon'] }}">visibility</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Vertical Grid Lines --}}
                            @if($labCount > 0)
                                @foreach($labs as $index => $lab)
                                    <div class="pointer-events-none border-r border-slate-300"
                                        style="grid-column: {{ $index + 2 }}; grid-row: 2 / span {{ $totalSlots + 1 }};">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
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
                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showDetail" x-transition:enter="ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-90"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-white/20">

                    <template x-if="selectedSchedule">
                        <div class="p-8 sm:p-10 relative overflow-hidden">
                            {{-- Decorative Background --}}
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full -mr-16 -mt-16 blur-2xl">
                            </div>

                            <div class="flex justify-between items-start mb-8 relative z-10">
                                <div
                                    :class="`w-16 h-16 rounded-2xl flex items-center justify-center shadow-inner ${getTheme(selectedSchedule.lab?.prodi).bg}`">
                                    <span
                                        :class="`material-symbols-outlined text-3xl ${getTheme(selectedSchedule.lab?.prodi).icon}`">event_note</span>
                                </div>
                                <button @click="showDetail = false"
                                    class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all flex items-center justify-center group">
                                    <span
                                        class="material-symbols-outlined transition-transform group-hover:rotate-90">close</span>
                                </button>
                            </div>

                            <div class="mb-8 relative z-10">
                                <h3 class="text-3xl font-black text-slate-900 leading-tight mb-2 tracking-tighter"
                                    x-text="selectedSchedule.activity"></h3>
                                <div class="flex items-center gap-3">
                                    <p class="text-xs font-bold text-slate-400"
                                        x-text="'Oleh: ' + (selectedSchedule.creator?.name || 'Sistem')"></p>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <template x-if="selectedSchedule.activity_type">
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.15em] bg-purple-100 text-purple-700 border border-white"
                                            x-text="selectedSchedule.activity_type.name">
                                        </span>
                                    </template>

                                    <template x-if="selectedSchedule.lab?.prodi">
                                        <span
                                            :class="`inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.15em] ${getTheme(selectedSchedule.lab?.prodi).bg100} ${getTheme(selectedSchedule.lab?.prodi).text700} border border-white`"
                                            x-text="(typeof selectedSchedule.lab.prodi === 'object') ? selectedSchedule.lab.prodi.name : selectedSchedule.lab.prodi"></span>
                                    </template>
                                </div>
                            </div>

                            <div
                                class="space-y-4 bg-slate-50/50 rounded-[2rem] p-6 border border-slate-100 mb-8 relative z-10">
                                <div class="flex items-center gap-4 text-slate-700">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-blue-500 border border-slate-100">
                                        <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] uppercase font-black text-slate-400 tracking-widest mb-0.5">
                                            Laboratorium</p>
                                        <p class="text-[14px] font-black tracking-tight"
                                            x-text="selectedSchedule.lab?.name"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 text-slate-700">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-indigo-500 border border-slate-100">
                                        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] uppercase font-black text-slate-400 tracking-widest mb-0.5">
                                            Hari & Tanggal</p>
                                        <p class="text-[14px] font-black tracking-tight"
                                            x-text="new Date(selectedSchedule.date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })">
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 text-slate-700">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-emerald-500 border border-slate-100">
                                        <span class="material-symbols-outlined text-[20px]">schedule</span>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] uppercase font-black text-slate-400 tracking-widest mb-0.5">
                                            Alokasi Waktu</p>
                                        <p class="text-[14px] font-black tracking-tight"
                                            x-text="selectedSchedule.start_time.substring(0,5) + ' - ' + selectedSchedule.end_time.substring(0,5)">
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4 relative z-10">
                                <button @click="openEditModalFromDetail()"
                                    class="flex-1 bg-slate-900 text-white px-6 py-4 rounded-2xl hover:bg-blue-600 shadow-xl shadow-slate-900/10 hover:shadow-blue-200 transition-all font-black text-xs uppercase tracking-[0.2em] flex items-center justify-center gap-3 active:scale-95">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                    <span>Edit</span>
                                </button>

                                <form :action="'{{ url('/schedules') }}/' + selectedSchedule.id" method="POST"
                                    class="contents" onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-14 h-14 bg-slate-50 text-slate-300 px-4 py-2.5 rounded-2xl hover:bg-rose-50 hover:text-rose-600 transition-all flex items-center justify-center border border-slate-100 hover:border-rose-100 active:scale-95">
                                        <span class="material-symbols-outlined text-2xl font-black">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Form Modal (Create/Edit) --}}
        <div x-show="showModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" @click="showModal = false">
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div x-show="showModal" x-transition:enter="ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-24 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white">

                    <form :action="formAction" method="POST" class="p-8 sm:p-10">
                        @csrf
                        <template x-if="editMode">
                            <div>
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" :value="formData.id">
                            </div>
                        </template>

                        <div class="mb-10 flex items-center justify-between">
                            <div>
                                <h3 class="text-3xl font-black text-slate-900 tracking-tighter"
                                    x-text="editMode ? 'Edit Jadwal' : 'Tambah Jadwal'"></h3>
                                <p
                                    class="text-sm font-medium text-slate-400 mt-1 uppercase tracking-widest text-[10px]">
                                    Lengkapi data formulir berikut.</p>
                            </div>
                            <div
                                class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                                <span class="material-symbols-outlined text-3xl"
                                    x-text="editMode ? 'edit_calendar' : 'add_task'"></span>
                            </div>
                        </div>

                        @error('collision')
                            <div
                                class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center gap-3">
                                <span class="material-symbols-outlined">warning</span>
                                <p class="text-sm font-bold">{{ $message }}</p>
                            </div>
                        @enderror

                        <div class="space-y-8">
                            <div class="grid grid-cols-1 gap-6">
                                {{-- LAB --}}
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Laboratorium</label>
                                    <div class="relative group">
                                        <select name="lab_id" x-model="formData.lab_id"
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all appearance-none cursor-pointer"
                                            required>
                                            <option value="">-- Pilih Laboratorium --</option>
                                            @foreach($allLabs as $lab)
                                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                            @endforeach
                                        </select>
                                        <span
                                            class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                                    </div>
                                    @error('lab_id') <p
                                        class="text-rose-500 text-[10px] mt-2 font-bold ml-1 tracking-tight">
                                        {{ $message }}
                                    </p> @enderror
                                </div>

                                {{-- ACTIVITY TYPE --}}
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Jenis
                                        Kegiatan</label>
                                    <div class="relative group">
                                        <select name="activity_type_id" x-model="formData.activity_type_id"
                                            class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all appearance-none cursor-pointer"
                                            required>
                                            <option value="">-- Pilih Jenis Kegiatan --</option>
                                            @foreach(\App\Models\ActivityType::all() as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        <span
                                            class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">expand_more</span>
                                    </div>
                                    @error('activity_type_id') <p
                                        class="text-rose-500 text-[10px] mt-2 font-bold ml-1 tracking-tight">
                                        {{ $message }}
                                    </p> @enderror
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Nama
                                    Kegiatan / Mata Kuliah</label>
                                <div class="relative group">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors">description</span>
                                    <input type="text" name="activity" x-model="formData.activity"
                                        placeholder="Contoh: Pemrograman Web"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-12 pr-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                                        required>
                                </div>
                                @error('activity') <p
                                    class="text-rose-500 text-[10px] mt-2 font-bold ml-1 tracking-tight">
                                    {{ $message }}
                                </p> @enderror
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Tanggal</label>
                                    <input type="date" name="date" x-model="formData.date"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                                        required>
                                    @error('date') <p
                                        class="text-rose-500 text-[10px] mt-2 font-bold ml-1 tracking-tight">
                                        {{ $message }}
                                    </p> @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Jam
                                        Mulai</label>
                                    <input type="time" name="start_time" x-model="formData.start_time"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                                        required>
                                    @error('start_time') <p
                                        class="text-rose-500 text-[10px] mt-2 font-bold ml-1 tracking-tight">
                                        {{ $message }}
                                    </p> @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Jam
                                        Selesai</label>
                                    <input type="time" name="end_time" x-model="formData.end_time"
                                        class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                                        required>
                                    @error('end_time') <p
                                        class="text-rose-500 text-[10px] mt-2 font-bold ml-1 tracking-tight">
                                        {{ $message }}
                                    </p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex gap-4">
                            <button type="button" @click="showModal = false"
                                class="flex-1 px-6 py-4 rounded-2xl bg-slate-50 text-slate-400 font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-100 transition-all active:scale-95">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-[2] px-6 py-4 rounded-2xl bg-blue-600 text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg">save</span>
                                Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const scheduledDates = @json($scheduledDates);

                flatpickr("#inline-calendar", {
                    inline: true,
                    locale: {
                        firstDayOfWeek: 1, // Minggu starts on 0, but displayed first is Senin (1)
                        weekdays: {
                            shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                            longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
                        },
                        months: {
                            shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                            longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
                        }
                    },
                    defaultDate: "{{ $selectedDate }}",
                    dateFormat: "Y-m-d",
                    disableMobile: true,
                    showMonths: 1,

                    /* MATIKAN DROPDOWN BULAN & TAHUN */
                    monthSelectorType: "static",

                    onDayCreate: function (_, __, fp, dayElem) {
                        const date = dayElem.dateObj;
                        const day = date.getDay();

                        /* Tandai Sabtu & Minggu sebagai hari libur */
                        if (day === 0 || day === 6) {
                            dayElem.classList.add('holiday');
                        }

                        // Add dot for scheduled dates
                        const dateStr = fp.formatDate(date, "Y-m-d");
                        if (scheduledDates.includes(dateStr)) {
                            const dot = document.createElement("span");
                            dot.className = "schedule-dot";
                            dayElem.appendChild(dot);
                        }
                    },

                    onChange: function (selectedDates, dateStr) {
                        document.getElementById('dateInput').value = dateStr;
                        document.getElementById('filterForm').submit();
                    }
                });
            });
        </script>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
        <script>
            function schedulePage() {
                return {
                    showModal: {{ $errors->any() ? 'true' : 'false' }},
                    showDetail: false,
                    selectedSchedule: null,
                    editMode: {{ old('_method') === 'PUT' ? 'true' : 'false' }},
                    formAction: '{{ old('_method') === 'PUT' ? url('/schedules/' . old('id')) : route('schedules.store') }}',

                    selectedLabs: [{!! implode(',', array_map(fn($id) => "'$id'", $selectedLabIds)) !!}],

                    // Lab Search Data
                    labSearch: '',
                    labTypeFilter: '',
                    allLabs: @json($allLabs),
                    get filteredLabs() {
                        let labs = this.allLabs;
                        if (this.labSearch !== '') {
                            labs = labs.filter(lab => lab.name.toLowerCase().includes(this.labSearch.toLowerCase()));
                        }
                        if (this.labTypeFilter !== '') {
                            labs = labs.filter(lab => String(lab.type_id) === String(this.labTypeFilter));
                        }
                        return labs;
                    },

                    formData: {
                        id: '{{ old('id') }}',
                        lab_id: '{{ old('lab_id') }}',
                        activity: '{{ old('activity') }}',
                        activity_type_id: '{{ old('activity_type_id') }}',
                        date: '{{ old('date', $selectedDate) }}',
                        start_time: '{{ old('start_time') }}',
                        end_time: '{{ old('end_time') }}'
                    },
                    openCreateModal() {
                        this.editMode = false;
                        this.formAction = '{{ route('schedules.store') }}';
                        this.formData = { id: '', lab_id: '', activity: '', activity_type_id: '', date: '{{ $selectedDate }}', start_time: '', end_time: '' };
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
                            activity_type_id: schedule.activity_type_id,
                            date: schedule.date,
                            start_time: schedule.start_time.substring(0, 5),
                            end_time: schedule.end_time.substring(0, 5)
                        };
                        this.showModal = true;
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>