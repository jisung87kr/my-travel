<x-layouts.vendor>
    <x-slot name="header">일정 관리</x-slot>

    <div id="schedule-calendar-container"
         data-products="{{ json_encode($products) }}"
         data-csrf-token="{{ csrf_token() }}">
    </div>

    <!-- Fallback for non-JS -->
    <noscript>
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <h2 class="text-sm font-semibold text-slate-900 mb-4">상품 선택</h2>

            <form method="GET" action="{{ route('vendor.schedules.index') }}" class="mb-6">
                <select name="product" onchange="this.form.submit()" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-colors">
                    <option value="">상품을 선택하세요</option>
                    @foreach($products as $product)
                        <option value="{{ $product['id'] }}" {{ request('product') == $product['id'] ? 'selected' : '' }}>
                            {{ $product['title'] }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if(request('product') && isset($schedules))
                <h3 class="text-sm font-semibold text-slate-900 mb-4">일정 목록</h3>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">날짜</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">총 재고</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">가용 재고</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">상태</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($schedules as $schedule)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-900">{{ $schedule['date'] }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-900">{{ $schedule['total_count'] }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-900">{{ $schedule['available_count'] }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full {{ $schedule['is_active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $schedule['is_active'] ? '예약가능' : '마감' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                            </svg>
                                        </div>
                                        <p class="text-slate-500">등록된 일정이 없습니다</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 mb-2">일정을 관리할 상품을 선택하세요</p>
                    <p class="text-xs text-slate-400">상품을 선택하면 날짜별 재고를 설정할 수 있습니다</p>
                </div>
            @endif
        </div>
    </noscript>
</x-layouts.vendor>
