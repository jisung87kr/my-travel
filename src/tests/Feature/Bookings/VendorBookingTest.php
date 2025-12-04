<?php

namespace Tests\Feature\Bookings;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorBookingTest extends TestCase
{
    use RefreshDatabase;

    private User $vendorUser;
    private Vendor $vendor;
    private Product $product;
    private ProductSchedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->vendorUser = User::factory()->create();
        $this->vendorUser->assignRole('vendor');
        $this->vendor = Vendor::factory()->create(['user_id' => $this->vendorUser->id]);

        $this->product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::ACTIVE,
            'booking_type' => BookingType::REQUEST,
        ]);

        $this->schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
            'date' => today()->addDay(),
            'total_count' => 10,
            'available_count' => 10,
            'is_active' => true,
        ]);
    }

    public function test_vendor_can_list_bookings(): void
    {
        Booking::factory()->count(3)->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->getJson('/vendor/bookings');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_vendor_can_filter_bookings_by_status(): void
    {
        Booking::factory()->count(2)->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::PENDING,
        ]);
        Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->getJson('/vendor/bookings?status=pending');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_vendor_can_view_booking_detail(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->getJson("/vendor/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $booking->id);
    }

    public function test_vendor_cannot_view_other_vendor_booking(): void
    {
        $otherVendor = Vendor::factory()->create();
        $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);
        $otherSchedule = ProductSchedule::factory()->create(['product_id' => $otherProduct->id]);
        $booking = Booking::factory()->create([
            'product_id' => $otherProduct->id,
            'schedule_id' => $otherSchedule->id,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->getJson("/vendor/bookings/{$booking->id}");

        $response->assertStatus(403);
    }

    public function test_vendor_can_approve_pending_booking(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::PENDING,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->patchJson("/vendor/bookings/{$booking->id}/approve");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', BookingStatus::CONFIRMED->value);
    }

    public function test_vendor_can_reject_pending_booking(): void
    {
        $this->schedule->update(['available_count' => 8]);

        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::PENDING,
            'adult_count' => 2,
            'child_count' => 0,
            'infant_count' => 0,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->patchJson("/vendor/bookings/{$booking->id}/reject", [
                'reason' => '일정 불가',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', BookingStatus::CANCELLED->value);

        $this->assertEquals(10, $this->schedule->fresh()->available_count);
    }

    public function test_vendor_can_complete_confirmed_booking(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->patchJson("/vendor/bookings/{$booking->id}/complete");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', BookingStatus::COMPLETED->value);
    }

    public function test_vendor_can_mark_no_show(): void
    {
        $traveler = User::factory()->create();
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'user_id' => $traveler->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->patchJson("/vendor/bookings/{$booking->id}/no-show");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', BookingStatus::NO_SHOW->value);

        $this->assertEquals(1, $traveler->fresh()->no_show_count);
    }

    public function test_cannot_approve_non_pending_booking(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->patchJson("/vendor/bookings/{$booking->id}/approve");

        $response->assertStatus(400);
    }

    public function test_cannot_complete_pending_booking(): void
    {
        $booking = Booking::factory()->create([
            'product_id' => $this->product->id,
            'schedule_id' => $this->schedule->id,
            'status' => BookingStatus::PENDING,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->patchJson("/vendor/bookings/{$booking->id}/complete");

        $response->assertStatus(400);
    }

    public function test_traveler_cannot_access_vendor_bookings(): void
    {
        $traveler = User::factory()->create();
        $traveler->assignRole('traveler');

        $response = $this->actingAs($traveler)
            ->getJson('/vendor/bookings');

        $response->assertStatus(403);
    }
}
