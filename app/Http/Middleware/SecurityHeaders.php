<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // A fresh nonce per request; Vite adds it to the script tags it prints.
        $nonce = Vite::useCspNonce();

        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('Content-Security-Policy', $this->policy($nonce, $request->isSecure()));

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if ($request->is('admin', 'admin/*', 'install', 'install/*')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }

    /**
     * Scripts must carry the nonce; scripts they load (Vite chunks, Google Tag
     * Manager, Meta Pixel, Clarity, Pusher) are trusted through 'strict-dynamic'.
     * 'self' and https: are fallbacks for browsers without CSP level 3.
     */
    private function policy(string $nonce, bool $secure): string
    {
        $directives = [
            'default-src' => "'self'",
            'script-src' => "'self' 'nonce-{$nonce}' 'strict-dynamic' https:",
            // Vue and Motion set inline style attributes.
            'style-src' => "'self' 'unsafe-inline'",
            'img-src' => "'self' data: blob: https:",
            'font-src' => "'self' data:",
            // Home page background video (self-hosted by default; a full https link may be set in Settings).
            'media-src' => "'self' blob: https:",
            'connect-src' => "'self' https: wss:",
            'frame-src' => "'self' https://www.googletagmanager.com https://www.youtube.com https://www.youtube-nocookie.com https://www.google.com",
            'object-src' => "'none'",
            'base-uri' => "'self'",
            'form-action' => "'self'",
            'frame-ancestors' => "'self'",
        ];

        $policy = collect($directives)->map(fn (string $value, string $name) => "{$name} {$value}")->implode('; ');

        return $secure ? $policy.'; upgrade-insecure-requests' : $policy;
    }
}
