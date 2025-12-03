<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Register test routes
        Route::middleware(['auth', 'role:admin'])->get('/test-admin', function () {
            return 'admin-only';
        });

        Route::middleware(['auth', 'role:vendor'])->get('/test-vendor', function () {
            return 'vendor-only';
        });

        Route::middleware(['auth', 'role:vendor,admin'])->get('/test-vendor-or-admin', function () {
            return 'vendor-or-admin';
        });
    }

    public function test_admin_can_access_admin_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::ADMIN->value);

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertStatus(200);
        $response->assertSee('admin-only');
    }

    public function test_non_admin_cannot_access_admin_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::TRAVELER->value);

        $response = $this->actingAs($user)->get('/test-admin');

        $response->assertStatus(403);
    }

    public function test_vendor_can_access_vendor_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::VENDOR->value);

        $response = $this->actingAs($user)->get('/test-vendor');

        $response->assertStatus(200);
        $response->assertSee('vendor-only');
    }

    public function test_admin_can_access_vendor_or_admin_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::ADMIN->value);

        $response = $this->actingAs($user)->get('/test-vendor-or-admin');

        $response->assertStatus(200);
    }

    public function test_vendor_can_access_vendor_or_admin_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::VENDOR->value);

        $response = $this->actingAs($user)->get('/test-vendor-or-admin');

        $response->assertStatus(200);
    }

    public function test_traveler_cannot_access_vendor_or_admin_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::TRAVELER->value);

        $response = $this->actingAs($user)->get('/test-vendor-or-admin');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/test-admin');

        // Laravel's auth middleware redirects unauthenticated users to login page
        $response->assertRedirect('/login');
    }
}
