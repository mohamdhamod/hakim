<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LocaleFromUrl
{
    /**
     * Handle an incoming request.
     *
     * Priority: URL segment {locale} > session('applocale') > app fallback
     */
    public function handle(Request $request, Closure $next)
    {
        $supported = array_keys(Config::get('languages', []));

        // Try from route parameter or first segment
        $locale = $request->route('locale');
        if (!$locale) {
            $firstSegment = $request->segment(1);
            if ($firstSegment && in_array($firstSegment, $supported, true)) {
                $locale = $firstSegment;
            }
        }

        if ($locale && in_array($locale, $supported, true)) {
            App::setLocale($locale);
            Session::put('applocale', $locale);
        } elseif (Session::has('applocale') && in_array(Session::get('applocale'), $supported, true)) {
            App::setLocale(Session::get('applocale'));
        } else {
            App::setLocale(config('app.fallback_locale'));
        }

        // Ensure URL generation fills {locale} automatically
        URL::defaults(['locale' => App::getLocale()]);

        return $next($request);
    }
}
