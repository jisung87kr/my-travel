<x-layouts.admin>
    <x-slot name="header">사용자 상세 - {{ $user->name }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold mx-auto">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <h2 class="mt-4 text-xl font-semibold">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">역할</span>
                        <div class="flex gap-1">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">상태</span>
                        <span>
                            @if($user->is_blocked)
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">차단</span>
                            @elseif($user->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">활성</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">비활성</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">가입일</span>
                        <span>{{ $user->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">노쇼 횟수</span>
                        <span class="{{ $user->no_show_count >= 3 ? 'text-red-600 font-medium' : '' }}">
                            {{ $user->no_show_count }}회
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    @if($user->is_blocked)
                        <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                차단 해제
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 {{ $user->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg">
                                {{ $user->is_active ? '비활성화' : '활성화' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.block', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                차단하기
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if($user->vendor)
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h3 class="font-semibold mb-4">제공자 정보</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-500 text-sm">사업자명</span>
                            <p class="font-medium">{{ $user->vendor->business_name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">상태</span>
                            <p>
                                @if($user->vendor->status === 'approved')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">승인됨</span>
                                @elseif($user->vendor->status === 'pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">대기중</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">거절됨</span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.vendors.show', $user->vendor) }}" class="text-indigo-600 hover:underline text-sm">
                            제공자 상세 &rarr;
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bookings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold">최근 예약</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상품</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">예약일</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">상태</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">금액</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($user->bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $booking->product->getTranslation('ko')?->title ?? '상품' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->booking_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $booking->status->color() }}">
                                            {{ $booking->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($booking->total_amount) }}원
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        예약 내역이 없습니다.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:underline">&larr; 목록으로</a>
    </div>
</x-layouts.admin>
