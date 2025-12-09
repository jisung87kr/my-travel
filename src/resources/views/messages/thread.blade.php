<x-layouts.app title="메시지">
    <div class="bg-gray-50 min-h-screen flex flex-col">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex-1 flex flex-col w-full">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('messages.index') }}"
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-pink-600 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span class="text-sm font-medium">대화 목록</span>
                </a>
            </div>

            <!-- Booking Info Card -->
            <div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $booking->product->slug ?? $booking->product->id]) }}"
                   class="flex items-center gap-4 cursor-pointer group">
                    @if($booking->product->images->first())
                        <div class="relative w-16 h-16 rounded-xl overflow-hidden flex-shrink-0">
                            <img src="{{ $booking->product->images->first()->url }}" alt=""
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 group-hover:text-pink-600 transition-colors truncate">
                            {{ $booking->product->getTranslation('ko')?->title ?? '상품' }}
                        </h3>
                        <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                </svg>
                                {{ $booking->booking_code }}
                            </span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                {{ $booking->schedule?->date?->format('Y.m.d') }}
                            </span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <!-- Chat Container -->
            <div class="bg-white rounded-2xl shadow-sm flex-1 flex flex-col overflow-hidden">
                <!-- Chat Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    @php
                        $otherUser = $booking->traveler_id === auth()->id() ? $booking->vendor : $booking->traveler;
                    @endphp
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-semibold">
                        {{ mb_substr($otherUser->name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">{{ $otherUser->name ?? '가이드' }}</h2>
                        <p class="text-xs text-gray-500">체험 담당 가이드</p>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="message-container" class="flex-1 overflow-y-auto p-6 space-y-4 max-h-[500px]">
                    @forelse($messages as $message)
                        @php
                            $isMine = $message->sender_id === auth()->id();
                        @endphp
                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] sm:max-w-md">
                                @unless($isMine)
                                    <p class="text-xs text-gray-500 mb-1.5 ml-1">{{ $message->sender->name }}</p>
                                @endunless
                                <div class="px-4 py-3 rounded-2xl {{ $isMine ? 'bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-br-md' : 'bg-gray-100 text-gray-900 rounded-bl-md' }}">
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $message->content }}</p>
                                </div>
                                <div class="flex items-center gap-2 mt-1.5 {{ $isMine ? 'justify-end mr-1' : 'ml-1' }}">
                                    <span class="text-xs text-gray-500">
                                        {{ $message->created_at->format('H:i') }}
                                    </span>
                                    @if($isMine && $message->read_at)
                                        <span class="text-xs text-pink-500 font-medium">읽음</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-center py-12">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                </svg>
                            </div>
                            <p class="text-gray-500">아직 메시지가 없습니다.</p>
                            <p class="text-sm text-gray-400 mt-1">첫 메시지를 보내보세요!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="px-6 py-4 border-t border-gray-100 bg-white">
                    <form method="POST" action="{{ route('messages.store', $booking) }}" class="flex items-end gap-3">
                        @csrf
                        <div class="flex-1 relative">
                            <textarea name="content"
                                      placeholder="메시지를 입력하세요..."
                                      rows="1"
                                      required
                                      maxlength="2000"
                                      class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-colors resize-none"
                                      style="min-height: 48px; max-height: 120px;"
                                      oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>
                        <button type="submit"
                                class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('message-container');
            container.scrollTop = container.scrollHeight;

            // Auto-resize textarea
            const textarea = document.querySelector('textarea[name="content"]');
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.value.trim()) {
                        this.closest('form').submit();
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
