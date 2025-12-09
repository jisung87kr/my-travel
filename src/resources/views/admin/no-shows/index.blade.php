<x-layouts.admin>
    <x-slot name="header">노쇼 관리</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- No-Show List -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $noShows->total() }}</p>
                            <p class="text-sm text-slate-500">노쇼 예약</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $blockedUsers->count() }}</p>
                            <p class="text-sm text-slate-500">차단된 사용자</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
                <form method="GET" action="{{ route('admin.no-shows.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="사용자 검색..."
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-medium shadow-lg shadow-blue-500/30 transition-all">
                        검색
                    </button>
                </form>
            </div>

            <!-- No-Show Table -->
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-red-50 to-rose-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">노쇼 예약 목록</h3>
                            <p class="text-sm text-slate-500">노쇼로 처리된 예약 내역입니다</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">사용자</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">상품</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">예약일</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">관리</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($noShows as $booking)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-100 to-rose-100 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-bold text-red-600">{{ mb_substr($booking->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-slate-900">{{ $booking->user->name }}</div>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <span class="text-xs text-red-600 font-medium">노쇼 {{ $booking->user->no_show_count }}회</span>
                                                    @if($booking->user->is_blocked)
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ring-1 ring-inset ring-red-600/20">
                                                            <span class="w-1 h-1 rounded-full bg-red-500"></span>
                                                            차단
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $booking->product->getTranslation('ko')?->name ?? '상품' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-900">{{ $booking->schedule?->date?->format('Y-m-d') }}</div>
                                        <div class="text-xs text-slate-500">{{ $booking->schedule?->date?->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('admin.users.show', $booking->user) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            사용자 상세
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-slate-500 font-medium">노쇼 예약이 없습니다</p>
                                            <p class="text-sm text-slate-400 mt-1">모든 사용자가 예약을 잘 지켰습니다</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($noShows->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $noShows->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Blocked Users -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-800 to-slate-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">차단된 사용자</h3>
                            <p class="text-sm text-slate-300">노쇼 3회 이상</p>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-slate-100 max-h-[600px] overflow-y-auto">
                    @forelse($blockedUsers as $user)
                        <div class="p-5 hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-white">{{ mb_substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-500 truncate">{{ $user->email }}</p>
                                    <p class="text-sm text-red-600 font-medium mt-1">노쇼 {{ $user->no_show_count }}회</p>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <form method="POST" action="{{ route('admin.no-shows.unblock', $user) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full px-3 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-all flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        차단해제
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.no-shows.reset', $user) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        횟수초기화
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">차단된 사용자가 없습니다</p>
                            <p class="text-sm text-slate-400 mt-1">모든 사용자가 정상 상태입니다</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
