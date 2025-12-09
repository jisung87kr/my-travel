<x-layouts.guide>
    <x-slot name="header">QR 체크인</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- QR Scanner Section -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">QR 코드 스캔</h2>
            </div>
            <div class="p-6">
                <div id="qr-scanner-container" class="relative">
                    <div id="qr-video-container" class="aspect-square bg-slate-900 rounded-2xl overflow-hidden max-w-sm mx-auto">
                        <video id="qr-video" class="w-full h-full object-cover"></video>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-48 h-48 border-2 border-teal-400 rounded-2xl shadow-lg shadow-teal-400/20"></div>
                    </div>
                </div>
                <div class="mt-6 flex justify-center gap-3">
                    <button id="start-scanner"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-700 text-white text-sm font-medium rounded-xl hover:from-teal-700 hover:to-teal-800 shadow-lg shadow-teal-500/25 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        스캐너 시작
                    </button>
                    <button id="stop-scanner"
                            class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                        </svg>
                        스캐너 중지
                    </button>
                </div>
            </div>
        </div>

        <!-- Manual Input -->
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">예약 코드 직접 입력</h2>
            </div>
            <div class="p-6">
                <form id="manual-lookup-form" class="flex gap-3">
                    <input type="text" id="booking-code" name="code"
                           placeholder="예약 코드를 입력하세요"
                           class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-colors">
                    <button type="submit"
                            class="px-5 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition-colors">
                        조회
                    </button>
                </form>
            </div>
        </div>

        <!-- Booking Result -->
        <div id="booking-result" class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">예약 정보</h2>
            </div>
            <div class="p-6">
                <div id="booking-info" class="space-y-4">
                    <!-- Booking info will be injected here -->
                </div>
                <div class="mt-6 pt-6 border-t border-slate-100 flex gap-3">
                    <button id="confirm-checkin"
                            class="flex-1 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-emerald-600 hover:to-emerald-700 shadow-lg shadow-emerald-500/25 transition-all">
                        체크인 확인
                    </button>
                    <button id="cancel-checkin"
                            class="px-5 py-2.5 text-slate-600 text-sm font-medium hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                        취소
                    </button>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error-message" class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl hidden">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium" id="error-text"></span>
            </div>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl hidden">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium" id="success-text"></span>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentBookingId = null;

        // Manual lookup form
        document.getElementById('manual-lookup-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const code = document.getElementById('booking-code').value.trim();
            if (code) {
                await lookupBooking(code);
            }
        });

        // Lookup booking by code
        async function lookupBooking(code) {
            hideMessages();

            try {
                const response = await fetch(`{{ route('guide.checkin.lookup') }}?code=${encodeURIComponent(code)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showBookingInfo(data.booking);
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('예약 조회 중 오류가 발생했습니다.');
            }
        }

        // Show booking info
        function showBookingInfo(booking) {
            currentBookingId = booking.id;

            const statusLabels = {
                'confirmed': '확정',
                'in_progress': '진행중'
            };

            const statusColors = {
                'confirmed': 'bg-amber-100 text-amber-700',
                'in_progress': 'bg-blue-100 text-blue-700'
            };

            document.getElementById('booking-info').innerHTML = `
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">예약번호</span>
                    <span class="text-sm font-mono font-medium text-slate-900 bg-slate-100 px-2 py-0.5 rounded">${booking.booking_code}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">고객명</span>
                    <span class="text-sm font-medium text-slate-900">${booking.customer_name}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">상품</span>
                    <span class="text-sm font-medium text-slate-900">${booking.product_name}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">인원</span>
                    <span class="text-sm font-medium text-slate-900">성인 ${booking.adult_count || 0}명${booking.child_count > 0 ? ', 아동 ' + booking.child_count + '명' : ''}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                    <span class="text-sm text-slate-500">예약일</span>
                    <span class="text-sm font-medium text-slate-900">${booking.booking_date}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-slate-500">상태</span>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full ${statusColors[booking.status] || 'bg-slate-100 text-slate-600'}">${statusLabels[booking.status] || booking.status}</span>
                </div>
            `;

            document.getElementById('booking-result').classList.remove('hidden');
        }

        // Confirm checkin
        document.getElementById('confirm-checkin').addEventListener('click', async function() {
            if (!currentBookingId) return;

            try {
                const response = await fetch(`/guide/checkin/${currentBookingId}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    document.getElementById('booking-result').classList.add('hidden');
                    document.getElementById('booking-code').value = '';
                    currentBookingId = null;
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('체크인 처리 중 오류가 발생했습니다.');
            }
        });

        // Cancel
        document.getElementById('cancel-checkin').addEventListener('click', function() {
            document.getElementById('booking-result').classList.add('hidden');
            currentBookingId = null;
        });

        function showError(message) {
            const el = document.getElementById('error-message');
            document.getElementById('error-text').textContent = message;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 5000);
        }

        function showSuccess(message) {
            const el = document.getElementById('success-message');
            document.getElementById('success-text').textContent = message;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 5000);
        }

        function hideMessages() {
            document.getElementById('error-message').classList.add('hidden');
            document.getElementById('success-message').classList.add('hidden');
        }

        // QR Scanner (placeholder - requires @zxing/library)
        document.getElementById('start-scanner').addEventListener('click', function() {
            alert('QR 스캐너 기능은 @zxing/library 패키지 설치 후 사용 가능합니다.');
        });
    </script>
    @endpush
</x-layouts.guide>
