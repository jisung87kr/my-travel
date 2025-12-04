<template>
    <div class="schedule-calendar">
        <!-- Product Selector -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">상품 선택</label>
            <select
                v-model="selectedProductId"
                @change="loadSchedules"
                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg"
            >
                <option value="">상품을 선택하세요</option>
                <option v-for="product in products" :key="product.id" :value="product.id">
                    {{ product.title }}
                </option>
            </select>
        </div>

        <!-- Calendar -->
        <div v-if="selectedProductId" class="bg-white rounded-lg shadow p-4">
            <div ref="calendarRef"></div>
        </div>

        <div v-else class="bg-white rounded-lg shadow p-12 text-center text-gray-500">
            일정을 관리할 상품을 선택하세요.
        </div>

        <!-- Inventory Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">재고 설정</h3>
                    <p class="text-sm text-gray-500">{{ modalData.date }}</p>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">총 재고</label>
                        <input
                            type="number"
                            v-model.number="modalData.totalCount"
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">예약 가능 재고</label>
                        <input
                            type="number"
                            v-model.number="modalData.availableCount"
                            min="0"
                            :max="modalData.totalCount"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            예약됨: {{ modalData.totalCount - modalData.availableCount }}
                        </p>
                    </div>

                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            v-model="modalData.isActive"
                            id="is-active"
                            class="mr-2"
                        >
                        <label for="is-active" class="text-sm text-gray-700">예약 가능</label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-2">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                    >
                        취소
                    </button>
                    <button
                        type="button"
                        @click="saveSchedule"
                        :disabled="isSaving"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ isSaving ? '저장 중...' : '저장' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Create Modal -->
        <div v-if="showBulkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">일괄 일정 생성</h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">시작일</label>
                            <input
                                type="date"
                                v-model="bulkData.startDate"
                                :min="today"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">종료일</label>
                            <input
                                type="date"
                                v-model="bulkData.endDate"
                                :min="bulkData.startDate || today"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">재고 수량</label>
                        <input
                            type="number"
                            v-model.number="bulkData.totalCount"
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">요일 선택</label>
                        <div class="flex flex-wrap gap-2">
                            <label v-for="day in weekdays" :key="day.value" class="flex items-center">
                                <input
                                    type="checkbox"
                                    v-model="bulkData.days"
                                    :value="day.value"
                                    class="mr-1"
                                >
                                <span class="text-sm">{{ day.label }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-2">
                    <button
                        type="button"
                        @click="showBulkModal = false"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                    >
                        취소
                    </button>
                    <button
                        type="button"
                        @click="saveBulkSchedules"
                        :disabled="isSaving"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ isSaving ? '생성 중...' : '일정 생성' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch, nextTick } from 'vue'
import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

const props = defineProps({
    products: { type: Array, required: true },
    csrfToken: { type: String, required: true },
})

const calendarRef = ref(null)
let calendar = null

const selectedProductId = ref('')
const schedules = ref([])
const bookings = ref([])
const showModal = ref(false)
const showBulkModal = ref(false)
const isSaving = ref(false)

const today = new Date().toISOString().split('T')[0]

const modalData = reactive({
    date: '',
    scheduleId: null,
    totalCount: 10,
    availableCount: 10,
    isActive: true,
})

const bulkData = reactive({
    startDate: '',
    endDate: '',
    totalCount: 10,
    days: [0, 1, 2, 3, 4, 5, 6], // All days selected
})

const weekdays = [
    { value: 0, label: '일' },
    { value: 1, label: '월' },
    { value: 2, label: '화' },
    { value: 3, label: '수' },
    { value: 4, label: '목' },
    { value: 5, label: '금' },
    { value: 6, label: '토' },
]

function initCalendar() {
    if (calendar) {
        calendar.destroy()
    }

    calendar = new Calendar(calendarRef.value, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: 'ko',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'bulkCreate',
        },
        customButtons: {
            bulkCreate: {
                text: '일괄 생성',
                click: () => {
                    showBulkModal.value = true
                },
            },
        },
        events: generateEvents(),
        dateClick: handleDateClick,
        eventClick: handleEventClick,
        validRange: {
            start: today,
        },
    })

    calendar.render()
}

