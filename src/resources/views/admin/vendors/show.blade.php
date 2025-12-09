<x-layouts.admin>
    <x-slot name="header">제공자 상세</x-slot>

    <!-- Breadcrumb -->
    <nav class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.vendors.index') }}" class="text-slate-500 hover:text-blue-600 transition-colors">제공자 관리</a>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-medium">{{ $vendor->business_name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Vendor Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-br from-purple-500 via-purple-600 to-violet-600 px-6 py-8 text-center">
                    <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-3xl font-bold mx-auto shadow-xl">
                        {{ mb_substr($vendor->business_name, 0, 1) }}
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-white">{{ $vendor->business_name }}</h2>
                    <p class="text-purple-100">{{ $vendor->user->name }}</p>
                </div>

                <!-- Profile Details -->
                <div class="p-6">
                    @php
                        $statusValue = $vendor->status->value ?? $vendor->status;
                    @endphp
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">상태</span>
                            @if($statusValue === 'approved')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    승인됨
                                </span>
                            @elseif($statusValue === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    대기중
                                </span>
                            @elseif($statusValue === 'suspended')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-orange-100 text-orange-700 ring-1 ring-inset ring-orange-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                    정지됨
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 ring-1 ring-inset ring-red-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    거절됨
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">사업자번호</span>
                            <span class="text-sm font-medium text-slate-700">{{ $vendor->business_number ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">이메일</span>
                            <span class="text-sm font-medium text-slate-700">{{ $vendor->user->email }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-sm text-slate-500">연락처</span>
                            <span class="text-sm font-medium text-slate-700">{{ $vendor->phone ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-slate-500">신청일</span>
                            <span class="text-sm font-medium text-slate-700">{{ $vendor->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>

                    @if($vendor->description)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <h4 class="text-sm font-semibold text-slate-700 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                소개
                            </h4>
                            <p class="text-sm text-slate-600">{{ $vendor->description }}</p>
                        </div>
                    @endif

                    @if($vendor->rejection_reason)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                                <h4 class="text-sm font-semibold text-red-700 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    거절 사유
                                </h4>
                                <p class="text-sm text-red-600">{{ $vendor->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t border-slate-200 space-y-3">
                        <a href="{{ route('admin.vendors.edit', $vendor) }}"
                           class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/25 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            수정
                        </a>

                        @if($statusValue === 'pending')
                            <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/25 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    승인하기
                                </button>
                            </form>
                            <button type="button" onclick="openRejectModal()" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                거절하기
                            </button>
                        @elseif($statusValue === 'rejected')
                            <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/25 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    승인으로 변경
                                </button>
                            </form>
                        @elseif($statusValue === 'approved')
                            <form method="POST" action="{{ route('admin.vendors.suspend', $vendor) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl hover:from-amber-600 hover:to-amber-700 transition-all shadow-lg shadow-amber-500/25 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    정지하기
                                </button>
                            </form>
                        @elseif($statusValue === 'suspended')
                            <form method="POST" action="{{ route('admin.vendors.approve', $vendor) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/25 font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    정지 해제
                                </button>
                            </form>
                        @endif

                        @if($vendor->products()->count() === 0)
                            <button type="button" onclick="openDeleteModal()" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                제공자 삭제
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-800">등록 상품</h3>
                    </div>
                    <span class="text-sm text-slate-500">총 {{ $vendor->products->count() }}건</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/60">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">상품명</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">지역</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">상태</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">관리</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($vendor->products as $product)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-800">
                                            {{ $product->getTranslation('ko')?->title ?? $product->getTranslation('ko')?->name ?? '상품' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $product->region?->label() ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $productStatus = $product->status->value ?? $product->status;
                                        @endphp
                                        @if($productStatus === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                활성
                                            </span>
                                        @elseif($productStatus === 'pending_review')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                검토중
                                            </span>
                                        @elseif($productStatus === 'inactive')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                비활성
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                                {{ $productStatus }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                            상세
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                            <p class="text-slate-500 font-medium">등록된 상품이 없습니다.</p>
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
        <a href="{{ route('admin.vendors.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            목록으로 돌아가기
        </a>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">제공자 거절</h3>
                    <p class="text-sm text-slate-500">거절 사유를 입력해주세요.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.vendors.reject', $vendor) }}">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">거절 사유</label>
                    <textarea name="reason" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-colors"
                              placeholder="거절 사유를 입력하세요..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-500/25 font-medium">
                        거절
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">제공자 삭제</h3>
                    <p class="text-sm text-slate-500">이 작업은 되돌릴 수 없습니다.</p>
                </div>
            </div>
            <p class="text-slate-600 mb-6">정말 이 제공자를 삭제하시겠습니까?</p>
            <form method="POST" action="{{ route('admin.vendors.destroy', $vendor) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        취소
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-500/25 font-medium">
                        삭제
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</x-layouts.admin>
