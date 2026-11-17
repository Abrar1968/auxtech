<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * When Settings > Security requires it, Super Admins and Admins must
 * finish setting up two-factor sign-in before using the admin panel.
 */
class RequireTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && setting('security.require_two_factor') && ! $user->hasTwoFactor()
            && $user->hasAnyRole(['Super Admin', 'Admin']) && ! $request->routeIs('admin.profile')) {
            return redirect()->route('admin.profile')->with('error', 'Set up two-factor sign-in to continue.');
        }

        return $next($request);
    }
}
