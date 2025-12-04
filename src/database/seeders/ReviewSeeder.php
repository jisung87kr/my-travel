<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $completedBookings = Booking::where('status', BookingStatus::COMPLETED)
            ->doesntHave('review')
            ->with(['user', 'product'])
            ->get();

        if ($completedBookings->isEmpty()) {
            return;
        }

        $reviewContents = [
            5 => [
                '정말 최고의 투어였습니다! 가이드분이 너무 친절하시고 설명도 자세해서 좋았어요.',
                '완벽한 경험이었습니다. 다음에 또 이용하고 싶어요.',
                '기대 이상이었어요! 모든 게 잘 준비되어 있었고 시간도 딱 맞았습니다.',
                '가족들과 함께 했는데 모두 만족했어요. 강력 추천합니다!',
                '이 가격에 이런 서비스라니 놀라웠어요. 감사합니다!',
            ],
            4 => [
                '전체적으로 좋았습니다. 다만 일정이 조금 빠듯했어요.',
                '가이드분 설명이 좋았어요. 점심 식사도 맛있었습니다.',
                '재미있는 투어였어요. 조금 더 자유시간이 있었으면 좋겠어요.',
                '사진 찍기 좋은 포인트를 많이 알려주셔서 좋았습니다.',
                '친절한 서비스에 감사드립니다. 조금 피곤했지만 만족해요.',
            ],
            3 => [
                '보통이었어요. 기대했던 것과는 조금 달랐습니다.',
                '나쁘지 않았지만 특별하지도 않았어요.',
                '가격 대비 그럭저럭이었습니다.',
            ],
        ];

        $vendorReplies = [
            '소중한 후기 감사합니다! 다음에도 좋은 여행 되시길 바랍니다.',
            '좋은 평가 감사드립니다. 더 나은 서비스로 보답하겠습니다.',
            '감사합니다! 또 방문해주세요.',
            '피드백 감사합니다. 말씀하신 부분 개선하도록 노력하겠습니다.',
            null,
        ];

        // Create reviews for completed bookings
        foreach ($completedBookings as $booking) {
            // 70% chance to leave a review
            if (rand(1, 100) > 70) {
                continue;
            }

            $rating = $this->getWeightedRating();
            $contents = $reviewContents[$rating] ?? $reviewContents[4];
            $content = $contents[array_rand($contents)];
            $vendorReply = $vendorReplies[array_rand($vendorReplies)];

            Review::create([
                'user_id' => $booking->user_id,
                'product_id' => $booking->product_id,
                'booking_id' => $booking->id,
                'rating' => $rating,
                'content' => $content,
                'vendor_reply' => $vendorReply,
                'replied_at' => $vendorReply ? now()->subDays(rand(1, 30)) : null,
                'is_visible' => true,
                'created_at' => $booking->completed_at?->addDays(rand(1, 7)) ?? now()->subDays(rand(1, 30)),
            ]);
        }

        // Update product ratings
        $products = Product::all();
        foreach ($products as $product) {
            $reviews = $product->reviews;
            if ($reviews->count() > 0) {
                $product->update([
                    'average_rating' => round($reviews->avg('rating'), 1),
                    'review_count' => $reviews->count(),
                ]);
            }
        }
    }

    private function getWeightedRating(): int
    {
        $rand = rand(1, 100);

        if ($rand <= 50) {
            return 5;
        } elseif ($rand <= 85) {
            return 4;
        } else {
            return 3;
        }
    }
}
