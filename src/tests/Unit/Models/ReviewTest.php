<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_can_be_created(): void
    {
        $review = Review::factory()->create([
            'rating' => 5,
            'content' => 'Great experience!',
        ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 5,
        ]);
    }

    public function test_review_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals($user->id, $review->user->id);
    }

    public function test_review_belongs_to_product(): void
    {
        $product = Product::factory()->create();
        $review = Review::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $review->product);
        $this->assertEquals($product->id, $review->product->id);
    }

    public function test_review_belongs_to_booking(): void
    {
        $booking = Booking::factory()->create();
        $review = Review::factory()->create(['booking_id' => $booking->id]);

        $this->assertInstanceOf(Booking::class, $review->booking);
        $this->assertEquals($booking->id, $review->booking->id);
    }

    public function test_review_has_no_reply_by_default(): void
    {
        $review = Review::factory()->create();

        $this->assertFalse($review->hasReply());
        $this->assertNull($review->vendor_reply);
    }

    public function test_review_can_be_replied(): void
    {
        $review = Review::factory()->create();

        $review->addVendorReply('Thank you for your feedback!');
        $review->refresh();

        $this->assertTrue($review->hasReply());
        $this->assertEquals('Thank you for your feedback!', $review->vendor_reply);
        $this->assertNotNull($review->replied_at);
    }

    public function test_review_scope_visible(): void
    {
        Review::factory()->count(3)->create(['is_visible' => true]);
        Review::factory()->hidden()->count(2)->create();

        $visibleReviews = Review::visible()->get();

        $this->assertCount(3, $visibleReviews);
    }

    public function test_review_scope_by_product(): void
    {
        $product = Product::factory()->create();
        Review::factory()->count(2)->create(['product_id' => $product->id]);
        Review::factory()->count(3)->create(); // other products

        $productReviews = Review::byProduct($product->id)->get();

        $this->assertCount(2, $productReviews);
    }

    public function test_review_soft_deletes(): void
    {
        $review = Review::factory()->create();
        $reviewId = $review->id;

        $review->delete();

        $this->assertSoftDeleted('reviews', ['id' => $reviewId]);
    }
}
