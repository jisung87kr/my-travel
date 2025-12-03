<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserActiveMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_user_can_access_protected_routes(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_blocked_user_is_logged_out_and_redirected(): void
    {
        $user = User::factory()->blocked()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    public function test_inactive_user_is_logged_out_and_redirected(): void
    {
        $user = User::factory()->inactive()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
