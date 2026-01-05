<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\RegionTranslation;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedKorea();
    }

    private function seedKorea(): void
    {
        // Country: South Korea
        $korea = Region::create([
            'code' => 'kr',
            'slug' => 'south-korea',
            'level' => Region::LEVEL_COUNTRY,
            'latitude' => 37.5665,
            'longitude' => 126.9780,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
            'timezone' => 'Asia/Seoul',
        ]);

        $this->createTranslations($korea, [
            'ko' => ['name' => '대한민국', 'short_name' => '한국', 'description' => '동아시아의 한반도 남쪽에 위치한 국가'],
            'en' => ['name' => 'South Korea', 'short_name' => 'Korea', 'description' => 'A country in East Asia on the Korean Peninsula'],
        ]);

        // Provinces (matches existing Region enum)
        $provinces = [
            [
                'code' => 'seoul',
                'lat' => 37.5665,
                'lng' => 126.9780,
                'featured' => true,
                'sort' => 1,
                'translations' => [
                    'ko' => ['name' => '서울특별시', 'short_name' => '서울'],
                    'en' => ['name' => 'Seoul', 'short_name' => 'Seoul'],
                ],
                'districts' => [
                    ['code' => 'gangnam-gu', 'ko' => '강남구', 'en' => 'Gangnam-gu', 'lat' => 37.5172, 'lng' => 127.0473],
                    ['code' => 'seocho-gu', 'ko' => '서초구', 'en' => 'Seocho-gu', 'lat' => 37.4837, 'lng' => 127.0324],
                    ['code' => 'jongno-gu', 'ko' => '종로구', 'en' => 'Jongno-gu', 'lat' => 37.5735, 'lng' => 126.9790],
                    ['code' => 'jung-gu', 'ko' => '중구', 'en' => 'Jung-gu', 'lat' => 37.5636, 'lng' => 126.9975],
                    ['code' => 'mapo-gu', 'ko' => '마포구', 'en' => 'Mapo-gu', 'lat' => 37.5663, 'lng' => 126.9014],
                    ['code' => 'yongsan-gu', 'ko' => '용산구', 'en' => 'Yongsan-gu', 'lat' => 37.5324, 'lng' => 126.9906],
                ],
            ],
            [
                'code' => 'busan',
                'lat' => 35.1796,
                'lng' => 129.0756,
                'featured' => true,
                'sort' => 2,
                'translations' => [
                    'ko' => ['name' => '부산광역시', 'short_name' => '부산'],
                    'en' => ['name' => 'Busan', 'short_name' => 'Busan'],
                ],
                'districts' => [
                    ['code' => 'haeundae-gu', 'ko' => '해운대구', 'en' => 'Haeundae-gu', 'lat' => 35.1631, 'lng' => 129.1635],
                    ['code' => 'seo-gu', 'ko' => '서구', 'en' => 'Seo-gu', 'lat' => 35.0979, 'lng' => 129.0244],
                    ['code' => 'nam-gu', 'ko' => '남구', 'en' => 'Nam-gu', 'lat' => 35.1368, 'lng' => 129.0843],
                ],
            ],
            [
                'code' => 'gyeonggi',
                'lat' => 37.4138,
                'lng' => 127.5183,
                'featured' => true,
                'sort' => 3,
                'translations' => [
                    'ko' => ['name' => '경기도', 'short_name' => '경기'],
                    'en' => ['name' => 'Gyeonggi-do', 'short_name' => 'Gyeonggi'],
                ],
                'districts' => [
                    ['code' => 'suwon', 'ko' => '수원시', 'en' => 'Suwon', 'lat' => 37.2636, 'lng' => 127.0286],
                    ['code' => 'seongnam', 'ko' => '성남시', 'en' => 'Seongnam', 'lat' => 37.4449, 'lng' => 127.1389],
                    ['code' => 'goyang', 'ko' => '고양시', 'en' => 'Goyang', 'lat' => 37.6584, 'lng' => 126.8320],
                ],
            ],
            [
                'code' => 'gangwon',
                'lat' => 37.8228,
                'lng' => 128.1555,
                'featured' => true,
                'sort' => 4,
                'translations' => [
                    'ko' => ['name' => '강원특별자치도', 'short_name' => '강원'],
                    'en' => ['name' => 'Gangwon State', 'short_name' => 'Gangwon'],
                ],
                'districts' => [
                    ['code' => 'sokcho', 'ko' => '속초시', 'en' => 'Sokcho', 'lat' => 38.2070, 'lng' => 128.5918],
                    ['code' => 'gangneung', 'ko' => '강릉시', 'en' => 'Gangneung', 'lat' => 37.7519, 'lng' => 128.8761],
                    ['code' => 'pyeongchang', 'ko' => '평창군', 'en' => 'Pyeongchang', 'lat' => 37.3705, 'lng' => 128.3906],
                ],
            ],
            [
                'code' => 'chungbuk',
                'lat' => 36.6357,
                'lng' => 127.4912,
                'featured' => false,
                'sort' => 5,
                'translations' => [
                    'ko' => ['name' => '충청북도', 'short_name' => '충북'],
                    'en' => ['name' => 'Chungcheongbuk-do', 'short_name' => 'Chungbuk'],
                ],
            ],
            [
                'code' => 'chungnam',
                'lat' => 36.6588,
                'lng' => 126.6728,
                'featured' => false,
                'sort' => 6,
                'translations' => [
                    'ko' => ['name' => '충청남도', 'short_name' => '충남'],
                    'en' => ['name' => 'Chungcheongnam-do', 'short_name' => 'Chungnam'],
                ],
            ],
            [
                'code' => 'jeonbuk',
                'lat' => 35.8203,
                'lng' => 127.1088,
                'featured' => true,
                'sort' => 7,
                'translations' => [
                    'ko' => ['name' => '전북특별자치도', 'short_name' => '전북'],
                    'en' => ['name' => 'Jeonbuk State', 'short_name' => 'Jeonbuk'],
                ],
                'districts' => [
                    ['code' => 'jeonju', 'ko' => '전주시', 'en' => 'Jeonju', 'lat' => 35.8242, 'lng' => 127.1480],
                ],
            ],
            [
                'code' => 'jeonnam',
                'lat' => 34.8161,
                'lng' => 126.4629,
                'featured' => false,
                'sort' => 8,
                'translations' => [
                    'ko' => ['name' => '전라남도', 'short_name' => '전남'],
                    'en' => ['name' => 'Jeollanam-do', 'short_name' => 'Jeonnam'],
                ],
                'districts' => [
                    ['code' => 'yeosu', 'ko' => '여수시', 'en' => 'Yeosu', 'lat' => 34.7604, 'lng' => 127.6622],
                ],
            ],
            [
                'code' => 'gyeongbuk',
                'lat' => 36.4919,
                'lng' => 128.8889,
                'featured' => true,
                'sort' => 9,
                'translations' => [
                    'ko' => ['name' => '경상북도', 'short_name' => '경북'],
                    'en' => ['name' => 'Gyeongsangbuk-do', 'short_name' => 'Gyeongbuk'],
                ],
                'districts' => [
                    ['code' => 'gyeongju', 'ko' => '경주시', 'en' => 'Gyeongju', 'lat' => 35.8562, 'lng' => 129.2247],
                    ['code' => 'andong', 'ko' => '안동시', 'en' => 'Andong', 'lat' => 36.5684, 'lng' => 128.7294],
                ],
            ],
            [
                'code' => 'gyeongnam',
                'lat' => 35.4606,
                'lng' => 128.2132,
                'featured' => false,
                'sort' => 10,
                'translations' => [
                    'ko' => ['name' => '경상남도', 'short_name' => '경남'],
                    'en' => ['name' => 'Gyeongsangnam-do', 'short_name' => 'Gyeongnam'],
                ],
                'districts' => [
                    ['code' => 'tongyeong', 'ko' => '통영시', 'en' => 'Tongyeong', 'lat' => 34.8544, 'lng' => 128.4330],
                ],
            ],
            [
                'code' => 'jeju',
                'lat' => 33.4996,
                'lng' => 126.5312,
                'featured' => true,
                'sort' => 11,
                'translations' => [
                    'ko' => ['name' => '제주특별자치도', 'short_name' => '제주'],
                    'en' => ['name' => 'Jeju Special Self-Governing Province', 'short_name' => 'Jeju'],
                ],
                'districts' => [
                    ['code' => 'jeju-city', 'ko' => '제주시', 'en' => 'Jeju City', 'lat' => 33.4996, 'lng' => 126.5312],
                    ['code' => 'seogwipo', 'ko' => '서귀포시', 'en' => 'Seogwipo', 'lat' => 33.2541, 'lng' => 126.5600],
                ],
            ],
        ];

        foreach ($provinces as $provinceData) {
            $province = Region::create([
                'parent_id' => $korea->id,
                'code' => $provinceData['code'],
                'slug' => $provinceData['code'],
                'level' => Region::LEVEL_PROVINCE,
                'latitude' => $provinceData['lat'],
                'longitude' => $provinceData['lng'],
                'is_active' => true,
                'is_featured' => $provinceData['featured'],
                'sort_order' => $provinceData['sort'],
                'timezone' => 'Asia/Seoul',
            ]);

            $this->createTranslations($province, $provinceData['translations']);

            // Create districts if present
            if (! empty($provinceData['districts'])) {
                foreach ($provinceData['districts'] as $index => $districtData) {
                    $district = Region::create([
                        'parent_id' => $province->id,
                        'code' => $districtData['code'],
                        'slug' => $provinceData['code'] . '-' . $districtData['code'],
                        'level' => Region::LEVEL_DISTRICT,
                        'latitude' => $districtData['lat'] ?? null,
                        'longitude' => $districtData['lng'] ?? null,
                        'is_active' => true,
                        'sort_order' => $index + 1,
                        'timezone' => 'Asia/Seoul',
                    ]);

                    $this->createTranslations($district, [
                        'ko' => ['name' => $districtData['ko']],
                        'en' => ['name' => $districtData['en']],
                    ]);
                }
            }
        }
    }

    private function createTranslations(Region $region, array $translations): void
    {
        foreach ($translations as $locale => $data) {
            RegionTranslation::create([
                'region_id' => $region->id,
                'locale' => $locale,
                'name' => $data['name'],
                'short_name' => $data['short_name'] ?? null,
                'description' => $data['description'] ?? null,
            ]);
        }
    }
}
