<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Exceptions\BookingExpiredException;
use App\Exceptions\BookingNotAllowedException;
use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\InvalidBookingStatusException;
use App\Models\Booking;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(
        private readonly InventoryService $inventoryService
    ) {}

    public function create(array $data, User $user): Booking
    {
        if ($user->is_blocked) {
            throw new BookingNotAllowedException('노쇼 횟수 초과로 예약이 제한되었습니다.');
        }

        if (!$user->is_active) {
            throw new BookingNotAllowedException('비활성화된 계정입니다.');
        }

        $date = $data['date'];
        if (strtotime($date) < strtotime(today())) {
            throw new BookingExpiredException();
        }

        return DB::transaction(function () use ($data, $user) {
            $totalPersons = ($data['adult_count'] ?? 0) + ($data['child_count'] ?? 0) + ($data['infant_count'] ?? 0);

            $schedule = $this->inventoryService->checkAvailability(
                $data['product_id'],
                $data['date'],
                $totalPersons
            );

            if (!$schedule) {
                throw new InsufficientInventoryException();
            }

            $product = Product::findOrFail($data['product_id']);
            $totalPrice = $this->calculatePrice($product, $data);

            $booking = Booking::create([
                'product_id' => $data['product_id'],
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'adult_count' => $data['adult_count'] ?? 0,
                'child_count' => $data['child_count'] ?? 0,
                'infant_count' => $data['infant_count'] ?? 0,
                'total_price' => $totalPrice,
                'special_request' => $data['special_request'] ?? null,
                'contact_name' => $data['contact_name'] ?? $user->name,
                'contact_phone' => $data['contact_phone'] ?? $user->phone ?? '',
                'contact_email' => $data['contact_email'] ?? $user->email,
                'status' => $this->determineInitialStatus($product),
            ]);

            if ($booking->status === BookingStatus::CONFIRMED) {
                $booking->update(['confirmed_at' => now()]);
            }

            $this->inventoryService->decreaseStock($schedule, $totalPersons);

            return $booking->load(['product.translations', 'schedule', 'user']);
        });
    }

    public function approve(Booking $booking): Booking
    {
        if ($booking->status !== BookingStatus::PENDING) {
            throw new InvalidBookingStatusException('대기 중인 예약만 승인할 수 있습니다.');
        }

        $booking->confirm();

        return $booking->fresh(['product.translations', 'schedule', 'user']);
    }

    public function reject(Booking $booking, ?string $reason = null): Booking
    {
        if ($booking->status !== BookingStatus::PENDING) {
            throw new InvalidBookingStatusException('대기 중인 예약만 거절할 수 있습니다.');
        }

        return DB::transaction(function () use ($booking, $reason) {
            $booking->update([
                'status' => BookingStatus::CANCELLED,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason ?? '제공자에 의해 거절됨',
            ]);

            $this->inventoryService->increaseStock(
                $booking->schedule,
                $booking->total_persons
            );

            return $booking->fresh(['product.translations', 'schedule', 'user']);
        });
    }

    public function cancel(Booking $booking, User $cancelledBy, ?string $reason = null): Booking
    {
        if (!$booking->canBeCancelled()) {
            throw new InvalidBookingStatusException('취소할 수 없는 예약입니다.');
        }

        return DB::transaction(function () use ($booking, $cancelledBy, $reason) {
            $booking->cancel($cancelledBy, $reason);

            return $booking->fresh(['product.translations', 'schedule', 'user']);
        });
    }

    public function complete(Booking $booking): Booking
    {
        if ($booking->status !== BookingStatus::CONFIRMED) {
            throw new InvalidBookingStatusException('확정된 예약만 완료 처리할 수 있습니다.');
        }

        $booking->complete();

        return $booking->fresh(['product.translations', 'schedule', 'user']);
    }

    public function markNoShow(Booking $booking): Booking
    {
        if ($booking->status !== BookingStatus::CONFIRMED) {
            throw new InvalidBookingStatusException('확정된 예약만 노쇼 처리할 수 있습니다.');
        }

        return DB::transaction(function () use ($booking) {
            $booking->markAsNoShow();

            return $booking->fresh(['product.translations', 'schedule', 'user']);
        });
    }

    public function getUserBookings(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Booking::with(['product.translations', 'product.images', 'schedule'])
            ->where('user_id', $user->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('created_at', 'desc');

        $perPage = min($filters['per_page'] ?? 20, 100);

        return $query->paginate($perPage);
    }

    public function getVendorBookings(User $vendor, array $filters = []): LengthAwarePaginator
    {
        $query = Booking::with(['product.translations', 'schedule', 'user'])
            ->whereHas('product', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->vendor->id);
            });

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['date'])) {
            $query->whereHas('schedule', function ($q) use ($filters) {
                $q->where('date', $filters['date']);
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereHas('schedule', function ($q) use ($filters) {
                $q->where('date', '>=', $filters['date_from']);
            });
        }

        if (!empty($filters['date_to'])) {
            $query->whereHas('schedule', function ($q) use ($filters) {
                $q->where('date', '<=', $filters['date_to']);
            });
        }

        $query->orderBy('created_at', 'desc');

        $perPage = min($filters['per_page'] ?? 20, 100);

        return $query->paginate($perPage);
    }

    private function calculatePrice(Product $product, array $data): int
    {
        $product->load('prices');

        $totalPrice = 0;

        $adultPrice = $product->prices->where('type', 'adult')->where('is_active', true)->first();
        if ($adultPrice && ($data['adult_count'] ?? 0) > 0) {
            $totalPrice += $adultPrice->price * $data['adult_count'];
        }

        $childPrice = $product->prices->where('type', 'child')->where('is_active', true)->first();
        if ($childPrice && ($data['child_count'] ?? 0) > 0) {
            $totalPrice += $childPrice->price * $data['child_count'];
        }

        $infantPrice = $product->prices->where('type', 'infant')->where('is_active', true)->first();
        if ($infantPrice && ($data['infant_count'] ?? 0) > 0) {
            $totalPrice += $infantPrice->price * $data['infant_count'];
        }

        return $totalPrice;
    }

    private function determineInitialStatus(Product $product): BookingStatus
    {
        return $product->booking_type === BookingType::INSTANT
            ? BookingStatus::CONFIRMED
            : BookingStatus::PENDING;
    }
}
