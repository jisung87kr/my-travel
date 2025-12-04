@props(['review', 'product' => null, 'showReplyForm' => false])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-600 font-medium">{{ mb_substr($review->user->name, 0, 1) }}</span>
            </div>
            <div class="ml-3">
                <p class="font-medium">{{ $review->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $review->created_at->format('Y.m.d') }}</p>
            </div>
        </div>
        <div class="flex items-center text-yellow-400">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                </svg>
            @endfor
        </div>
    </div>

    <p class="text-gray-700 mb-4">{{ $review->content }}</p>

    @if($review->images->count() > 0)
        <div class="flex gap-2 mb-4 flex-wrap">
            @foreach($review->images as $image)
                <img src="{{ Storage::url($image->path) }}" alt=""
                     class="w-20 h-20 rounded-lg object-cover cursor-pointer hover:opacity-80"
                     onclick="window.open('{{ Storage::url($image->path) }}', '_blank')">
            @endforeach
        </div>
    @endif

    @if($review->vendor_reply)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <span class="text-sm font-medium text-emerald-600">판매자 답변</span>
                    <span class="text-sm text-gray-500 ml-2">{{ $review->replied_at?->format('Y.m.d') }}</span>
                </div>
                <p class="text-gray-700">{{ $review->vendor_reply }}</p>
            </div>
        </div>
    @elseif($showReplyForm)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('vendor.reviews.reply', $review) }}">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="reply" placeholder="답변을 입력하세요..."
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        답변
                    </button>
                </div>
            </form>
        </div>
    @endif

    @auth
        @if($review->user_id !== auth()->id())
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="openReportModal({{ $review->id }})"
                        class="text-sm text-gray-500 hover:text-red-600">
                    신고하기
                </button>
            </div>
        @endif
    @endauth
</div>
