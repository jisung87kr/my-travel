<?php

namespace App\Http\Middleware;

use App\Enums\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');
        $supportedLocales = Language::values();

        if ($locale && in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        } else {
            // Check session or user preference
            $locale = session('locale', Language::default()->value);

            // Check Accept-Language header
            if (!session('locale') && $request->hasHeader('Accept-Language')) {
                $browserLocale = substr($request->header('Accept-Language'), 0, 2);
                if (in_array($browserLocale, $supportedLocales)) {
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
