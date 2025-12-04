<x-layouts.guide>
    <x-slot name="header">일정 관리</x-slot>

    <div class="bg-white rounded-lg shadow p-6">
        <div id="guide-schedule-calendar"></div>
    </div>

    <!-- Event Detail Modal -->
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold" id="modalTitle">일정 상세</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="space-y-3">
                <!-- Content will be injected by JavaScript -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                    닫기
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('guide-schedule-calendar');

            if (typeof FullCalendar !== 'undefined') {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'ko',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,listWeek'
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

            title.textContent = event.title;

            const statusLabels = {
                'confirmed': '확정',
                'in_progress': '진행중',
                'completed': '완료'
            };

            content.innerHTML = `
                <div class="flex justify-between">
                    <span class="text-gray-500">날짜</span>
                    <span>${event.startStr}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">고객명</span>
                    <span>${event.extendedProps.customer}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">인원</span>
                    <span>${event.extendedProps.quantity}명</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">상태</span>
                    <span>${statusLabels[event.extendedProps.status] || event.extendedProps.status}</span>
                </div>
            `;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
    @endpush
</x-layouts.guide>
