<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewService
{
    public function create(Booking $booking, array $data, array $images = []): Review
    {
        return DB::transaction(function () use ($booking, $data, $images) {
            $review = Review::create([
                'user_id' => $booking->user_id,
                'product_id' => $booking->product_id,
                'booking_id' => $booking->id,
                'rating' => $data['rating'],
                'content' => $data['content'],
                'is_visible' => true,
            ]);

            // Upload images
            foreach ($images as $index => $image) {
                $this->uploadImage($review, $image, $index);
            }

            // Update product average rating
            $this->updateProductRating($booking->product);

            return $review;
        });
    }

    public function update(Review $review, array $data): Review
    {
        $review->update([
            'rating' => $data['rating'] ?? $review->rating,
            'content' => $data['content'] ?? $review->content,
        ]);

        // Update product average rating
        $this->updateProductRating($review->product);

        return $review->fresh();
    }

    public function delete(Review $review): void
    {
        $product = $review->product;

        // Delete images from storage
        foreach ($review->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $review->delete();

        // Update product average rating
        $this->updateProductRating($product);
    }

    public function reply(Review $review, string $content): Review
    {
        $review->reply($content);

        return $review->fresh();
    }

    public function uploadImage(Review $review, UploadedFile $file, int $sortOrder = 0): ReviewImage
    {
        $path = $file->store('reviews', 'public');

        return $review->images()->create([
            'path' => $path,
            'sort_order' => $sortOrder,
        ]);
    }

    public function deleteImage(ReviewImage $image): void
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
    }

    public function getProductReviews(Product $product, int $perPage = 10)
    {
        return Review::with(['user', 'images'])
            ->visible()
            ->byProduct($product->id)
            ->latest()
            ->paginate($perPage);
    }

    public function canReview(Booking $booking): bool
    {
        // Must be completed
        if ($booking->status->value !== 'completed') {
            return false;
        }

        // Must not have existing review
        if ($booking->review) {
            return false;
        }

        return true;
    }

    private function updateProductRating(Product $product): void
    {
        $stats = Review::visible()
            ->byProduct($product->id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as review_count')
            ->first();

        $product->update([
            'rating' => round($stats->avg_rating ?? 0, 1),
            'review_count' => $stats->review_count ?? 0,
        ]);
    }
}
