<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">상품 정보 (다국어)</h3>

        <!-- Tab Headers -->
        <div class="border-b border-gray-200 mb-4">
            <nav class="flex space-x-4">
                <button
                    v-for="lang in languages"
                    :key="lang.code"
                    type="button"
                    @click="activeTab = lang.code"
                    :class="[
                        'px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors',
                        activeTab === lang.code
                            ? 'border-blue-500 text-blue-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    ]"
                >
                    {{ lang.label }}
                    <span v-if="lang.required" class="text-red-500 ml-1">*</span>
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div v-for="lang in languages" :key="lang.code" v-show="activeTab === lang.code" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    상품명 {{ lang.required ? '*' : '' }}
                </label>
                <input
                    type="text"
                    v-model="translations[lang.code].title"
                    :required="lang.required"
                    :placeholder="lang.code === 'ko' ? '' : `한국어: ${translations.ko?.title || ''}`"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">간단 설명</label>
                <input
                    type="text"
                    v-model="translations[lang.code].short_description"
                    :placeholder="lang.code === 'ko' ? '검색 결과에 표시될 짧은 설명' : `한국어: ${translations.ko?.short_description || ''}`"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    상세 설명 {{ lang.required ? '*' : '' }}
                </label>
                <textarea
                    v-model="translations[lang.code].description"
                    :required="lang.required"
                    rows="5"
                    :placeholder="lang.code === 'ko' ? '상품에 대한 상세한 설명을 입력하세요' : `한국어: ${translations.ko?.description || ''}`"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">포함 사항</label>
                    <textarea
                        v-model="translations[lang.code].includes"
                        rows="4"
                        placeholder="줄바꿈으로 구분&#10;예: 입장료&#10;가이드&#10;점심식사"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">불포함 사항</label>
                    <textarea
                        v-model="translations[lang.code].excludes"
                        rows="4"
                        placeholder="줄바꿈으로 구분&#10;예: 여행자 보험&#10;개인 경비"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- Copy Helper -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                <span class="font-medium">팁:</span>
                한국어 내용을 먼저 작성한 후 다른 언어로 번역하세요.
                다른 언어 필드를 비워두면 한국어가 기본값으로 사용됩니다.
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['update:modelValue'])

const translations = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
})

const languages = [
    { code: 'ko', label: '한국어', required: true },
    { code: 'en', label: 'English', required: false },
    { code: 'zh', label: '中文', required: false },
    { code: 'ja', label: '日本語', required: false },
]

const activeTab = ref('ko')
</script>
