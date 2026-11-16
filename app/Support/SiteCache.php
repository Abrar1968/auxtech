<?php

namespace App\Support;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Support\Facades\Cache;

/** Cached public output that must be rebuilt when content changes. */
final class SiteCache
{
    public const FEED_KEYS = ['feeds.sitemap', 'feeds.llms', 'feeds.llms-full', 'feeds.rss'];

    public static function flush(): void
    {
        HandleInertiaRequests::forgetNavigation();

        foreach (self::FEED_KEYS as $key) {
            Cache::forget($key);
        }
    }
}
