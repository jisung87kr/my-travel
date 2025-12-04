<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mytravel.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole(UserRole::ADMIN->value);

        // Create test travelers
        $traveler = User::factory()->create([
            'name' => 'Test Traveler',
            'email' => 'traveler@test.com',
            'password' => bcrypt('password'),
        ]);
        $traveler->assignRole(UserRole::TRAVELER->value);

        // Create additional test travelers for reviews
        $testTravelers = [
            ['name' => '김민수', 'email' => 'minsu@test.com'],
            ['name' => '이영희', 'email' => 'younghee@test.com'],
            ['name' => '박지훈', 'email' => 'jihoon@test.com'],
            ['name' => '최수진', 'email' => 'sujin@test.com'],
            ['name' => '정대현', 'email' => 'daehyun@test.com'],
        ];

        foreach ($testTravelers as $data) {
            $user = User::factory()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
            ]);
            $user->assignRole(UserRole::TRAVELER->value);
        }

        // Call vendor, product, booking, and review seeders
        $this->call([
            VendorSeeder::class,
            ProductSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
