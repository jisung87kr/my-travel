<x-layouts.app title="메시지">
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center shadow-lg shadow-pink-500/25">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">메시지</h1>
                        <p class="text-sm text-gray-500">가이드와의 대화 목록</p>
                    </div>
                </div>
                @if($unreadCount > 0)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-pink-100 text-pink-700">
                        <span class="w-2 h-2 bg-pink-500 rounded-full animate-pulse"></span>
                        {{ $unreadCount }}개 읽지 않음
                    </span>
                @endif
            </div>

            <!-- Conversation List -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                @forelse($conversations as $message)
                    @php
                        $isUnread = $message->receiver_id === auth()->id() && !$message->read_at;
                        $otherUser = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
                    @endphp
                    <a href="{{ route('messages.thread', $message->booking) }}"
                       class="block px-6 py-5 hover:bg-gray-50 transition-colors cursor-pointer {{ $isUnread ? 'bg-pink-50/50' : '' }} {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex items-start gap-4">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-semibold text-lg {{ $isUnread ? 'ring-2 ring-pink-500 ring-offset-2' : '' }}">
                                    {{ mb_substr($otherUser->name, 0, 1) }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900 {{ $isUnread ? 'text-pink-900' : '' }}">
                                            {{ $otherUser->name }}
                                        </span>
                                        @if($isUnread)
                                            <span class="w-2.5 h-2.5 bg-pink-500 rounded-full animate-pulse"></span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500 flex-shrink-0">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-1.5">
                                    {{ $message->booking->product->getTranslation('ko')?->title ?? '상품' }}
                                </p>
                                <p class="text-sm text-gray-500 line-clamp-1 {{ $isUnread ? 'font-medium text-gray-700' : '' }}">
                                    {{ $message->sender_id === auth()->id() ? '나: ' : '' }}{{ $message->content }}
                                </p>
                            </div>

                            <!-- Arrow -->
                            <div class="flex-shrink-0 self-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">아직 메시지가 없습니다</h3>
                        <p class="text-gray-500 mb-6">체험을 예약하면 가이드와 대화할 수 있어요!</p>
                        <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-semibold rounded-xl shadow-lg shadow-pink-500/25 hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            체험 둘러보기
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
