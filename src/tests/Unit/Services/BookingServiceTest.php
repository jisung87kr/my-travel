<?php

namespace Tests\Unit\Services;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Exceptions\BookingExpiredException;
use App\Exceptions\BookingNotAllowedException;
use App\Exceptions\InsufficientInventoryException;
use App\Exceptions\InvalidBookingStatusException;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductSchedule;
use App\Models\User;
use App\Models\Vendor;
use App\Services\BookingService;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $service;
    private User $traveler;
    private Product $product;
    private ProductSchedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->service = new BookingService(new InventoryService());

        $this->traveler = User::factory()->create();
        $this->traveler->assignRole('traveler');

        $vendor = Vendor::factory()->create();
        $this->product = Product::factory()->active()->instant()->create([
            'vendor_id' => $vendor->id,
        ]);

        ProductPrice::factory()->adult()->create([
            'product_id' => $this->product->id,
            'price' => 50000,
        ]);

        $this->schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 10,
            'is_active' => true,
        ]);
    }

    public function test_create_instant_booking(): void
    {
        $data = [
            'product_id' => $this->product->id,
            'date' => today()->addDay()->toDateString(),
            'adult_count' => 2,
            'child_count' => 0,
            'infant_count' => 0,
        ];

        $booking = $this->service->create($data, $this->traveler);

        $this->assertEquals(BookingStatus::CONFIRMED, $booking->status);
        $this->assertEquals(100000, $booking->total_price);
        $this->assertEquals(8, $this->schedule->fresh()->available_count);
    }

    public function test_create_request_booking(): void
    {
        $this->product->update(['booking_type' => BookingType::REQUEST]);

        $data = [
            'product_id' => $this->product->id,
            'date' => today()->addDay()->toDateString(),
            'adult_count' => 2,
        ];

        $booking = $this->service->create($data, $this->traveler);

        $this->assertEquals(BookingStatus::PENDING, $booking->status);
    }

    public function test_create_booking_fails_for_blocked_user(): void
    {
        $this->traveler->update(['is_blocked' => true]);

        $data = [
            'product_id' => $this->product->id,
            'date' => today()->addDay()->toDateString(),
            'adult_count' => 1,
        ];

        $this->expectException(BookingNotAllowedException::class);
        $this->service->create($data, $this->traveler);
    }

    public function test_create_booking_fails_for_past_date(): void
    {
        $data = [
            'product_id' => $this->product->id,
            'date' => today()->subDay()->toDateString(),
            'adult_count' => 1,
        ];

        $this->expectException(BookingExpiredException::class);
        $this->service->create($data, $this->traveler);
    }

    public function test_create_booking_fails_for_insufficient_inventory(): void
    {
        $data = [
            'product_id' => $this->product->id,
            'date' => today()->addDay()->toDateString(),
            'adult_count' => 20,
        ];

        $this->expectException(InsufficientInventoryException::class);
        $this->service->create($data, $this->traveler);
    }

    public function test_approve_pending_booking(): void
    {
        $this->product->update(['booking_type' => BookingType::REQUEST]);
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::PENDING,
        ]);

        $booking = $this->service->approve($booking);

        $this->assertEquals(BookingStatus::CONFIRMED, $booking->status);
        $this->assertNotNull($booking->confirmed_at);
    }

    public function test_approve_non_pending_booking_fails(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $this->expectException(InvalidBookingStatusException::class);
        $this->service->approve($booking);
    }

    public function test_reject_pending_booking(): void
    {
        $this->schedule->update(['available_count' => 8]);

        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::PENDING,
            'adult_count' => 2,
            'child_count' => 0,
            'infant_count' => 0,
        ]);

        $booking = $this->service->reject($booking, '일정 변경');

        $this->assertEquals(BookingStatus::CANCELLED, $booking->status);
        $this->assertEquals(10, $this->schedule->fresh()->available_count);
    }

    public function test_cancel_booking(): void
    {
        $this->schedule->update(['available_count' => 8]);

        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
            'adult_count' => 2,
            'child_count' => 0,
            'infant_count' => 0,
        ]);

        $booking = $this->service->cancel($booking, $this->traveler, '개인 사정');

        $this->assertEquals(BookingStatus::CANCELLED, $booking->status);
        $this->assertEquals(10, $this->schedule->fresh()->available_count);
    }

    public function test_cancel_completed_booking_fails(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::COMPLETED,
        ]);

        $this->expectException(InvalidBookingStatusException::class);
        $this->service->cancel($booking, $this->traveler);
    }

    public function test_complete_booking(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $booking = $this->service->complete($booking);

        $this->assertEquals(BookingStatus::COMPLETED, $booking->status);
        $this->assertEquals(1, $this->product->fresh()->booking_count);
    }

    public function test_mark_no_show(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $booking = $this->service->markNoShow($booking);

        $this->assertEquals(BookingStatus::NO_SHOW, $booking->status);
        $this->assertEquals(1, $this->traveler->fresh()->no_show_count);
    }

    public function test_user_blocked_after_three_no_shows(): void
    {
        $this->traveler->update(['no_show_count' => 2]);

        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $this->service->markNoShow($booking);

        $this->assertTrue($this->traveler->fresh()->is_blocked);
    }

    public function test_get_user_bookings(): void
    {
        Booking::factory()->count(3)->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]);
        Booking::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]); // other user

        $bookings = $this->service->getUserBookings($this->traveler);

        $this->assertEquals(3, $bookings->total());
    }

    public function test_get_vendor_bookings(): void
    {
        $vendorUser = User::factory()->create();
        $vendorUser->assignRole('vendor');
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $product = Product::factory()->active()->create(['vendor_id' => $vendor->id]);
        $schedule = ProductSchedule::factory()->create(['product_id' => $product->id]);

        Booking::factory()->count(3)->create([
            'product_id' => $product->id,
            'schedule_id' => $schedule->id,
        ]);
        Booking::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]); // other vendor

        $bookings = $this->service->getVendorBookings($vendorUser);

        $this->assertEquals(3, $bookings->total());
    }
}
