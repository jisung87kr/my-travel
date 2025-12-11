<x-layouts.admin>
    <x-slot name="header">예약 관리</x-slot>

    <div class="space-y-6" id="app">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] ?? $bookings->total() }}</p>
                        <p class="text-sm text-slate-500">전체 예약</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">대기중</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['confirmed'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">확정</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['completed'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">완료</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['cancelled'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">취소/노쇼</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="사용자 또는 상품 검색..."
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                    </div>
                </div>
                <div>
                    <select name="status" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all min-w-[140px]">
                        <option value="">전체 상태</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>대기중</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>확정</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>완료</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>취소</option>
                        <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>노쇼</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                    <span class="text-slate-400">~</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-medium shadow-lg shadow-blue-500/30 transition-all">
                        검색
                    </button>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">예약번호</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">사용자</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">상품</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">예약일</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">금액</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">상태</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">관리</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono font-medium text-slate-900 bg-slate-100 px-2 py-1 rounded-lg">{{ $booking->booking_number ?? '#'.$booking->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold text-blue-600">{{ mb_substr($booking->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-slate-900">{{ $booking->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $booking->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $booking->product->getTranslation('ko')?->name ?? '상품' }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->product->vendor->company_name ?? $booking->product->vendor->business_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $booking->schedule?->date?->format('Y-m-d') }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->schedule?->date?->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-slate-900">{{ number_format($booking->total_amount) }}원</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusValue = $booking->status->value ?? $booking->status;
                                    @endphp
                                    @if($statusValue === 'confirmed')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'cancelled')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @elseif($statusValue === 'no_show')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            {{ $booking->status->label() }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            상세
                                        </a>
                                        @if(!in_array($statusValue, ['cancelled', 'no_show', 'completed']))
                                            <button type="button" @click="handleCancel('{{ $booking->id }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                취소
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">예약이 없습니다</p>
                                        <p class="text-sm text-slate-400 mt-1">검색 조건을 변경해보세요</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($bookings->hasPages())
            <div class="flex justify-center">
                {{ $bookings->withQueryString()->links() }}
            </div>
        @endif

        <toast-container></toast-container>
        <modal-container></modal-container>
    </div>
    <script type="module">
        createVueApp({
            data() {
                return {};
            },
            methods: {
                handleCancel(bookingId) {
                    this.$modal.confirm({
                        title: '예약 취소',
                        description: '정말 이 예약을 취소하시겠습니까? 이 작업은 되돌릴 수 없습니다.',
                        confirmText: '예약 취소',
                        cancelText: '닫기',
                        variant: 'danger',
                        icon: 'x',
                    }).then((confirm) => {
                        if(confirm){
                            api.bookings.cancel(bookingId).then(() => {
                                this.$toast.success('예약이 취소되었습니다.');
                                setTimeout(() => {
                                    location.reload();
                                }, 500);

                            });
                        } else {
                            this.$toast.info('취소되었습니다.');
                        }
                    });
                },
            },
        }).mount('#app');
    </script>
</x-layouts.admin>
