<template>
    <form @submit.prevent="submitForm" class="space-y-8">
        <!-- Basic Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">기본 정보</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">상품 유형 *</label>
                    <select v-model="form.type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option v-for="type in types" :key="type.value" :value="type.value">
                            {{ type.label }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">지역 *</label>
                    <select v-model="form.region" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option v-for="region in regions" :key="region.value" :value="region.value">
                            {{ region.label }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">소요시간 (분)</label>
                    <input type="number" v-model.number="form.duration" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">최대 인원</label>
                    <input type="number" v-model.number="form.max_persons" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">예약 유형 *</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" v-model="form.booking_type" value="instant" class="mr-2">
                            자동 확정 (Instant Booking)
                        </label>
                        <label class="flex items-center">
                            <input type="radio" v-model="form.booking_type" value="request" class="mr-2">
                            승인 필요 (Request Booking)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Translation Tabs -->
        <TranslationTabs v-model="form.translations" />

        <!-- Prices -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">가격 설정</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">성인 가격 (원) *</label>
                    <input type="number" v-model.number="form.prices.adult" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">아동 가격 (원)</label>
                    <input type="number" v-model.number="form.prices.child" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
        </div>

        <!-- Images -->
        <ImageUploader
            v-model="form.newImages"
            :existing="form.existingImages"
            @delete-existing="deleteExistingImage"
        />

        <!-- Error Messages -->
        <div v-if="errors.length > 0" class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg">
            <ul class="list-disc list-inside">
                <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a :href="redirectUrl" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                취소
            </a>
            <button v-if="!isEdit" type="submit" @click="submitStatus = 'draft'"
                    :disabled="isSubmitting"
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 disabled:opacity-50">
                {{ isSubmitting ? '저장 중...' : '초안 저장' }}
            </button>
            <button type="submit" @click="submitStatus = isEdit ? null : 'pending_review'"
                    :disabled="isSubmitting"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                {{ isSubmitting ? '저장 중...' : (isEdit ? '저장' : '검토 요청') }}
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import TranslationTabs from './TranslationTabs.vue'
import ImageUploader from './ImageUploader.vue'

const props = defineProps({
    action: { type: String, required: true },
    method: { type: String, default: 'POST' },
    redirectUrl: { type: String, required: true },
    regions: { type: Array, required: true },
    types: { type: Array, required: true },
    product: { type: Object, default: null },
})

const isEdit = props.product !== null

const form = reactive({
    type: props.product?.type || props.types[0]?.value || '',
    region: props.product?.region || props.regions[0]?.value || '',
    duration: props.product?.duration || null,
    max_persons: props.product?.max_persons || null,
    booking_type: props.product?.booking_type || 'instant',
    translations: props.product?.translations || {
        ko: { title: '', short_description: '', description: '', includes: '', excludes: '' },
        en: { title: '', short_description: '', description: '', includes: '', excludes: '' },
        zh: { title: '', short_description: '', description: '', includes: '', excludes: '' },
        ja: { title: '', short_description: '', description: '', includes: '', excludes: '' },
    },
    prices: props.product?.prices || { adult: 0, child: 0 },
    existingImages: props.product?.images || [],
    newImages: [],
})

const isSubmitting = ref(false)
const errors = ref([])
const submitStatus = ref(null)

async function submitForm() {
    isSubmitting.value = true
    errors.value = []

    const formData = new FormData()
    formData.append('type', form.type)
    formData.append('region', form.region)
    formData.append('booking_type', form.booking_type)

    if (form.duration) formData.append('duration', form.duration)
    if (form.max_persons) formData.append('max_persons', form.max_persons)
    if (submitStatus.value) formData.append('status', submitStatus.value)

    // Translations
    Object.keys(form.translations).forEach(locale => {
        Object.keys(form.translations[locale]).forEach(field => {
            formData.append(`translations[${locale}][${field}]`, form.translations[locale][field] || '')
        })
    })

    // Prices
    formData.append('prices[adult]', form.prices.adult || 0)
    formData.append('prices[child]', form.prices.child || 0)

    // New Images
    form.newImages.forEach((file, index) => {
        formData.append(`images[${index}]`, file)
    })

    // Method override for PUT
    if (props.method === 'PUT') {
        formData.append('_method', 'PUT')
    }

    try {
        const response = await fetch(props.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        })

        const data = await response.json()

        if (response.ok) {
            window.location.href = props.redirectUrl
        } else if (response.status === 422) {
            errors.value = Object.values(data.errors).flat()
        } else {
            errors.value = [data.message || '저장에 실패했습니다.']
        }
    } catch (error) {
        errors.value = ['네트워크 오류가 발생했습니다.']
    } finally {
        isSubmitting.value = false
    }
}

async function deleteExistingImage(imageId) {
    if (!confirm('이미지를 삭제하시겠습니까?')) return

    try {
        const response = await fetch(`${props.action}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })

        if (response.ok) {
            form.existingImages = form.existingImages.filter(img => img.id !== imageId)
        }
    } catch (error) {
        console.error('Failed to delete image:', error)
    }
}
</script>
