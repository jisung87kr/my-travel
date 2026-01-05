<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // 대분류 카테고리
        $mainCategories = [
            [
                'slug' => 'experience',
                'translations' => [
                    'ko' => ['name' => '체험', 'description' => '다양한 체험 프로그램'],
                    'en' => ['name' => 'Experience', 'description' => 'Various experience programs'],
                ],
                'children' => [
                    [
                        'slug' => 'cultural',
                        'translations' => [
                            'ko' => ['name' => '문화체험', 'description' => '한국 문화를 직접 체험'],
                            'en' => ['name' => 'Cultural Experience', 'description' => 'Experience Korean culture firsthand'],
                        ],
                        'children' => [
                            ['slug' => 'hanbok', 'translations' => ['ko' => ['name' => '한복체험'], 'en' => ['name' => 'Hanbok Experience']]],
                            ['slug' => 'tea-ceremony', 'translations' => ['ko' => ['name' => '다도체험'], 'en' => ['name' => 'Tea Ceremony']]],
                            ['slug' => 'calligraphy', 'translations' => ['ko' => ['name' => '서예체험'], 'en' => ['name' => 'Calligraphy']]],
                        ],
                    ],
                    [
                        'slug' => 'food',
                        'translations' => [
                            'ko' => ['name' => '음식체험', 'description' => '한국 요리 만들기 체험'],
                            'en' => ['name' => 'Food Experience', 'description' => 'Korean cooking experience'],
                        ],
                        'children' => [
                            ['slug' => 'cooking-class', 'translations' => ['ko' => ['name' => '쿠킹클래스'], 'en' => ['name' => 'Cooking Class']]],
                            ['slug' => 'food-tour', 'translations' => ['ko' => ['name' => '푸드투어'], 'en' => ['name' => 'Food Tour']]],
                        ],
                    ],
                    [
                        'slug' => 'craft',
                        'translations' => [
                            'ko' => ['name' => '공예체험', 'description' => '전통 공예품 만들기'],
                            'en' => ['name' => 'Craft Experience', 'description' => 'Traditional craft making'],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'tour',
                'translations' => [
                    'ko' => ['name' => '투어', 'description' => '가이드 투어 프로그램'],
                    'en' => ['name' => 'Tour', 'description' => 'Guided tour programs'],
                ],
                'children' => [
                    [
                        'slug' => 'city-tour',
                        'translations' => [
                            'ko' => ['name' => '시티투어', 'description' => '도시 관광 투어'],
                            'en' => ['name' => 'City Tour', 'description' => 'Urban sightseeing tours'],
                        ],
                    ],
                    [
                        'slug' => 'nature-tour',
                        'translations' => [
                            'ko' => ['name' => '자연투어', 'description' => '자연 속 힐링 투어'],
                            'en' => ['name' => 'Nature Tour', 'description' => 'Healing tours in nature'],
                        ],
                    ],
                    [
                        'slug' => 'history-tour',
                        'translations' => [
                            'ko' => ['name' => '역사투어', 'description' => '역사 유적지 탐방'],
                            'en' => ['name' => 'History Tour', 'description' => 'Historical site exploration'],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'activity',
                'translations' => [
                    'ko' => ['name' => '액티비티', 'description' => '스포츠 및 레저 활동'],
                    'en' => ['name' => 'Activity', 'description' => 'Sports and leisure activities'],
                ],
                'children' => [
                    [
                        'slug' => 'water-sports',
                        'translations' => [
                            'ko' => ['name' => '수상스포츠', 'description' => '물 위에서 즐기는 스포츠'],
                            'en' => ['name' => 'Water Sports', 'description' => 'Sports on the water'],
                        ],
                    ],
                    [
                        'slug' => 'mountain-activity',
                        'translations' => [
                            'ko' => ['name' => '산악활동', 'description' => '등산 및 트레킹'],
                            'en' => ['name' => 'Mountain Activity', 'description' => 'Hiking and trekking'],
                        ],
                    ],
                    [
                        'slug' => 'winter-sports',
                        'translations' => [
                            'ko' => ['name' => '겨울스포츠', 'description' => '스키 및 스노보드'],
                            'en' => ['name' => 'Winter Sports', 'description' => 'Skiing and snowboarding'],
                        ],
                    ],
                ],
            ],
        ];

        $sortOrder = 0;
        foreach ($mainCategories as $mainData) {
            $this->createCategory($mainData, null, ProductCategory::LEVEL_MAIN, $sortOrder++);
        }
    }

    private function createCategory(array $data, ?int $parentId, int $level, int $sortOrder): ProductCategory
    {
        $category = ProductCategory::create([
            'parent_id' => $parentId,
            'slug' => $data['slug'],
            'level' => $level,
            'sort_order' => $sortOrder,
            'is_active' => true,
            'is_featured' => $level === ProductCategory::LEVEL_MAIN,
        ]);

        // Create translations
        foreach ($data['translations'] as $locale => $translation) {
            ProductCategoryTranslation::create([
                'category_id' => $category->id,
                'locale' => $locale,
                'name' => $translation['name'],
                'description' => $translation['description'] ?? null,
            ]);
        }

        // Create children
        if (isset($data['children'])) {
            $childSortOrder = 0;
            foreach ($data['children'] as $childData) {
                $this->createCategory($childData, $category->id, $level + 1, $childSortOrder++);
            }
        }

        return $category;
    }
}
