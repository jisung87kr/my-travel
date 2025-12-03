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

        // Create test traveler
        $traveler = User::factory()->create([
            'name' => 'Test Traveler',
            'email' => 'traveler@test.com',
            'password' => bcrypt('password'),
        ]);
        $traveler->assignRole(UserRole::TRAVELER->value);
    }
}
