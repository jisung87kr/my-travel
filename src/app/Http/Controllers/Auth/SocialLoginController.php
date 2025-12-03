<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    private const ALLOWED_PROVIDERS = ['google', 'kakao', 'apple'];

    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function redirect(string $provider): RedirectResponse
    {
        if (!$this->isValidProvider($provider)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        if (!$this->isValidProvider($provider)) {
            abort(404);
        }

        try {
            $socialiteUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors([
                'social' => '소셜 로그인 중 오류가 발생했습니다. 다시 시도해주세요.',
            ]);
        }

        $user = $this->authService->findOrCreateFromSocialite($socialiteUser, $provider);

        if ($user->isBlocked()) {
            return redirect('/login')->withErrors([
                'social' => '차단된 계정입니다. 관리자에게 문의해주세요.',
            ]);
        }

        if (!$user->is_active) {
            return redirect('/login')->withErrors([
                'social' => '비활성화된 계정입니다.',
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended('/')->with('success', '로그인되었습니다.');
    }

    private function isValidProvider(string $provider): bool
    {
        return in_array($provider, self::ALLOWED_PROVIDERS, true);
    }
}
