<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\VendorStatus;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'user' => [
                    'name' => '서울투어',
                    'email' => 'seoul@vendor.com',
                    'password' => bcrypt('password'),
                ],
                'vendor' => [
                    'company_name' => '서울투어 주식회사',
                    'business_number' => '123-45-67890',
                    'contact_phone' => '02-1234-5678',
                    'contact_email' => 'contact@seoultour.com',
                    'description' => '서울의 아름다운 명소를 안내하는 전문 여행사입니다.',
                    'status' => VendorStatus::APPROVED,
                    'approved_at' => now(),
                ],
            ],
            [
                'user' => [
                    'name' => '부산여행사',
                    'email' => 'busan@vendor.com',
                    'password' => bcrypt('password'),
                ],
                'vendor' => [
                    'company_name' => '부산여행사',
                    'business_number' => '234-56-78901',
                    'contact_phone' => '051-234-5678',
                    'contact_email' => 'contact@busantravel.com',
                    'description' => '부산의 바다와 문화를 경험할 수 있는 여행 상품을 제공합니다.',
                    'status' => VendorStatus::APPROVED,
                    'approved_at' => now(),
                ],
            ],
            [
                'user' => [
                    'name' => '제주관광',
                    'email' => 'jeju@vendor.com',
                    'password' => bcrypt('password'),
                ],
                'vendor' => [
                    'company_name' => '제주관광 주식회사',
                    'business_number' => '345-67-89012',
                    'contact_phone' => '064-345-6789',
                    'contact_email' => 'contact@jejutour.com',
                    'description' => '제주도의 자연과 문화를 체험할 수 있는 다양한 투어를 제공합니다.',
                    'status' => VendorStatus::APPROVED,
                    'approved_at' => now(),
                ],
            ],
        ];

        foreach ($vendors as $data) {
            $user = User::create($data['user']);
            $user->assignRole(UserRole::VENDOR->value);

            Vendor::create([
                'user_id' => $user->id,
                ...$data['vendor'],
            ]);
        }
    }
}
