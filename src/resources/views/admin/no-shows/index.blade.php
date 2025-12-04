<x-layouts.admin>
    <x-slot name="header">노쇼 관리</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- No-Show List -->
        <div class="lg:col-span-2">
            <div class="mb-4 bg-white rounded-lg shadow p-4">
                <form method="GET" action="{{ route('admin.no-shows.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="사용자 검색..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            검색
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold">노쇼 예약 목록</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">사용자</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상품</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">예약일</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">관리</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($noShows as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-medium">
                                            {{ mb_substr($booking->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                노쇼 {{ $booking->user->no_show_count }}회
                                                @if($booking->user->is_blocked)
                                                    <span class="px-1.5 py-0.5 text-xs rounded bg-red-100 text-red-800 ml-1">차단</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $booking->product->getTranslation('ko')?->title ?? '상품' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->booking_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('admin.users.show', $booking->user) }}" class="text-indigo-600 hover:text-indigo-900">
                                        사용자 상세
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    노쇼 예약이 없습니다.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($noShows->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $noShows->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Blocked Users -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-red-600">차단된 사용자</h3>
                    <p class="text-sm text-gray-500">노쇼 3회 이상으로 차단된 사용자</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($blockedUsers as $user)
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    <p class="text-sm text-red-600">노쇼 {{ $user->no_show_count }}회</p>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <form method="POST" action="{{ route('admin.no-shows.unblock', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm text-green-600 hover:text-green-900">
                                            차단해제
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.no-shows.reset', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-900">
                                            횟수초기화
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            차단된 사용자가 없습니다.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
