@props(['booking'])

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">리뷰 작성</h3>

    <form method="POST" action="{{ route('reviews.store', $booking) }}" enctype="multipart/form-data">
        @csrf

        <!-- Star Rating -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">평점</label>
            <div class="flex items-center gap-1" x-data="{ rating: 0, hoverRating: 0 }">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            @click="rating = {{ $i }}"
                            @mouseenter="hoverRating = {{ $i }}"
                            @mouseleave="hoverRating = 0"
                            class="text-2xl transition-colors"
                            :class="(hoverRating >= {{ $i }} || (!hoverRating && rating >= {{ $i }})) ? 'text-yellow-400' : 'text-gray-300'">
                        <svg class="w-8 h-8 fill-current" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    </button>
                @endfor
                <input type="hidden" name="rating" x-model="rating" required>
            </div>
            @error('rating')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">리뷰 내용</label>
            <textarea name="content" id="content" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                      placeholder="상품 이용 경험을 공유해 주세요. (최소 10자)"
                      required minlength="10" maxlength="1000">{{ old('content') }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Upload -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">사진 첨부 (최대 5장)</label>
            <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <p class="mt-1 text-sm text-gray-500">JPEG, PNG 형식, 파일당 최대 5MB</p>
            @error('images')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
            리뷰 등록
        </button>
    </form>
</div>
