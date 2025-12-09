<x-layouts.admin>
    <x-slot name="header">사용자 상세</x-slot>

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">사용자 관리</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">{{ $user->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-br from-blue-500 via-blue-600 to-violet-600 px-6 py-8 text-center">
                    <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-3xl font-bold mx-auto shadow-xl">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-blue-100">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-blue-200 text-sm mt-1">{{ $user->phone }}</p>
                    @endif
                </div>

                <!-- Profile Details -->
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">역할</span>
                            <div class="flex gap-1">
                                @foreach($user->roles as $role)
                                    @php
                                        $roleStyles = [
                                            'admin' => 'bg-purple-100 text-purple-700 ring-purple-500/20',
                                            'vendor' => 'bg-blue-100 text-blue-700 ring-blue-500/20',
                                            'guide' => 'bg-cyan-100 text-cyan-700 ring-cyan-500/20',
                                            'traveler' => 'bg-slate-100 text-slate-700 ring-slate-500/20',
                                        ];
                                        $roleNames = [
                                            'admin' => '관리자',
                                            'vendor' => '제공자',
                                            'guide' => '가이드',
                                            'traveler' => '여행자',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg ring-1 ring-inset {{ $roleStyles[$role->name] ?? 'bg-slate-100 text-slate-700 ring-slate-500/20' }}">
                                        {{ $roleNames[$role->name] ?? $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">상태</span>
                            @if($user->is_blocked)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 ring-1 ring-inset ring-red-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    차단
                                </span>
                            @elseif($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    활성
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    비활성
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">가입일</span>
                            <span class="text-sm font-medium text-slate-700">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">노쇼 횟수</span>
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg {{ $user->no_show_count >= 3 ? 'bg-red-100 text-red-700 ring-1 ring-inset ring-red-500/20' : 'bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20' }}">
                                {{ $user->no_show_count }}회
                            </span>
                        </div>
                        @if($user->provider)
                            <div class="flex items-center justify-between py-3">
                                <span class="text-sm text-slate-500">소셜 로그인</span>
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-700">
                                    @if($user->provider === 'google')
                                        <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                                    @elseif($user->provider === 'kakao')
                                        <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#3C1E1E" d="M12 3c-5.523 0-10 3.582-10 8 0 2.839 1.865 5.33 4.675 6.733l-.954 3.543a.5.5 0 00.764.547l4.135-2.757c.457.045.92.068 1.38.068 5.523 0 10-3.582 10-8s-4.477-8-10-8z"/></svg>
                                    @elseif($user->provider === 'naver')
                                        <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#03C75A" d="M16.273 12.845L7.376 0H0v24h7.727V11.155L16.624 24H24V0h-7.727z"/></svg>
                                    @endif
                                    {{ ucfirst($user->provider) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t border-slate-200 space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            수정
                        </a>

                        @if($user->is_blocked)
                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/25 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    차단 해제
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 {{ $user->is_active ? 'bg-gradient-to-r from-amber-500 to-amber-600 shadow-amber-500/25' : 'bg-gradient-to-r from-emerald-600 to-emerald-700 shadow-emerald-500/25' }} text-white rounded-xl hover:opacity-90 transition-all shadow-lg font-medium">
                                    @if($user->is_active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        비활성화
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        활성화
                                    @endif
                                </button>
                            </form>
                            @if(!$user->hasRole('admin'))
                                <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        차단하기
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vendor Info Card -->
            @if($user->vendor)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-800">제공자 정보</h3>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-slate-500">사업자명</span>
                            <p class="font-medium text-slate-800">{{ $user->vendor->business_name }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500">상태</span>
                            <p class="mt-1">
                                @if($user->vendor->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        승인됨
                                    </span>
                                @elseif($user->vendor->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        대기중
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 ring-1 ring-inset ring-red-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        거절됨
                                    </span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.vendors.show', $user->vendor) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium mt-2">
                            제공자 상세 보기
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bookings Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-800">최근 예약</h3>
                    </div>
                    <span class="text-sm text-slate-500">총 {{ $user->bookings->count() }}건</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/60">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">상품</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">예약일</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">상태</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">금액</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($user->bookings as $booking)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-800">
                                            {{ $booking->product->translations->where('locale', 'ko')->first()?->title ?? $booking->product->translations->where('locale', 'ko')->first()?->name ?? $booking->product->slug }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $booking->schedule?->date?->format('Y-m-d') ?? $booking->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusStyles = [
                                                'pending' => 'bg-amber-100 text-amber-700 ring-amber-500/20',
                                                'confirmed' => 'bg-blue-100 text-blue-700 ring-blue-500/20',
                                                'in_progress' => 'bg-indigo-100 text-indigo-700 ring-indigo-500/20',
                                                'completed' => 'bg-emerald-100 text-emerald-700 ring-emerald-500/20',
                                                'cancelled' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                                'no_show' => 'bg-red-100 text-red-700 ring-red-500/20',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg ring-1 ring-inset {{ $statusStyles[$booking->status->value] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20' }}">
                                            {{ $booking->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-medium text-slate-800">{{ number_format($booking->total_price) }}원</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-slate-500 font-medium">예약 내역이 없습니다.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mt-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            목록으로 돌아가기
        </a>
    </div>
</x-layouts.admin>
