<?php

namespace Tests\Unit\Services;

use App\Enums\Language;
use App\Enums\UserRole;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->authService = new AuthService();
    }

    public function test_register_creates_user_with_traveler_role(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $user = $this->authService->register($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue($user->hasRole(UserRole::TRAVELER->value));
    }

    public function test_register_with_preferred_language(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'preferred_language' => Language::EN,
        ];

        $user = $this->authService->register($data);

        $this->assertEquals(Language::EN, $user->preferred_language);
    }

    public function test_register_defaults_to_korean_language(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $user = $this->authService->register($data);

        $this->assertEquals(Language::KO, $user->preferred_language);
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $result = $this->authService->login($user->email, 'password');

        $this->assertTrue($result);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $result = $this->authService->login($user->email, 'wrong-password');

        $this->assertFalse($result);
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $result = $this->authService->login('nonexistent@example.com', 'password');

        $this->assertFalse($result);
    }

    public function test_login_fails_for_blocked_user(): void
    {
        $user = User::factory()->blocked()->create();

        $result = $this->authService->login($user->email, 'password');

        $this->assertFalse($result);
    }

    public function test_login_fails_for_inactive_user(): void
    {
        $user = User::factory()->inactive()->create();

        $result = $this->authService->login($user->email, 'password');

        $this->assertFalse($result);
    }

    public function test_find_or_create_from_socialite_creates_new_user(): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('12345');
        $socialiteUser->shouldReceive('getEmail')->andReturn('social@example.com');
        $socialiteUser->shouldReceive('getName')->andReturn('Social User');
        $socialiteUser->shouldReceive('getNickname')->andReturn(null);
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

        $user = $this->authService->findOrCreateFromSocialite($socialiteUser, 'google');

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('social@example.com', $user->email);
        $this->assertEquals('Social User', $user->name);
        $this->assertEquals('google', $user->provider);
        $this->assertEquals('12345', $user->provider_id);
        $this->assertTrue($user->hasRole(UserRole::TRAVELER->value));
    }

    public function test_find_or_create_from_socialite_returns_existing_user(): void
    {
        $existingUser = User::factory()->create([
            'provider' => 'google',
            'provider_id' => '12345',
        ]);

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('12345');

        $user = $this->authService->findOrCreateFromSocialite($socialiteUser, 'google');

        $this->assertEquals($existingUser->id, $user->id);
    }

    public function test_find_or_create_from_socialite_links_existing_email(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'existing@example.com',
            'provider' => null,
            'provider_id' => null,
        ]);

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('12345');
        $socialiteUser->shouldReceive('getEmail')->andReturn('existing@example.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

        $user = $this->authService->findOrCreateFromSocialite($socialiteUser, 'google');

        $this->assertEquals($existingUser->id, $user->id);
        $this->assertEquals('google', $user->fresh()->provider);
        $this->assertEquals('12345', $user->fresh()->provider_id);
    }

    public function test_register_as_vendor_creates_vendor_and_assigns_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::TRAVELER->value);

        $vendorData = [
            'company_name' => 'Test Company',
            'business_number' => '123-45-67890',
            'contact_phone' => '010-1234-5678',
            'description' => 'Test description',
        ];

        $this->authService->registerAsVendor($user, $vendorData);

        $this->assertTrue($user->fresh()->hasRole(UserRole::VENDOR->value));
        $this->assertNotNull($user->fresh()->vendor);
        $this->assertEquals('Test Company', $user->fresh()->vendor->company_name);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
