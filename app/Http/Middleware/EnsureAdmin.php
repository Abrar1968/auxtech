<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Only active users who hold at least one role may use the admin panel. */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active || $user->roles->isEmpty()) {
            auth()->guard('web')->logout();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
