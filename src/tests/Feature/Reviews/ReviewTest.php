<?php

namespace Tests\Feature\Reviews;

use App\Enums\BookingStatus;
use App\Enums\ProductStatus;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $traveler;
    private User $vendorUser;
    private Vendor $vendor;
    private Product $product;
    private Booking $completedBooking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->traveler = User::factory()->create();
        $this->traveler->assignRole('traveler');

        $this->vendorUser = User::factory()->create();
        $this->vendorUser->assignRole('vendor');

        $this->vendor = Vendor::factory()->approved()->create([
            'user_id' => $this->vendorUser->id,
        ]);

        $this->product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::ACTIVE,
        ]);

        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
        ]);

        $this->completedBooking = Booking::factory()->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $schedule->id,
            'status' => BookingStatus::COMPLETED,
        ]);
    }

    public function test_traveler_can_write_review_for_completed_booking(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->completedBooking->id}/review", [
                'rating' => 5,
                'content' => '정말 좋은 투어였습니다. 가이드분이 친절하셨어요.',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'booking_id' => $this->completedBooking->id,
            'user_id' => $this->traveler->id,
            'rating' => 5,
        ]);
    }

    public function test_traveler_can_write_review_with_images(): void
    {
        // Skip if GD is not available
        if (!function_exists('imagejpeg')) {
            $this->markTestSkipped('GD library not available');
        }

        Storage::fake('public');

        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->completedBooking->id}/review", [
                'rating' => 5,
                'content' => '아름다운 풍경이었습니다. 정말 좋았어요!',
                'images' => [
                    UploadedFile::fake()->image('photo1.jpg'),
                    UploadedFile::fake()->image('photo2.jpg'),
                ],
            ]);

        $response->assertStatus(201);

        $review = Review::where('booking_id', $this->completedBooking->id)->first();
        $this->assertNotNull($review);
    }

    public function test_traveler_cannot_write_review_for_non_completed_booking(): void
    {
        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
        ]);

        $pendingBooking = Booking::factory()->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $schedule->id,
            'status' => BookingStatus::PENDING,
        ]);

        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$pendingBooking->id}/review", [
                'rating' => 5,
                'content' => '리뷰 테스트입니다.',
            ]);

        $response->assertStatus(400);
    }

    public function test_traveler_cannot_write_review_twice(): void
    {
        Review::factory()->create([
            'booking_id' => $this->completedBooking->id,
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
        ]);

        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->completedBooking->id}/review", [
                'rating' => 4,
                'content' => '두번째 리뷰 테스트입니다.',
            ]);

        $response->assertStatus(400);
    }

    public function test_traveler_cannot_review_other_users_booking(): void
    {
        $otherUser = User::factory()->create();
        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
        ]);
        $otherBooking = Booking::factory()->create([
            'user_id' => $otherUser->id,
            'product_id' => $this->product->id,
            'schedule_id' => $schedule->id,
            'status' => BookingStatus::COMPLETED,
        ]);

        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$otherBooking->id}/review", [
                'rating' => 5,
                'content' => '리뷰 테스트입니다.',
            ]);

        $response->assertStatus(403);
    }

    public function test_traveler_can_update_own_review(): void
    {
        $review = Review::factory()->create([
            'booking_id' => $this->completedBooking->id,
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
            'rating' => 4,
            'content' => '원래 리뷰입니다. 괜찮았어요.',
        ]);

        $response = $this->actingAs($this->traveler)
            ->putJson("/reviews/{$review->id}", [
                'rating' => 5,
                'content' => '수정된 리뷰입니다. 정말 좋았어요!',
            ]);

        $response->assertStatus(200);

        $this->assertEquals(5, $review->fresh()->rating);
        $this->assertEquals('수정된 리뷰입니다. 정말 좋았어요!', $review->fresh()->content);
    }

    public function test_traveler_can_delete_own_review(): void
    {
        $review = Review::factory()->create([
            'booking_id' => $this->completedBooking->id,
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
        ]);

        $response = $this->actingAs($this->traveler)
            ->deleteJson("/reviews/{$review->id}");

        $response->assertStatus(200);
        // Soft delete check
        $this->assertSoftDeleted('reviews', ['id' => $review->id]);
    }

    public function test_vendor_can_reply_to_review(): void
    {
        $review = Review::factory()->create([
            'booking_id' => $this->completedBooking->id,
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/vendor/reviews/{$review->id}/reply", [
                'reply' => '리뷰 감사합니다. 다음에도 찾아주세요!',
            ]);

        $response->assertStatus(200);

        $this->assertNotNull($review->fresh()->vendor_reply);
        $this->assertNotNull($review->fresh()->replied_at);
    }

    public function test_user_can_report_review(): void
    {
        $review = Review::factory()->create([
            'booking_id' => $this->completedBooking->id,
            'product_id' => $this->product->id,
            'user_id' => $this->traveler->id,
        ]);

        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->postJson("/reviews/{$review->id}/report", [
                'reason' => '부적절한 내용이 포함되어 있습니다.',
            ]);

        // Controller returns 200 for this endpoint
        $response->assertStatus(200);

        $this->assertDatabaseHas('reports', [
            'reportable_type' => Review::class,
            'reportable_id' => $review->id,
            'reporter_id' => $otherUser->id,
        ]);
    }

    public function test_rating_validation(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->completedBooking->id}/review", [
                'rating' => 6, // Invalid: should be 1-5
                'content' => '리뷰 테스트입니다.',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('rating');
    }

    public function test_content_min_length_validation(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->completedBooking->id}/review", [
                'rating' => 5,
                'content' => 'ab', // Too short: minimum 10 characters
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    public function test_unauthenticated_user_cannot_write_review(): void
    {
        $response = $this->postJson("/bookings/{$this->completedBooking->id}/review", [
            'rating' => 5,
            'content' => '리뷰 테스트입니다.',
        ]);

        $response->assertStatus(401);
    }
}
