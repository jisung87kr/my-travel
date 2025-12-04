<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['ko', 'en', 'zh', 'ja'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if ($locale && in_array($locale, $this->supportedLocales)) {
            app()->setLocale($locale);
        } else {
            // Check session or user preference
            $locale = session('locale', config('app.locale', 'ko'));

            // Check Accept-Language header
            if (!session('locale') && $request->hasHeader('Accept-Language')) {
                $browserLocale = substr($request->header('Accept-Language'), 0, 2);
                if (in_array($browserLocale, $this->supportedLocales)) {
                    $locale = $browserLocale;
                }
            }

            // Check authenticated user's preference
            if (auth()->check() && auth()->user()->preferred_language) {
                $locale = auth()->user()->preferred_language->value;
            }

            app()->setLocale($locale);
        }

        return $next($request);
    }
}
