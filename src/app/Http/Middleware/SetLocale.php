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
            // Priority: session > user preference > browser > default
            $sessionLocale = session('locale');

            if ($sessionLocale && in_array($sessionLocale, $supportedLocales)) {
                // Session locale takes priority (user explicitly selected)
                $locale = $sessionLocale;
            } elseif (auth()->check() && auth()->user()->preferred_language) {
                // Fall back to authenticated user's preference
                $locale = auth()->user()->preferred_language->value;
            } elseif ($request->hasHeader('Accept-Language')) {
                // Fall back to browser Accept-Language header
                $browserLocale = substr($request->header('Accept-Language'), 0, 2);
                $locale = in_array($browserLocale, $supportedLocales) ? $browserLocale : Language::default()->value;
            } else {
                // Default locale
                $locale = Language::default()->value;
            }

            app()->setLocale($locale);
        }

        return $next($request);
    }
}
