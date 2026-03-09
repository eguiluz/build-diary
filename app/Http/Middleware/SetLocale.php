<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check user preference first
        if (Auth::check() && Auth::user()->locale) {
            App::setLocale(Auth::user()->locale);
        }
        // Then check session
        elseif ($request->session()->has('locale')) {
            App::setLocale($request->session()->get('locale'));
        }
        // Finally check browser preference
        elseif ($request->hasHeader('Accept-Language')) {
            $browserLocale = substr($request->header('Accept-Language'), 0, 2);
            if (in_array($browserLocale, ['es', 'en'])) {
                App::setLocale($browserLocale);
            }
        }

        return $next($request);
    }
}
