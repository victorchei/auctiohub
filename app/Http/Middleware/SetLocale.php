<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if (in_array($lang, ['uk', 'en'], true)) {
                cookie()->queue('locale', $lang, 60 * 24 * 365);
                app()->setLocale($lang);

                return redirect()->back()->withCookie(cookie('locale', $lang, 60 * 24 * 365));
            }
        }

        $cookieLocale = $request->cookie('locale');
        if (in_array($cookieLocale, ['uk', 'en'], true)) {
            app()->setLocale($cookieLocale);
        }

        return $next($request);
    }
}
