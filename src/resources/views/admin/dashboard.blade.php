<x-layouts.admin>
    <x-slot name="header">대시보드</x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">전체 사용자</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['total_users']) }}</p>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            +{{ $stats['new_users_this_month'] }}
                        </span>
                        <span class="text-xs text-slate-400">이번 달</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
            </div>
            @if($stats['blocked_users'] > 0)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-xs text-red-600 font-medium">{{ $stats['blocked_users'] }}명 차단됨</span>
                </div>
            @endif
        </div>

        <!-- Vendors -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">제공자</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['approved_vendors']) }}</p>
                    @if($stats['pending_vendors'] > 0)
                        <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}"
                           class="inline-flex items-center gap-1 mt-3 text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            {{ $stats['pending_vendors'] }}건 승인 대기
                        </a>
                    @else
                        <p class="text-xs text-slate-400 mt-3">모든 제공자 승인됨</p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">활성 상품</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['active_products']) }}</p>
                    @if($stats['pending_products'] > 0)
                        <a href="{{ route('admin.products.index', ['status' => 'pending_review']) }}"
                           class="inline-flex items-center gap-1 mt-3 text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            {{ $stats['pending_products'] }}건 검토 대기
                        </a>
                    @else
                        <p class="text-xs text-slate-400 mt-3">모든 상품 검토 완료</p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 hover:shadow-lg hover:shadow-slate-200/50 transition-shadow duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">이번 달 매출</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($stats['monthly_revenue'] / 10000) }}<span class="text-lg font-medium text-slate-400">만원</span></p>
                    <p class="text-xs text-slate-400 mt-3">총 매출: {{ number_format($stats['total_revenue']) }}원</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Stats -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-6 mb-8">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">예약 현황</h3>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <div class="text-center p-4 rounded-xl bg-slate-50">
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_bookings']) }}</p>
                <p class="text-xs font-medium text-slate-500 mt-1">전체</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-amber-50">
                <p class="text-2xl font-bold text-amber-600">{{ number_format($stats['pending_bookings']) }}</p>
                <p class="text-xs font-medium text-slate-500 mt-1">대기중</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-emerald-50">
                <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['completed_bookings']) }}</p>
                <p class="text-xs font-medium text-slate-500 mt-1">완료</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-slate-50">
                <p class="text-2xl font-bold text-slate-500">{{ number_format($stats['cancelled_bookings']) }}</p>
                <p class="text-xs font-medium text-slate-500 mt-1">취소</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-red-50">
                <p class="text-2xl font-bold text-red-600">{{ number_format($stats['no_show_bookings']) }}</p>
                <p class="text-xs font-medium text-slate-500 mt-1">노쇼</p>
            </div>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Vendors -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">승인 대기 제공자</h2>
                <a href="{{ route('admin.vendors.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    전체보기
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($pendingVendors as $vendor)
                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="block px-6 py-4 hover:bg-slate-50 transition-colors cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white font-semibold text-sm shadow-lg shadow-violet-500/20">
                                {{ mb_substr($vendor->company_name ?? $vendor->business_name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $vendor->company_name ?? $vendor->business_name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $vendor->user->name }} · {{ $vendor->user->email }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">대기중인 제공자가 없습니다</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Products -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">검토 대기 상품</h2>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    전체보기
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($pendingProducts as $product)
                    <a href="{{ route('admin.products.show', $product) }}" class="block px-6 py-4 hover:bg-slate-50 transition-colors cursor-pointer">
                        <div class="flex items-center gap-3">
                            @if($product->images->first())
                                <img src="{{ $product->images->first()->url }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $product->getTranslation('ko')?->name ?? $product->getTranslation('ko')?->title ?? '상품' }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $product->vendor->user->name }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">대기중인 상품이 없습니다</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">최근 예약</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    전체보기
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentBookings->take(5) as $booking)
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="block px-6 py-4 hover:bg-slate-50 transition-colors cursor-pointer">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $booking->product->getTranslation('ko')?->name ?? $booking->product->getTranslation('ko')?->title ?? '상품' }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->user->name }} · {{ $booking->created_at->format('m/d H:i') }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'in_progress' => 'bg-indigo-100 text-indigo-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-slate-100 text-slate-600',
                                    'no_show' => 'bg-red-100 text-red-700',
                                ];
                                $statusValue = $booking->status->value ?? $booking->status;
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$statusValue] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">예약이 없습니다</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
