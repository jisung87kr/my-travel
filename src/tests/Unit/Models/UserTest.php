<?php

namespace Tests\Unit\Models;

use App\Enums\Language;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_preferred_language_is_cast_to_enum(): void
    {
        $user = User::factory()->create([
            'preferred_language' => 'en',
        ]);

        $this->assertInstanceOf(Language::class, $user->preferred_language);
        $this->assertEquals(Language::EN, $user->preferred_language);
    }

    public function test_user_can_be_assigned_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::TRAVELER->value);

        $this->assertTrue($user->hasRole(UserRole::TRAVELER->value));
    }

    public function test_user_is_not_blocked_by_default(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isBlocked());
        $this->assertTrue($user->canBook());
    }

    public function test_user_no_show_count_increments(): void
    {
        $user = User::factory()->create(['no_show_count' => 0]);

        $user->incrementNoShowCount();
        $user->refresh();

        $this->assertEquals(1, $user->no_show_count);
        $this->assertFalse($user->isBlocked());
    }

    public function test_user_gets_blocked_after_three_no_shows(): void
    {
        $user = User::factory()->create(['no_show_count' => 2]);

        $user->incrementNoShowCount();
        $user->refresh();

        $this->assertEquals(3, $user->no_show_count);
        $this->assertTrue($user->isBlocked());
        $this->assertNotNull($user->blocked_at);
        $this->assertFalse($user->canBook());
    }

    public function test_blocked_user_cannot_book(): void
    {
        $user = User::factory()->create([
            'is_blocked' => true,
            'blocked_at' => now(),
        ]);

        $this->assertFalse($user->canBook());
    }

    public function test_inactive_user_cannot_book(): void
    {
        $user = User::factory()->create([
            'is_active' => false,
        ]);

        $this->assertFalse($user->canBook());
    }

    public function test_user_has_vendor_relationship(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Vendor::class, $user->vendor);
        $this->assertEquals($vendor->id, $user->vendor->id);
    }

    public function test_user_soft_deletes(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);
    }
}
