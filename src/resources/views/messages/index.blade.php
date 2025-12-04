<x-layouts.app>
    <x-slot name="header">메시지</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold">대화 목록</h2>
                @if($unreadCount > 0)
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">
                        {{ $unreadCount }}개 읽지 않음
                    </span>
                @endif
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($conversations as $message)
                    @php
                        $isUnread = $message->receiver_id === auth()->id() && !$message->read_at;
                        $otherUser = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
                    @endphp
                    <a href="{{ route('messages.thread', $message->booking) }}"
                       class="block px-6 py-4 hover:bg-gray-50 {{ $isUnread ? 'bg-emerald-50' : '' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium {{ $isUnread ? 'text-emerald-900' : '' }}">
                                        {{ $otherUser->name }}
                                    </span>
                                    @if($isUnread)
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $message->booking->product->getTranslation('ko')?->title ?? '상품' }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-1">
                                    {{ $message->content }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $message->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        아직 메시지가 없습니다.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
