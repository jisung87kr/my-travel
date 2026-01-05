<?php

namespace Database\Seeders;

use App\Enums\BookingType;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductSchedule;
use App\Models\ProductTranslation;
use App\Models\Region;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Cache region ids by code
        $regionIds = Region::provinces()->pluck('id', 'code');

        $products = $this->getProductData();

        foreach ($products as $data) {
            $vendor = Vendor::where('company_name', 'like', "%{$data['vendor_keyword']}%")->first();
            if (! $vendor) {
                $vendor = Vendor::first();
            }

            // Get region_id from code
            $regionId = $regionIds[$data['region_code']] ?? null;

            // Create product
            $product = Product::create([
                'vendor_id' => $vendor->id,
                'slug' => Str::slug($data['translations']['en']['title']) ?: 'product-' . uniqid(),
                'type' => $data['type'],
                'region_id' => $regionId,
                'duration' => $data['duration'],
                'min_persons' => $data['min_persons'],
                'max_persons' => $data['max_persons'],
                'booking_type' => $data['booking_type'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'status' => ProductStatus::ACTIVE,
                'average_rating' => $data['rating'],
                'review_count' => $data['review_count'],
                'booking_count' => $data['booking_count'] ?? 0,
            ]);

            // Create translations
            foreach ($data['translations'] as $locale => $translation) {
                ProductTranslation::create([
                    'product_id' => $product->id,
                    'locale' => $locale,
                    'title' => $translation['title'],
                    'short_description' => $translation['short_description'] ?? null,
                    'description' => $translation['description'],
                    'includes' => $translation['includes'] ?? null,
                    'excludes' => $translation['excludes'] ?? null,
                    'notes' => $translation['notes'] ?? null,
                    'meeting_point' => $translation['meeting_point'] ?? null,
                    'meeting_point_detail' => $translation['meeting_point_detail'] ?? null,
                ]);
            }

            // Create images - download and store locally
            foreach ($data['images'] as $index => $imageUrl) {
                $imagePaths = $this->downloadAndStoreImage($imageUrl, $product->id);

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePaths['path'],
                    'thumbnail_path' => $imagePaths['thumbnail_path'],
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }

            // Create prices
            ProductPrice::create([
                'product_id' => $product->id,
                'type' => 'adult',
                'label' => '성인',
                'price' => $data['adult_price'],
                'min_age' => 13,
                'max_age' => null,
                'is_active' => true,
            ]);

            ProductPrice::create([
                'product_id' => $product->id,
                'type' => 'child',
                'label' => '아동',
                'price' => $data['child_price'],
                'min_age' => 3,
                'max_age' => 12,
                'is_active' => true,
            ]);

            // Create schedules for next 30 days
            for ($i = 1; $i <= 30; $i++) {
                ProductSchedule::create([
                    'product_id' => $product->id,
                    'date' => now()->addDays($i)->toDateString(),
                    'start_time' => $data['start_time'] ?? '09:00:00',
                    'total_count' => $data['max_persons'],
                    'available_count' => $data['max_persons'],
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Download image from URL and store in local storage
     */
    private function downloadAndStoreImage(string $imageUrl, int $productId): array
    {
        $directory = "products/{$productId}";

        // Ensure directory exists
        Storage::disk('public')->makeDirectory($directory);

        // Generate unique filename
        $filename = Str::random(40) . '.jpg';
        $path = "{$directory}/{$filename}";
        $thumbFilename = Str::random(40) . '_thumb.jpg';
        $thumbPath = "{$directory}/{$thumbFilename}";

        try {
            // Download image from URL
            $response = Http::timeout(30)->get($imageUrl);

            if ($response->successful()) {
                // Store original image
                Storage::disk('public')->put($path, $response->body());

                // For thumbnail, we'll use the same image (simplified for seeder)
                // In production, you'd want to resize it
                Storage::disk('public')->put($thumbPath, $response->body());

                return [
                    'path' => $path,
                    'thumbnail_path' => $thumbPath,
                ];
            }
        } catch (\Exception $e) {
            // Log error but continue with placeholder
            \Log::warning("Failed to download image: {$imageUrl} - {$e->getMessage()}");
        }

        // If download fails, create a placeholder image
        return $this->createPlaceholderImage($directory);
    }

    /**
     * Create a simple placeholder image
     */
    private function createPlaceholderImage(string $directory): array
    {
        $filename = Str::random(40) . '.jpg';
        $path = "{$directory}/{$filename}";
        $thumbFilename = Str::random(40) . '_thumb.jpg';
        $thumbPath = "{$directory}/{$thumbFilename}";

        // Create a simple 800x600 placeholder image
        $image = imagecreatetruecolor(800, 600);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 150, 150, 150);
        imagefill($image, 0, 0, $bgColor);
        imagestring($image, 5, 350, 290, 'No Image', $textColor);

        // Save to buffer
        ob_start();
        imagejpeg($image, null, 80);
        $imageData = ob_get_clean();
        imagedestroy($image);

        // Store images
        Storage::disk('public')->put($path, $imageData);

        // Create thumbnail (300x200)
        $thumb = imagecreatetruecolor(300, 200);
        imagefill($thumb, 0, 0, imagecolorallocate($thumb, 240, 240, 240));
        imagestring($thumb, 3, 120, 90, 'No Image', imagecolorallocate($thumb, 150, 150, 150));

        ob_start();
        imagejpeg($thumb, null, 80);
        $thumbData = ob_get_clean();
        imagedestroy($thumb);

        Storage::disk('public')->put($thumbPath, $thumbData);

        return [
            'path' => $path,
            'thumbnail_path' => $thumbPath,
        ];
    }

    private function getProductData(): array
    {
        return [
            // 서울 투어
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'seoul',
                'duration' => 480,
                'min_persons' => 1,
                'max_persons' => 15,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 37.5759,
                'longitude' => 126.9769,
                'rating' => 4.8,
                'review_count' => 124,
                'booking_count' => 350,
                'adult_price' => 65000,
                'child_price' => 45000,
                'start_time' => '09:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1538485399081-7191377e8241?w=800',
                    'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '경복궁과 북촌 한옥마을 투어',
                        'short_description' => '조선 왕조의 역사가 살아있는 경복궁과 북촌 한옥마을 문화 투어',
                        'description' => '조선 왕조의 역사가 살아 숨쉬는 경복궁과 전통 한옥이 아름다운 북촌 한옥마을을 둘러보는 문화 투어입니다. 전문 가이드와 함께 한국의 전통 문화와 역사를 깊이 있게 체험해보세요.',
                        'includes' => '전문 가이드, 경복궁 입장료, 한복 체험',
                        'excludes' => '식사, 개인 경비',
                        'notes' => '편한 신발 착용을 권장합니다.',
                        'meeting_point' => '경복궁역 5번 출구',
                        'meeting_point_detail' => '스타벅스 앞 집합',
                    ],
                    'en' => [
                        'title' => 'Gyeongbokgung Palace & Bukchon Hanok Village Tour',
                        'short_description' => 'Cultural tour of historic Gyeongbokgung Palace and Bukchon Hanok Village',
                        'description' => 'Explore the historic Gyeongbokgung Palace and the beautiful traditional Bukchon Hanok Village. Experience Korean traditional culture and history with our professional guide.',
                        'includes' => 'Professional guide, Palace entrance fee, Hanbok experience',
                        'excludes' => 'Meals, Personal expenses',
                        'notes' => 'Comfortable shoes recommended.',
                        'meeting_point' => 'Gyeongbokgung Station Exit 5',
                        'meeting_point_detail' => 'Meet in front of Starbucks',
                    ],
                ],
            ],
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'seoul',
                'duration' => 300,
                'min_persons' => 1,
                'max_persons' => 10,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 37.5563,
                'longitude' => 126.9237,
                'rating' => 4.6,
                'review_count' => 89,
                'booking_count' => 220,
                'adult_price' => 55000,
                'child_price' => 40000,
                'start_time' => '14:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1517154421773-0529f29ea451?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '홍대 & 이태원 나이트 투어',
                        'short_description' => '서울의 젊음과 열정이 넘치는 홍대와 이태원 나이트 투어',
                        'description' => '서울의 젊음과 열정이 넘치는 홍대와 다문화가 공존하는 이태원을 탐험하는 나이트 투어입니다.',
                        'includes' => '가이드, 간식 1회',
                        'excludes' => '음료, 클럽 입장료',
                        'meeting_point' => '홍대입구역 9번 출구',
                    ],
                    'en' => [
                        'title' => 'Hongdae & Itaewon Night Tour',
                        'short_description' => 'Explore the vibrant nightlife of Hongdae and Itaewon',
                        'description' => 'Explore the vibrant nightlife of Hongdae and multicultural Itaewon with our exciting night tour.',
                        'includes' => 'Guide, One snack',
                        'excludes' => 'Drinks, Club entrance fees',
                        'meeting_point' => 'Hongik Univ. Station Exit 9',
                    ],
                ],
            ],
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::ACTIVITY,
                'region_code' => 'seoul',
                'duration' => 180,
                'min_persons' => 2,
                'max_persons' => 8,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 37.5743,
                'longitude' => 126.9850,
                'rating' => 4.9,
                'review_count' => 156,
                'booking_count' => 420,
                'adult_price' => 75000,
                'child_price' => 55000,
                'start_time' => '10:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1590077428593-a55bb07c4665?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '전통 한식 쿠킹클래스',
                        'short_description' => '김치, 비빔밥, 잡채 등 대표적인 한식을 배우는 쿠킹클래스',
                        'description' => '한국 전통 음식을 직접 만들어보는 쿠킹클래스입니다. 김치, 비빔밥, 잡채 등 대표적인 한식을 배워보세요.',
                        'includes' => '모든 재료, 레시피북, 앞치마',
                        'excludes' => '없음',
                        'meeting_point' => '인사동 쌈지길 입구',
                    ],
                    'en' => [
                        'title' => 'Traditional Korean Cooking Class',
                        'short_description' => 'Learn to cook Kimchi, Bibimbap, and Japchae',
                        'description' => 'Learn to cook authentic Korean dishes including Kimchi, Bibimbap, and Japchae with our professional chef.',
                        'includes' => 'All ingredients, Recipe book, Apron',
                        'excludes' => 'None',
                        'meeting_point' => 'Insadong Ssamziegil Entrance',
                    ],
                ],
            ],
            // 부산 투어
            [
                'vendor_keyword' => '부산',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'busan',
                'duration' => 540,
                'min_persons' => 1,
                'max_persons' => 20,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 35.1153,
                'longitude' => 129.0421,
                'rating' => 4.7,
                'review_count' => 98,
                'booking_count' => 280,
                'adult_price' => 70000,
                'child_price' => 50000,
                'start_time' => '08:30:00',
                'images' => [
                    'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=800',
                    'https://images.unsplash.com/photo-1548115184-bc6544d06a58?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '부산 해운대 & 광안리 투어',
                        'short_description' => '부산의 대표 해변 해운대와 광안리를 둘러보는 투어',
                        'description' => '부산의 대표 해변인 해운대와 광안리를 둘러보는 투어입니다. 아름다운 바다 풍경과 함께 부산의 매력을 느껴보세요.',
                        'includes' => '가이드, 차량, 해물탕 점심',
                        'excludes' => '개인 경비',
                        'meeting_point' => '부산역 광장',
                    ],
                    'en' => [
                        'title' => 'Busan Haeundae & Gwangalli Beach Tour',
                        'short_description' => 'Explore Busan\'s famous Haeundae and Gwangalli beaches',
                        'description' => 'Explore Busan\'s famous beaches - Haeundae and Gwangalli. Enjoy stunning ocean views and local seafood.',
                        'includes' => 'Guide, Transportation, Seafood lunch',
                        'excludes' => 'Personal expenses',
                        'meeting_point' => 'Busan Station Plaza',
                    ],
                ],
            ],
            [
                'vendor_keyword' => '부산',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'busan',
                'duration' => 420,
                'min_persons' => 1,
                'max_persons' => 15,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 35.0975,
                'longitude' => 129.0107,
                'rating' => 4.8,
                'review_count' => 112,
                'booking_count' => 310,
                'adult_price' => 55000,
                'child_price' => 40000,
                'start_time' => '09:30:00',
                'images' => [
                    'https://images.unsplash.com/photo-1548115184-bc6544d06a58?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '감천문화마을 포토투어',
                        'short_description' => '알록달록한 감천문화마을에서 인생샷 남기기',
                        'description' => '알록달록한 집들이 아름다운 감천문화마을에서 인생샷을 남기세요. 전문 가이드가 포토스팟을 안내해드립니다.',
                        'includes' => '가이드, 스낵',
                        'excludes' => '식사',
                        'meeting_point' => '감천문화마을 입구',
                    ],
                    'en' => [
                        'title' => 'Gamcheon Culture Village Photo Tour',
                        'short_description' => 'Capture beautiful photos at colorful Gamcheon Village',
                        'description' => 'Capture beautiful photos at the colorful Gamcheon Culture Village. Our guide will show you the best photo spots.',
                        'includes' => 'Guide, Snack',
                        'excludes' => 'Meals',
                        'meeting_point' => 'Gamcheon Culture Village Entrance',
                    ],
                ],
            ],
            // 제주 투어
            [
                'vendor_keyword' => '제주',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'jeju',
                'duration' => 600,
                'min_persons' => 1,
                'max_persons' => 12,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 33.5070,
                'longitude' => 126.4929,
                'rating' => 4.9,
                'review_count' => 203,
                'booking_count' => 520,
                'adult_price' => 89000,
                'child_price' => 65000,
                'start_time' => '08:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1555992336-fb0d29498b13?w=800',
                    'https://images.unsplash.com/photo-1590077428593-a55bb07c4665?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '제주 동부 일주 투어',
                        'short_description' => '성산일출봉, 만장굴, 월정리 해변 등 제주 동부 명소 투어',
                        'description' => '성산일출봉, 만장굴, 월정리 해변 등 제주 동부의 핵심 명소를 하루에 둘러보는 투어입니다.',
                        'includes' => '차량, 가이드, 입장료, 점심',
                        'excludes' => '개인 경비',
                        'meeting_point' => '제주공항 1층 로비',
                    ],
                    'en' => [
                        'title' => 'Jeju East Island Tour',
                        'short_description' => 'Visit Seongsan, Manjanggul Cave, and Woljeongri Beach',
                        'description' => 'Visit Seongsan Ilchulbong, Manjanggul Cave, and Woljeongri Beach in one day with our comprehensive east coast tour.',
                        'includes' => 'Transportation, Guide, Entrance fees, Lunch',
                        'excludes' => 'Personal expenses',
                        'meeting_point' => 'Jeju Airport 1F Lobby',
                    ],
                ],
            ],
            [
                'vendor_keyword' => '제주',
                'type' => ProductType::ACTIVITY,
                'region_code' => 'jeju',
                'duration' => 240,
                'min_persons' => 2,
                'max_persons' => 6,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 33.2411,
                'longitude' => 126.5602,
                'rating' => 4.7,
                'review_count' => 78,
                'booking_count' => 180,
                'adult_price' => 120000,
                'child_price' => 90000,
                'start_time' => '10:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '제주 스쿠버다이빙 체험',
                        'short_description' => '초보자도 안전하게 즐기는 제주 바다 스쿠버다이빙',
                        'description' => '제주의 아름다운 바다 속을 직접 탐험해보세요. 초보자도 안전하게 즐길 수 있는 스쿠버다이빙 체험입니다.',
                        'includes' => '장비 대여, 강습, 수중 사진',
                        'excludes' => '수영복',
                        'meeting_point' => '서귀포 잠수함 선착장',
                    ],
                    'en' => [
                        'title' => 'Jeju Scuba Diving Experience',
                        'short_description' => 'Safe scuba diving experience for beginners in Jeju',
                        'description' => 'Explore the beautiful underwater world of Jeju. Safe experience suitable for beginners.',
                        'includes' => 'Equipment rental, Instruction, Underwater photos',
                        'excludes' => 'Swimwear',
                        'meeting_point' => 'Seogwipo Submarine Pier',
                    ],
                ],
            ],
            // 경기 투어
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'gyeonggi',
                'duration' => 480,
                'min_persons' => 1,
                'max_persons' => 20,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 37.5547,
                'longitude' => 126.9707,
                'rating' => 4.6,
                'review_count' => 67,
                'booking_count' => 150,
                'adult_price' => 75000,
                'child_price' => 55000,
                'start_time' => '08:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                    'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => 'DMZ & 판문점 투어',
                        'short_description' => '분단의 역사를 직접 느낄 수 있는 DMZ 투어',
                        'description' => '분단의 역사를 직접 느낄 수 있는 DMZ와 판문점을 방문합니다. 한반도 평화의 의미를 되새겨보세요.',
                        'includes' => '차량, 가이드, 입장료, 점심',
                        'excludes' => '여권 필수 (외국인)',
                        'meeting_point' => '서울역 버스 터미널',
                    ],
                    'en' => [
                        'title' => 'DMZ & Panmunjom Tour',
                        'short_description' => 'Experience Korea\'s division history at the DMZ',
                        'description' => 'Visit the DMZ and Panmunjom to experience Korea\'s division history. Reflect on the meaning of peace.',
                        'includes' => 'Transportation, Guide, Entrance fees, Lunch',
                        'excludes' => 'Passport required (foreigners)',
                        'meeting_point' => 'Seoul Station Bus Terminal',
                    ],
                ],
            ],
            // 강원 투어
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::PACKAGE,
                'region_code' => 'gangwon',
                'duration' => 1440,
                'min_persons' => 2,
                'max_persons' => 10,
                'booking_type' => BookingType::REQUEST,
                'latitude' => 37.7648,
                'longitude' => 128.8962,
                'rating' => 4.8,
                'review_count' => 45,
                'booking_count' => 95,
                'adult_price' => 250000,
                'child_price' => 180000,
                'start_time' => '10:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '강릉 1박2일 패키지',
                        'short_description' => '동해바다와 커피거리를 즐기는 강릉 1박2일',
                        'description' => '동해바다와 커피거리, 경포대를 즐기는 강릉 1박2일 패키지입니다. 숙박과 주요 관광지가 포함됩니다.',
                        'includes' => '숙박, 조식, 가이드, 차량',
                        'excludes' => '석식, 개인 경비',
                        'meeting_point' => '강릉역',
                    ],
                    'en' => [
                        'title' => 'Gangneung 2D1N Package',
                        'short_description' => 'Enjoy East Sea and Coffee Street in Gangneung',
                        'description' => 'Enjoy the East Sea, Coffee Street, and Gyeongpodae with our 2-day Gangneung package including accommodation.',
                        'includes' => 'Accommodation, Breakfast, Guide, Transportation',
                        'excludes' => 'Dinner, Personal expenses',
                        'meeting_point' => 'Gangneung Station',
                    ],
                ],
            ],
            // 전북 투어
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'jeonbuk',
                'duration' => 540,
                'min_persons' => 1,
                'max_persons' => 15,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 35.8389,
                'longitude' => 127.1350,
                'rating' => 4.9,
                'review_count' => 134,
                'booking_count' => 380,
                'adult_price' => 68000,
                'child_price' => 48000,
                'start_time' => '09:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '전주 한옥마을 당일투어',
                        'short_description' => '전통 문화가 살아있는 전주 한옥마을 탐방',
                        'description' => '한국의 전통 문화가 살아있는 전주 한옥마을을 탐방합니다. 비빔밥과 한지 공예 체험이 포함됩니다.',
                        'includes' => '가이드, 점심(비빔밥), 한지공예 체험',
                        'excludes' => '개인 경비',
                        'meeting_point' => '전주역',
                    ],
                    'en' => [
                        'title' => 'Jeonju Hanok Village Day Tour',
                        'short_description' => 'Explore traditional Jeonju Hanok Village',
                        'description' => 'Explore the traditional Jeonju Hanok Village. Includes Bibimbap lunch and Hanji craft experience.',
                        'includes' => 'Guide, Lunch (Bibimbap), Hanji craft experience',
                        'excludes' => 'Personal expenses',
                        'meeting_point' => 'Jeonju Station',
                    ],
                ],
            ],
            // 경북 투어
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::DAY_TOUR,
                'region_code' => 'gyeongbuk',
                'duration' => 600,
                'min_persons' => 1,
                'max_persons' => 20,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 35.8562,
                'longitude' => 129.2242,
                'rating' => 4.7,
                'review_count' => 89,
                'booking_count' => 240,
                'adult_price' => 72000,
                'child_price' => 52000,
                'start_time' => '08:30:00',
                'images' => [
                    'https://images.unsplash.com/photo-1559825481-12a05cc00344?w=800',
                    'https://images.unsplash.com/photo-1534274988757-a28bf1a57c17?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => '경주 역사 탐방 투어',
                        'short_description' => '신라 천년의 역사가 살아있는 경주 탐방',
                        'description' => '신라 천년의 역사가 살아있는 경주를 탐방합니다. 불국사, 석굴암, 첨성대 등 주요 유적지를 방문합니다.',
                        'includes' => '차량, 가이드, 입장료, 점심',
                        'excludes' => '개인 경비',
                        'meeting_point' => '경주역',
                    ],
                    'en' => [
                        'title' => 'Gyeongju Historical Tour',
                        'short_description' => 'Explore the ancient Silla Kingdom capital',
                        'description' => 'Explore the ancient Silla Kingdom capital. Visit Bulguksa Temple, Seokguram Grotto, and Cheomseongdae Observatory.',
                        'includes' => 'Transportation, Guide, Entrance fees, Lunch',
                        'excludes' => 'Personal expenses',
                        'meeting_point' => 'Gyeongju Station',
                    ],
                ],
            ],
            // 추가 서울 투어
            [
                'vendor_keyword' => '서울',
                'type' => ProductType::ACTIVITY,
                'region_code' => 'seoul',
                'duration' => 120,
                'min_persons' => 1,
                'max_persons' => 20,
                'booking_type' => BookingType::INSTANT,
                'latitude' => 37.5512,
                'longitude' => 126.9882,
                'rating' => 4.5,
                'review_count' => 234,
                'booking_count' => 890,
                'adult_price' => 35000,
                'child_price' => 25000,
                'start_time' => '18:00:00',
                'images' => [
                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
                    'https://images.unsplash.com/photo-1517154421773-0529f29ea451?w=800',
                ],
                'translations' => [
                    'ko' => [
                        'title' => 'N서울타워 야경 투어',
                        'short_description' => '서울의 아름다운 야경을 한눈에',
                        'description' => '서울의 아름다운 야경을 한눈에 볼 수 있는 N서울타워 전망대 투어입니다.',
                        'includes' => '전망대 입장권',
                        'excludes' => '케이블카, 식사',
                        'meeting_point' => 'N서울타워 입구',
                    ],
                    'en' => [
                        'title' => 'N Seoul Tower Night View Tour',
                        'short_description' => 'Panoramic night views of Seoul',
                        'description' => 'Enjoy panoramic night views of Seoul from the N Seoul Tower observation deck.',
                        'includes' => 'Observatory entrance ticket',
                        'excludes' => 'Cable car, Meals',
                        'meeting_point' => 'N Seoul Tower Entrance',
                    ],
                ],
            ],
        ];
    }
}
