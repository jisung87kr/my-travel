<?php

namespace Tests\Unit\Models;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_can_be_created(): void
    {
        $booking = Booking::factory()->create();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
        ]);
    }

    public function test_booking_code_is_auto_generated(): void
    {
        $booking = Booking::factory()->create();

        $this->assertNotNull($booking->booking_code);
        $this->assertStringStartsWith('B', $booking->booking_code);
    }

    public function test_booking_code_is_unique(): void
    {
        $booking1 = Booking::factory()->create();
        $booking2 = Booking::factory()->create();

        $this->assertNotEquals($booking1->booking_code, $booking2->booking_code);
    }

    public function test_booking_status_is_cast_to_enum(): void
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        $this->assertInstanceOf(BookingStatus::class, $booking->status);
        $this->assertEquals(BookingStatus::PENDING, $booking->status);
    }

    public function test_booking_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $booking->user);
        $this->assertEquals($user->id, $booking->user->id);
    }

    public function test_booking_belongs_to_product(): void
    {
        $product = Product::factory()->create();
        $booking = Booking::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $booking->product);
        $this->assertEquals($product->id, $booking->product->id);
    }

    public function test_booking_belongs_to_schedule(): void
    {
        $schedule = ProductSchedule::factory()->create();
        $booking = Booking::factory()->create(['schedule_id' => $schedule->id]);

        $this->assertInstanceOf(ProductSchedule::class, $booking->schedule);
        $this->assertEquals($schedule->id, $booking->schedule->id);
    }

    public function test_booking_total_persons_attribute(): void
    {
        $booking = Booking::factory()->create([
            'adult_count' => 2,
            'child_count' => 1,
            'infant_count' => 1,
        ]);

        $this->assertEquals(4, $booking->total_persons);
    }

    public function test_booking_formatted_price_attribute(): void
    {
        $booking = Booking::factory()->create([
            'total_price' => 150000,
        ]);

        $this->assertEquals('150,000원', $booking->formatted_price);
    }

    public function test_booking_status_checks(): void
    {
        $pendingBooking = Booking::factory()->create();
        $confirmedBooking = Booking::factory()->confirmed()->create();
        $completedBooking = Booking::factory()->completed()->create();
        $cancelledBooking = Booking::factory()->cancelled()->create();

        $this->assertTrue($pendingBooking->isPending());
        $this->assertTrue($confirmedBooking->isConfirmed());
        $this->assertTrue($completedBooking->isCompleted());
        $this->assertTrue($cancelledBooking->isCancelled());
    }

    public function test_booking_can_be_cancelled_check(): void
    {
        $pendingBooking = Booking::factory()->create();
        $confirmedBooking = Booking::factory()->confirmed()->create();
        $completedBooking = Booking::factory()->completed()->create();
        $cancelledBooking = Booking::factory()->cancelled()->create();

        $this->assertTrue($pendingBooking->canBeCancelled());
        $this->assertTrue($confirmedBooking->canBeCancelled());
        $this->assertFalse($completedBooking->canBeCancelled());
        $this->assertFalse($cancelledBooking->canBeCancelled());
    }

    public function test_booking_confirm(): void
    {
        $booking = Booking::factory()->create();

        $booking->confirm();
        $booking->refresh();

        $this->assertTrue($booking->isConfirmed());
        $this->assertNotNull($booking->confirmed_at);
    }

    public function test_booking_cancel(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'total_count' => 10,
            'available_count' => 7,
        ]);
        $booking = Booking::factory()->confirmed()->create([
            'schedule_id' => $schedule->id,
            'adult_count' => 2,
            'child_count' => 1,
            'infant_count' => 0,
        ]);
        $user = $booking->user;

        $booking->cancel($user, 'Personal reason');
        $booking->refresh();
        $schedule->refresh();

        $this->assertTrue($booking->isCancelled());
        $this->assertNotNull($booking->cancelled_at);
        $this->assertEquals($user->id, $booking->cancelled_by);
        $this->assertEquals('Personal reason', $booking->cancellation_reason);
        $this->assertEquals(10, $schedule->available_count); // Stock restored
    }

    public function test_booking_mark_as_no_show(): void
    {
        $user = User::factory()->create(['no_show_count' => 0]);
        $booking = Booking::factory()->confirmed()->create(['user_id' => $user->id]);

        $booking->markAsNoShow();
        $booking->refresh();
        $user->refresh();

        $this->assertEquals(BookingStatus::NO_SHOW, $booking->status);
        $this->assertEquals(1, $user->no_show_count);
    }

    public function test_booking_complete(): void
    {
        $product = Product::factory()->create(['booking_count' => 5]);
        $booking = Booking::factory()->confirmed()->create(['product_id' => $product->id]);

        $booking->complete();
        $booking->refresh();
        $product->refresh();

        $this->assertTrue($booking->isCompleted());
        $this->assertEquals(6, $product->booking_count);
    }

    public function test_booking_scopes(): void
    {
        Booking::factory()->count(2)->create();
        Booking::factory()->confirmed()->count(3)->create();
        Booking::factory()->completed()->count(1)->create();

        $this->assertCount(2, Booking::pending()->get());
        $this->assertCount(3, Booking::confirmed()->get());
        $this->assertCount(1, Booking::completed()->get());
    }

    public function test_booking_soft_deletes(): void
    {
        $booking = Booking::factory()->create();
        $bookingId = $booking->id;

        $booking->delete();

        $this->assertSoftDeleted('bookings', ['id' => $bookingId]);
    }

    public function test_booking_status_transitions(): void
    {
        $pendingBooking = Booking::factory()->create();

        $this->assertTrue($pendingBooking->canTransitionTo(BookingStatus::CONFIRMED));
        $this->assertTrue($pendingBooking->canTransitionTo(BookingStatus::CANCELLED));
        $this->assertFalse($pendingBooking->canTransitionTo(BookingStatus::COMPLETED));
    }
}
