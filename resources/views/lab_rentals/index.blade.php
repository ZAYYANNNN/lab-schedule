<x-app-layout title="Jadwal Kalibrasi">
    <div class="px-4 py-6" x-data="calibrationPage()">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Jadwal Kalibrasi</h1>
                <p class="text-gray-500">Monitoring jadwal kalibrasi alat laboratorium.</p>
            </div>

            <a href="{{ route('schedules.index') }}"
                class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center">
                <span class="material-symbols-outlined mr-1">arrow_back</span>
                Kembali ke Jadwal Lab
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            {{-- Search Filter --}}
            <div class="mb-6 max-w-md">
                <div class="relative w-full rounded-xl">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        search
                    </span>

                    <input type="text" x-model="search" @input.debounce.500ms="refetchEvents()"
                        placeholder="Cari Lab..."
                        class="w-full border border-gray-300 pl-11 pr-4 py-2.5 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
            </div>

            <div id="calendar"></div>
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

                    <template x-if="selectedEvent">
                        <div class="p-8 sm:p-10 relative overflow-hidden">
                            {{-- Decorative Background --}}
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-purple-50/50 rounded-full -mr-16 -mt-16 blur-2xl">
                            </div>

                            <div class="flex justify-between items-start mb-8 relative z-10">
                                <div
                                    class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-inner bg-purple-50 text-purple-600">
                                    <span class="material-symbols-outlined text-3xl">event_upcoming</span>
                                </div>
                                <button @click="showDetail = false"
                                    class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all flex items-center justify-center group">
                                    <span
                                        class="material-symbols-outlined transition-transform group-hover:rotate-90">close</span>
                                </button>
                            </div>

                            <div class="mb-8 relative z-10">
                                <h3 class="text-3xl font-black text-slate-900 leading-tight mb-2 tracking-tighter"
                                    x-text="selectedEvent.extendedProps.activity"></h3>
                                <div class="flex items-center gap-3">
                                    <p class="text-xs font-bold text-slate-400"
                                        x-text="'Oleh: ' + (selectedEvent.extendedProps.creator || 'Sistem')"></p>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.15em] bg-purple-100 text-purple-700 border border-white">
                                        Kalibrasi
                                    </span>
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
                                            x-text="selectedEvent.extendedProps.lab_name"></p>
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
                                            Waktu</p>
                                        <p class="text-[14px] font-black tracking-tight"
                                            x-text="formatDateRange(selectedEvent.start, selectedEvent.end)"></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <style>
            [x-cloak] {
                display: none !important;
            }

            .fc-event {
                border: none !important;
            }

            .fc-daygrid-event {
                white-space: normal !important;
                align-items: start !important;
            }
        </style>
        <script>
            function calibrationPage() {
                return {
                    search: '{{ request('search') }}',
                    calendar: null,
                    events: @json($schedules),
                    showDetail: false,
                    selectedEvent: null,

                    init() {
                        const calendarEl = document.getElementById('calendar');
                        this.calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek'
                            },
                            events: this.events,
                            eventClick: (info) => {
                                this.selectedEvent = info.event;
                                this.showDetail = true;
                            },
                            eventContent: function (arg) {
                                const start = arg.event.start;
                                const end = arg.event.end;
                                const timeText = start.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + 
                                               ' - ' + 
                                               (end ? end.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '');

                                return {
                                    html: `
                                    <div class="px-2 py-1.5 w-full bg-purple-100/80 border-l-4 border-purple-500 rounded-r-md hover:bg-purple-200 transition-colors cursor-pointer mb-1 shadow-sm">
                                        <div class="flex items-center gap-1 mb-0.5">
                                            <span class="material-symbols-outlined text-[10px] text-purple-600">schedule</span>
                                            <span class="text-[10px] font-bold text-purple-700">${timeText}</span>
                                        </div>
                                        <div class="text-xs font-bold text-purple-900 leading-tight line-clamp-2">${arg.event.extendedProps.activity}</div>
                                        <div class="text-[10px] font-medium text-purple-600/80 truncate mt-0.5">${arg.event.extendedProps.lab_name}</div>
                                    </div>
                                    `
                                }
                            }
                        });
                        this.calendar.render();
                    },

                    refetchEvents() {
                        window.location.href = "{{ route('lab-rentals.index') }}?search=" + encodeURIComponent(this.search);
                    },

                    formatDate(dateObj) {
                        if (!dateObj) return '-';
                        return dateObj.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' });
                    },

                    formatDateRange(start, end) {
                        if (!start || !end) return '-';
                        const optionsDate = { weekday: 'long', day: 'numeric', month: 'long' };
                        const optionsTime = { hour: '2-digit', minute: '2-digit' };

                        const dateStr = start.toLocaleDateString('id-ID', optionsDate);
                        const startTime = start.toLocaleTimeString('id-ID', optionsTime);
                        const endTime = end.toLocaleTimeString('id-ID', optionsTime);

                        return `${dateStr}, ${startTime} - ${endTime}`;
                    }


                }
            }
        </script>
    @endpush
</x-app-layout>