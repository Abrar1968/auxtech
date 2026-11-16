<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** English has no URL prefix; Bangla lives under /bn. */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('auxtech.locales.supported');
        $segment = $request->segment(1);

        app()->setLocale(in_array($segment, $supported, true) && $segment !== config('auxtech.locales.default')
            ? $segment
            : config('auxtech.locales.default'));

        return $next($request);
    }
}
