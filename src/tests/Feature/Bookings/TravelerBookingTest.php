<?php

namespace Tests\Feature\Bookings;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductSchedule;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelerBookingTest extends TestCase
{
    use RefreshDatabase;

    private User $traveler;
    private Product $product;
    private ProductSchedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->traveler = User::factory()->create();
        $this->traveler->assignRole('traveler');

        $vendor = Vendor::factory()->create();
        $this->product = Product::factory()->create([
            'vendor_id' => $vendor->id,
            'status' => ProductStatus::ACTIVE,
            'booking_type' => BookingType::INSTANT,
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

    public function test_traveler_can_list_own_bookings(): void
    {
        Booking::factory()->count(3)->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]);

        $response = $this->actingAs($this->traveler)
            ->getJson('/bookings');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_traveler_can_create_instant_booking(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson('/bookings', [
                'product_id' => $this->product->id,
                'date' => today()->addDay()->toDateString(),
                'adult_count' => 2,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', BookingStatus::CONFIRMED->value);

        $booking = Booking::where('user_id', $this->traveler->id)
            ->where('product_id', $this->product->id)
            ->first();
        $this->assertNotNull($booking);
        $this->assertEquals(2, $booking->adult_count);

        $this->assertEquals(8, $this->schedule->fresh()->available_count);
    }

    public function test_traveler_can_create_request_booking(): void
    {
        $this->product->update(['booking_type' => BookingType::REQUEST]);

        $response = $this->actingAs($this->traveler)
            ->postJson('/bookings', [
                'product_id' => $this->product->id,
                'date' => today()->addDay()->toDateString(),
                'adult_count' => 2,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', BookingStatus::PENDING->value);
    }

    public function test_traveler_can_view_own_booking(): void
    {
        $booking = Booking::factory()->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]);

        $response = $this->actingAs($this->traveler)
            ->getJson("/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $booking->id);
    }

    public function test_traveler_cannot_view_other_user_booking(): void
    {
        $otherUser = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $otherUser->id,
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]);

        $response = $this->actingAs($this->traveler)
            ->getJson("/bookings/{$booking->id}");

        $response->assertStatus(403);
    }

    public function test_traveler_can_cancel_pending_booking(): void
    {
        $this->schedule->update(['available_count' => 8]);

        $booking = Booking::factory()->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::PENDING,
            'adult_count' => 2,
            'child_count' => 0,
            'infant_count' => 0,
        ]);

        $response = $this->actingAs($this->traveler)
            ->deleteJson("/bookings/{$booking->id}", [
                'reason' => '일정 변경',
            ]);

        $response->assertStatus(200);
        $this->assertEquals(BookingStatus::CANCELLED, $booking->fresh()->status);
        $this->assertEquals(10, $this->schedule->fresh()->available_count);
    }

    public function test_traveler_cannot_cancel_completed_booking(): void
    {
        $booking = Booking::factory()->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::COMPLETED,
        ]);

        $response = $this->actingAs($this->traveler)
            ->deleteJson("/bookings/{$booking->id}");

        $response->assertStatus(403);
    }

    public function test_blocked_user_cannot_create_booking(): void
    {
        $this->traveler->update(['is_blocked' => true]);

        $response = $this->actingAs($this->traveler)
            ->postJson('/bookings', [
                'product_id' => $this->product->id,
                'date' => today()->addDay()->toDateString(),
                'adult_count' => 2,
            ]);

        // Middleware redirects blocked users (302 for web, may include JSON with error)
        $response->assertStatus(302);
    }

    public function test_cannot_book_inactive_product(): void
    {
        $this->product->update(['status' => ProductStatus::DRAFT]);

        $response = $this->actingAs($this->traveler)
            ->postJson('/bookings', [
                'product_id' => $this->product->id,
                'date' => today()->addDay()->toDateString(),
                'adult_count' => 2,
            ]);

        $response->assertStatus(422);
    }

    public function test_cannot_book_past_date(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson('/bookings', [
                'product_id' => $this->product->id,
                'date' => today()->subDay()->toDateString(),
                'adult_count' => 2,
            ]);

        $response->assertStatus(422);
    }

    public function test_cannot_book_with_insufficient_inventory(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson('/bookings', [
                'product_id' => $this->product->id,
                'date' => today()->addDay()->toDateString(),
                'adult_count' => 20,
            ]);

        $response->assertStatus(400);
    }

    public function test_unauthenticated_user_cannot_create_booking(): void
    {
        $response = $this->postJson('/bookings', [
            'product_id' => $this->product->id,
            'date' => today()->addDay()->toDateString(),
            'adult_count' => 2,
        ]);

        $response->assertStatus(401);
    }
}
