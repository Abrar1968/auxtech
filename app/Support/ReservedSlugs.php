<?php

namespace App\Support;

/** Top-level URL segments owned by the application; CMS pages cannot use them. */
final class ReservedSlugs
{
    /** @return list<string> */
    public static function all(): array
    {
        return [
            'admin', 'api', 'bn', 'en', 'work', 'services', 'solutions', 'industries', 'pricing', 'estimate', 'about',
            'blog', 'faq', 'contact', 'auxify', 'install', 'login', 'logout', 'go', 'up', 'storage', 'uploads', 'build',
            'brand', 'sitemap', 'robots', 'llms', 'feed', 'search', 'broadcasting', 'two-factor-challenge',
            'forgot-password', 'reset-password', 'user',
        ];
    }
}
