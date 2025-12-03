<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;

class LogoutController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect('/')->with('success', '로그아웃되었습니다.');
    }
}