function generateEvents() {
    const events = []

    schedules.value.forEach(schedule => {
        const color = !schedule.is_active ? '#9CA3AF' :
                      schedule.available_count === 0 ? '#EF4444' :
                      schedule.available_count < 3 ? '#F59E0B' : '#10B981'

        events.push({
            id: `schedule-${schedule.id}`,
            title: `재고: ${schedule.available_count}/${schedule.total_count}`,
            date: schedule.date,
            backgroundColor: color,
            borderColor: color,
            extendedProps: { type: 'schedule', schedule },
        })
    })

    bookings.value.forEach(booking => {
        const statusColors = {
            pending: '#F59E0B',
            confirmed: '#3B82F6',
            completed: '#10B981',
            cancelled: '#9CA3AF',
            no_show: '#EF4444',
        }

        events.push({
            id: `booking-${booking.id}`,
            title: `예약 #${booking.booking_code}`,
            date: booking.schedule?.date,
            backgroundColor: statusColors[booking.status] || '#6B7280',
            borderColor: statusColors[booking.status] || '#6B7280',
            extendedProps: { type: 'booking', booking },
        })
    })

    return events
}

function handleDateClick(info) {
    const existingSchedule = schedules.value.find(s => s.date === info.dateStr)

    modalData.date = info.dateStr
    modalData.scheduleId = existingSchedule?.id || null
    modalData.totalCount = existingSchedule?.total_count || 10
    modalData.availableCount = existingSchedule?.available_count || 10
    modalData.isActive = existingSchedule?.is_active ?? true

    showModal.value = true
}

function handleEventClick(info) {
    const { type, schedule, booking } = info.event.extendedProps

    if (type === 'schedule') {
        modalData.date = schedule.date
        modalData.scheduleId = schedule.id
        modalData.totalCount = schedule.total_count
        modalData.availableCount = schedule.available_count
        modalData.isActive = schedule.is_active

        showModal.value = true
    } else if (type === 'booking') {
        window.location.href = `/vendor/bookings/${booking.id}`
    }
}

async function loadSchedules() {
    if (!selectedProductId.value) {
        schedules.value = []
        bookings.value = []
        return
    }

    try {
        const response = await fetch(`/vendor/products/${selectedProductId.value}/schedules`, {
            headers: {
                'Accept': 'application/json',
            },
        })

        const data = await response.json()
        schedules.value = data.schedules || []
        bookings.value = data.bookings || []

        await nextTick()
        initCalendar()
    } catch (error) {
        console.error('Failed to load schedules:', error)
    }
}

async function saveSchedule() {
    isSaving.value = true

    try {
        const response = await fetch(`/vendor/products/${selectedProductId.value}/schedules`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': props.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                date: modalData.date,
                total_count: modalData.totalCount,
                available_count: modalData.availableCount,
                is_active: modalData.isActive,
            }),
        })

        if (response.ok) {
            showModal.value = false
            await loadSchedules()
        }
    } catch (error) {
        console.error('Failed to save schedule:', error)
    } finally {
        isSaving.value = false
    }
}

async function saveBulkSchedules() {
    isSaving.value = true

    try {
        const response = await fetch(`/vendor/products/${selectedProductId.value}/schedules/bulk`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': props.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                start_date: bulkData.startDate,
                end_date: bulkData.endDate,
                total_count: bulkData.totalCount,
                days_of_week: bulkData.days,
            }),
        })

        if (response.ok) {
            showBulkModal.value = false
            await loadSchedules()
        }
    } catch (error) {
        console.error('Failed to create bulk schedules:', error)
    } finally {
        isSaving.value = false
    }
}

watch(selectedProductId, () => {
    if (selectedProductId.value) {
        loadSchedules()
    }
})
</script>
