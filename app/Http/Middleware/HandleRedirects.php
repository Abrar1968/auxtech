<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/** Applies admin-managed and automatic (slug change) redirects. */
class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->path(), '/');
        $prefix = '';

        // Redirects are stored without the locale prefix and apply to both languages.
        if ($path === '/bn' || str_starts_with($path, '/bn/')) {
            $prefix = '/bn';
            $path = substr($path, 3) ?: '/';
        }

        try {
            $target = Redirect::map()[$path] ?? null;
        } catch (Throwable) {
            $target = null;
        }

        if ($target === null) {
            return $next($request);
        }

        [$to, $status] = $target;

        Redirect::query()->where('from_path', $path)->update(['hits' => \DB::raw('hits + 1'), 'last_hit_at' => now()]);

        return redirect(str_starts_with($to, '/') ? $prefix.$to : $to, $status);
    }
}
