<x-layouts.guide>
    <x-slot name="header">QR 체크인</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- QR Scanner Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="font-semibold mb-4">QR 코드 스캔</h3>
            <div id="qr-scanner-container" class="relative">
                <div id="qr-video-container" class="aspect-square bg-gray-900 rounded-lg overflow-hidden">
                    <video id="qr-video" class="w-full h-full object-cover"></video>
                </div>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-48 border-2 border-teal-400 rounded-lg"></div>
                </div>
            </div>
            <div class="mt-4 flex justify-center gap-4">
                <button id="start-scanner" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                    스캐너 시작
                </button>
                <button id="stop-scanner" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 hidden">
                    스캐너 중지
                </button>
            </div>
        </div>

        <!-- Manual Input -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="font-semibold mb-4">예약 코드 직접 입력</h3>
            <form id="manual-lookup-form" class="flex gap-4">
                <input type="text" id="booking-code" name="code"
                       placeholder="예약 코드를 입력하세요"
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                    조회
                </button>
            </form>
        </div>

        <!-- Booking Result -->
        <div id="booking-result" class="bg-white rounded-lg shadow p-6 hidden">
            <h3 class="font-semibold mb-4">예약 정보</h3>
            <div id="booking-info" class="space-y-3">
                <!-- Booking info will be injected here -->
            </div>
            <div class="mt-6 flex gap-4">
                <button id="confirm-checkin" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    체크인 확인
                </button>
                <button id="cancel-checkin" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                    취소
                </button>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden">
        </div>

        <!-- Success Message -->
        <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden">
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

            document.getElementById('booking-info').innerHTML = `
                <div class="flex justify-between">
                    <span class="text-gray-500">예약번호</span>
                    <span class="font-mono">${booking.booking_code}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">고객명</span>
                    <span>${booking.customer_name}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">상품</span>
                    <span>${booking.product_name}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">인원</span>
                    <span>${booking.quantity}명 (성인 ${booking.adult_count || 0}, 아동 ${booking.child_count || 0})</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">예약일</span>
                    <span>${booking.booking_date}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">상태</span>
                    <span>${statusLabels[booking.status] || booking.status}</span>
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
            el.textContent = message;
            el.classList.remove('hidden');
        }

        function showSuccess(message) {
            const el = document.getElementById('success-message');
            el.textContent = message;
            el.classList.remove('hidden');
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
