<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $travelers = User::role('traveler')->get();
        $products = Product::with(['schedules', 'prices'])->get();

        if ($travelers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $specialRequests = [
            '채식 식단 요청드립니다.',
            '휠체어 이용자가 있습니다.',
            '알레르기가 있어서 해산물을 피해주세요.',
            '영어 가이드 요청드립니다.',
            null,
            null,
            null,
        ];

        // Create completed bookings (for reviews)
        foreach ($products as $product) {
            $bookingCount = rand(3, 6);

            for ($i = 0; $i < $bookingCount; $i++) {
                $traveler = $travelers->random();
                $schedule = $product->schedules->random();

                if (!$schedule) {
                    continue;
                }

                $adultCount = rand(1, 3);
                $childCount = rand(0, 2);

                $adultPrice = $product->prices->where('type', 'adult')->first()?->price ?? 50000;
                $childPrice = $product->prices->where('type', 'child')->first()?->price ?? 30000;

                $totalPrice = ($adultCount * $adultPrice) + ($childCount * $childPrice);

                Booking::create([
                    'user_id' => $traveler->id,
                    'product_id' => $product->id,
                    'schedule_id' => $schedule->id,
                    'booking_code' => $this->generateBookingCode(),
                    'status' => BookingStatus::COMPLETED,
                    'adult_count' => $adultCount,
                    'child_count' => $childCount,
                    'infant_count' => 0,
                    'total_price' => $totalPrice,
                    'special_request' => $specialRequests[array_rand($specialRequests)],
                    'contact_name' => $traveler->name,
                    'contact_phone' => '010-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                    'contact_email' => $traveler->email,
                    'confirmed_at' => now()->subDays(rand(30, 60)),
                    'completed_at' => now()->subDays(rand(1, 30)),
                    'created_at' => now()->subDays(rand(60, 90)),
                ]);
            }
        }

        // Create some pending and confirmed bookings
        foreach ($products->take(5) as $product) {
            $traveler = $travelers->random();
            $schedule = $product->schedules->where('date', '>=', now()->toDateString())->first();

            if (!$schedule) {
                continue;
            }

            $adultCount = rand(1, 2);
            $childCount = rand(0, 1);

            $adultPrice = $product->prices->where('type', 'adult')->first()?->price ?? 50000;
            $childPrice = $product->prices->where('type', 'child')->first()?->price ?? 30000;

            $totalPrice = ($adultCount * $adultPrice) + ($childCount * $childPrice);

            // Pending booking
            Booking::create([
                'user_id' => $traveler->id,
                'product_id' => $product->id,
                'schedule_id' => $schedule->id,
                'booking_code' => $this->generateBookingCode(),
                'status' => BookingStatus::PENDING,
                'adult_count' => $adultCount,
                'child_count' => $childCount,
                'infant_count' => 0,
                'total_price' => $totalPrice,
                'special_request' => $specialRequests[array_rand($specialRequests)],
                'contact_name' => $traveler->name,
                'contact_phone' => '010-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'contact_email' => $traveler->email,
                'created_at' => now()->subHours(rand(1, 48)),
            ]);

            // Confirmed booking
            $anotherTraveler = $travelers->random();
            Booking::create([
                'user_id' => $anotherTraveler->id,
                'product_id' => $product->id,
                'schedule_id' => $schedule->id,
                'booking_code' => $this->generateBookingCode(),
                'status' => BookingStatus::CONFIRMED,
                'adult_count' => rand(1, 2),
                'child_count' => 0,
                'infant_count' => 0,
                'total_price' => $adultPrice * rand(1, 2),
                'special_request' => null,
                'contact_name' => $anotherTraveler->name,
                'contact_phone' => '010-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'contact_email' => $anotherTraveler->email,
                'confirmed_at' => now()->subDays(rand(1, 5)),
                'created_at' => now()->subDays(rand(5, 10)),
            ]);
        }
    }

    private function generateBookingCode(): string
    {
        return 'B' . date('ymd') . Str::upper(Str::random(6));
    }
}
