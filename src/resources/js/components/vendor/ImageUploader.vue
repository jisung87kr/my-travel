<template>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">이미지</h3>

        <!-- Existing Images -->
        <div v-if="existing.length > 0" class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-2">현재 이미지</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    v-for="(image, index) in existing"
                    :key="image.id"
                    class="relative group"
                    draggable="true"
                    @dragstart="onDragStart($event, index, 'existing')"
                    @dragover.prevent
                    @drop="onDrop($event, index, 'existing')"
                >
                    <img
                        :src="image.url"
                        :alt="`이미지 ${index + 1}`"
                        class="w-full h-32 object-cover rounded-lg cursor-move"
                    >
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg">
                        <button
                            type="button"
                            @click="deleteExisting(image.id)"
                            class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <span v-if="index === 0"
                              class="absolute bottom-2 left-2 px-2 py-1 bg-blue-500 text-white text-xs rounded">
                            대표 이미지
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Images Preview -->
        <div v-if="previews.length > 0" class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-2">새 이미지</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    v-for="(preview, index) in previews"
                    :key="index"
                    class="relative group"
                >
                    <img
                        :src="preview"
                        :alt="`새 이미지 ${index + 1}`"
                        class="w-full h-32 object-cover rounded-lg"
                    >
                    <button
                        type="button"
                        @click="removeNew(index)"
                        class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Upload Area -->
        <div
            @dragover.prevent="isDragging = true"
            @dragleave="isDragging = false"
            @drop.prevent="onFileDrop"
            :class="[
                'border-2 border-dashed rounded-lg p-8 text-center transition-colors',
                isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'
            ]"
        >
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="mt-2 text-sm text-gray-600">
                이미지를 드래그하거나 클릭하여 업로드
            </p>
            <p class="mt-1 text-xs text-gray-500">
                JPG, PNG 형식, 최대 10장
            </p>
            <input
                ref="fileInput"
                type="file"
                multiple
                accept="image/jpeg,image/png,image/webp"
                @change="onFileSelect"
                class="hidden"
            >
            <button
                type="button"
                @click="$refs.fileInput.click()"
                class="mt-4 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
            >
                파일 선택
            </button>
        </div>

        <!-- Help Text -->
        <p class="mt-2 text-xs text-gray-500">
            첫 번째 이미지가 대표 이미지로 사용됩니다. 드래그하여 순서를 변경할 수 있습니다.
        </p>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    existing: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['update:modelValue', 'delete-existing', 'reorder-existing'])

const isDragging = ref(false)
const previews = ref([])
const dragData = ref(null)

const files = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
})

function onFileSelect(event) {
    const selectedFiles = Array.from(event.target.files)
    addFiles(selectedFiles)
    event.target.value = '' // Reset input
}

function onFileDrop(event) {
    isDragging.value = false
    const droppedFiles = Array.from(event.dataTransfer.files).filter(
        file => file.type.startsWith('image/')
    )
    addFiles(droppedFiles)
}

function addFiles(newFiles) {
    const totalCount = props.existing.length + files.value.length + newFiles.length
    if (totalCount > 10) {
        alert('최대 10장까지 업로드할 수 있습니다.')
        return
    }

    newFiles.forEach(file => {
        files.value.push(file)
        const reader = new FileReader()
        reader.onload = (e) => {
            previews.value.push(e.target.result)
        }
        reader.readAsDataURL(file)
    })
}

function removeNew(index) {
    files.value.splice(index, 1)
    previews.value.splice(index, 1)
}

function deleteExisting(imageId) {
    emit('delete-existing', imageId)
}

function onDragStart(event, index, type) {
    dragData.value = { index, type }
    event.dataTransfer.effectAllowed = 'move'
}

function onDrop(event, targetIndex, targetType) {
    if (!dragData.value || dragData.value.type !== targetType) return

    const sourceIndex = dragData.value.index
    if (sourceIndex === targetIndex) return

    if (targetType === 'existing') {
        emit('reorder-existing', { from: sourceIndex, to: targetIndex })
    }

    dragData.value = null
}
</script>
