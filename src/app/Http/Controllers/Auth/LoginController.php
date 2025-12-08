<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function showForm(Request $request): View
    {
        // Store redirect URL in session for post-login redirect
        if ($request->has('redirect')) {
            $request->session()->put('url.intended', $request->get('redirect'));
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (!$this->authService->login(
            $credentials['email'],
            $credentials['password'],
            $request->boolean('remember')
        )) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => '이메일 또는 비밀번호가 일치하지 않거나 계정이 비활성화되었습니다.']);
        }

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', '로그인되었습니다.');
    }
}
