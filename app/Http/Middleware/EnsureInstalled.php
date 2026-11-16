<?php

namespace App\Http\Middleware;

use App\Support\Installation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Until the browser installer has run, every URL leads to it. */
class EnsureInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Installation::done() && ! $request->is('install', 'install/*', 'up')) {
            return redirect('/install');
        }

        return $next($request);
    }
}
