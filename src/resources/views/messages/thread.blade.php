<x-layouts.app>
    <x-slot name="header">메시지</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <!-- Booking Info -->
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <div class="flex items-center gap-4">
                @if($booking->product->images->first())
                    <img src="{{ $booking->product->images->first()->url }}" alt=""
                         class="w-16 h-16 rounded-lg object-cover">
                @endif
                <div>
                    <h3 class="font-medium">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</h3>
                    <p class="text-sm text-gray-500">
                        예약번호: {{ $booking->booking_code }} ·
                        {{ $booking->booking_date->format('Y.m.d') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">대화</h2>
            </div>

            <div id="message-container" class="p-6 h-96 overflow-y-auto space-y-4">
                @forelse($messages as $message)
                    @php
                        $isMine = $message->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            @unless($isMine)
                                <p class="text-xs text-gray-500 mb-1">{{ $message->sender->name }}</p>
                            @endunless
                            <div class="px-4 py-2 rounded-lg {{ $isMine ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                <p>{{ $message->content }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 {{ $isMine ? 'text-right' : '' }}">
                                {{ $message->created_at->format('H:i') }}
                                @if($isMine && $message->read_at)
                                    · 읽음
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500">
                        아직 메시지가 없습니다. 첫 메시지를 보내보세요!
                    </div>
                @endforelse
            </div>

            <!-- Input -->
            <div class="px-6 py-4 border-t border-gray-200">
                <form method="POST" action="{{ route('messages.store', $booking) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="content" placeholder="메시지를 입력하세요..."
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"
                           required maxlength="2000" autofocus>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        전송
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('messages.index') }}" class="text-emerald-600 hover:underline">&larr; 목록으로</a>
        </div>
    </div>

    <script>
        // Auto scroll to bottom
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('message-container');
            container.scrollTop = container.scrollHeight;
        });
    </script>
</x-layouts.app>
