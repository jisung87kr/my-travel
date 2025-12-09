<x-layouts.guide>
    <x-slot name="header">일정 관리</x-slot>

    <div class="space-y-6">
        <!-- Calendar Container -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">일정 캘린더</h2>
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        확정
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        진행중
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        완료
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div id="guide-schedule-calendar" class="guide-calendar"></div>
            </div>
        </div>
    </div>

    <!-- Event Detail Modal -->
    <div id="eventModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900" id="modalTitle">일정 상세</h3>
                <button onclick="closeModal()" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="p-6 space-y-4">
                <!-- Content will be injected by JavaScript -->
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                    닫기
                </button>
                <a id="modalDetailLink" href="#" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 rounded-xl shadow-lg shadow-teal-500/25 transition-all">
                    상세보기
                </a>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .guide-calendar .fc {
            font-family: inherit;
        }
        .guide-calendar .fc-toolbar-title {
            font-size: 1.125rem !important;
            font-weight: 600 !important;
            color: #0f172a;
        }
        .guide-calendar .fc-button-primary {
            background-color: #0d9488 !important;
            border-color: #0d9488 !important;
            font-weight: 500 !important;
            font-size: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2) !important;
        }
        .guide-calendar .fc-button-primary:hover {
            background-color: #0f766e !important;
            border-color: #0f766e !important;
        }
        .guide-calendar .fc-button-primary:disabled {
            opacity: 0.6 !important;
        }
        .guide-calendar .fc-button-active {
            background-color: #0f766e !important;
            border-color: #0f766e !important;
        }
        .guide-calendar .fc-daygrid-day-number {
            color: #475569;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .guide-calendar .fc-day-today {
            background-color: #f0fdfa !important;
        }
        .guide-calendar .fc-day-today .fc-daygrid-day-number {
            color: #0d9488;
            font-weight: 600;
        }
        .guide-calendar .fc-event {
            border-radius: 0.5rem !important;
            padding: 0.125rem 0.5rem !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            border: none !important;
            cursor: pointer !important;
        }
        .guide-calendar .fc-col-header-cell-cushion {
            color: #64748b;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        .guide-calendar .fc-scrollgrid {
            border-color: #e2e8f0 !important;
        }
        .guide-calendar .fc-scrollgrid td,
        .guide-calendar .fc-scrollgrid th {
            border-color: #e2e8f0 !important;
        }
        .guide-calendar .fc-list-event:hover td {
            background-color: #f0fdfa !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('guide-schedule-calendar');

            if (typeof FullCalendar !== 'undefined') {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'ko',
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,listWeek'
                    },
                    buttonText: {
                        today: '오늘',
                        month: '월',
                        week: '주',
                        list: '목록'
                    },
                    events: '{{ route("guide.schedules.events") }}',
                    eventClick: function(info) {
                        showEventModal(info.event);
                    },
                    eventDidMount: function(info) {
                        info.el.style.cursor = 'pointer';
                    }
                });
                calendar.render();
            }
        });

        function showEventModal(event) {
            const modal = document.getElementById('eventModal');
            const title = document.getElementById('modalTitle');
            const content = document.getElementById('modalContent');
            const detailLink = document.getElementById('modalDetailLink');

            title.textContent = event.title;

            const statusLabels = {
                'confirmed': '확정',
                'in_progress': '진행중',
                'completed': '완료'
            };

            const statusColors = {
                'confirmed': 'bg-amber-100 text-amber-700',
                'in_progress': 'bg-blue-100 text-blue-700',
                'completed': 'bg-emerald-100 text-emerald-700'
            };

            const status = event.extendedProps.status;

            content.innerHTML = `
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">날짜</span>
                    <span class="text-sm font-medium text-slate-900">${event.startStr}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">고객명</span>
                    <span class="text-sm font-medium text-slate-900">${event.extendedProps.customer}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">인원</span>
                    <span class="text-sm font-medium text-slate-900">${event.extendedProps.quantity}명</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-slate-500">상태</span>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full ${statusColors[status] || 'bg-slate-100 text-slate-600'}">${statusLabels[status] || status}</span>
                </div>
            `;

            if (event.extendedProps.booking_id) {
                detailLink.href = `/guide/schedules/${event.extendedProps.booking_id}`;
                detailLink.classList.remove('hidden');
            } else {
                detailLink.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
    @endpush
</x-layouts.guide>
