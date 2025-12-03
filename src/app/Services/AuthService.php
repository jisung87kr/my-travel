<?php

namespace App\Services;

use App\Enums\Language;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'preferred_language' => $data['preferred_language'] ?? Language::KO,
        ]);

        $user->assignRole(UserRole::TRAVELER->value);

        return $user;
    }

    public function login(string $email, string $password, bool $remember = false): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        if ($user->isBlocked()) {
            return false;
        }

        if (!$user->is_active) {
            return false;
        }

        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ], $remember);
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    public function findOrCreateFromSocialite(SocialiteUser $socialiteUser, string $provider): User
    {
        $user = User::where('provider', $provider)
            ->where('provider_id', $socialiteUser->getId())
            ->first();

        if ($user) {
            return $user;
        }

        // Check if user exists with same email
        $existingUser = User::where('email', $socialiteUser->getEmail())->first();

        if ($existingUser) {
            // Link social account to existing user
            $existingUser->update([
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId(),
                'avatar' => $socialiteUser->getAvatar(),
            ]);

            return $existingUser;
        }

        // Create new user
        $user = User::create([
            'name' => $socialiteUser->getName() ?? $socialiteUser->getNickname() ?? 'User',
            'email' => $socialiteUser->getEmail(),
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'avatar' => $socialiteUser->getAvatar(),
            'email_verified_at' => now(),
            'preferred_language' => Language::KO,
        ]);

        $user->assignRole(UserRole::TRAVELER->value);

        return $user;
    }

    public function registerAsVendor(User $user, array $vendorData): void
    {
        $user->vendor()->create([
            'company_name' => $vendorData['company_name'],
            'business_number' => $vendorData['business_number'] ?? null,
            'contact_phone' => $vendorData['contact_phone'] ?? null,
            'contact_email' => $vendorData['contact_email'] ?? $user->email,
            'description' => $vendorData['description'] ?? null,
        ]);

        $user->assignRole(UserRole::VENDOR->value);
    }
}
