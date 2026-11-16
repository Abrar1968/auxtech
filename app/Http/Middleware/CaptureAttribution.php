<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remembers where a visitor first came from (UTM, click IDs, referrer,
 * landing page) in a first-party cookie so a later lead can be attributed.
 */
class CaptureAttribution
{
    public const COOKIE = 'aux_attr';

    public const VISITOR_COOKIE = 'aux_vid';

    private const KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') || $request->expectsJson() && ! $request->header('X-Inertia')) {
            return $response;
        }

        if (! $request->cookie(self::VISITOR_COOKIE)) {
            $response->headers->setCookie(cookie(self::VISITOR_COOKIE, (string) Str::uuid(), 60 * 24 * 365));
        }

        $incoming = array_filter($request->only(self::KEYS), fn ($v) => is_string($v) && $v !== '');
        $existing = self::read($request);

        // First touch wins, unless a new campaign parameter arrives.
        if ($existing !== [] && $incoming === []) {
            return $response;
        }

        $referrer = (string) $request->headers->get('referer', '');
        $external = $referrer !== '' && parse_url($referrer, PHP_URL_HOST) !== $request->getHost();

        $data = array_map(fn ($v) => Str::limit((string) $v, 250, ''), $incoming) + [
            'referrer' => $external ? Str::limit($referrer, 480, '') : ($existing['referrer'] ?? null),
            'landing_url' => $existing['landing_url'] ?? Str::limit($request->fullUrl(), 480, ''),
        ];

        $response->headers->setCookie(cookie(self::COOKIE, json_encode($data), 60 * 24 * 90));

        return $response;
    }

    /** @return array<string, string|null> */
    public static function read(Request $request): array
    {
        $raw = $request->cookie(self::COOKIE);
        $data = is_string($raw) ? json_decode($raw, true) : null;

        return is_array($data)
            ? array_intersect_key($data, array_flip([...self::KEYS, 'referrer', 'landing_url']))
            : [];
    }
}
