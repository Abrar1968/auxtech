<?php

use App\Services\Seo\SeoManager;
use App\Services\Settings\SettingsRepository;

if (! function_exists('setting')) {
    /** Read a site setting, e.g. setting('company.name'). */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingsRepository::class)->get($key, $default);
    }
}

if (! function_exists('setting_t')) {
    /** Read a translatable setting in the current locale, falling back to English. */
    function setting_t(string $key, ?string $default = null): ?string
    {
        return app(SettingsRepository::class)->translated($key, $default);
    }
}

if (! function_exists('lroute')) {
    /** Route URL in the current (or given) locale: English has no prefix, Bangla uses /bn. */
    function lroute(string $name, mixed $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        $locale ??= app()->getLocale();
        $prefix = $locale === config('auxtech.locales.default') ? '' : $locale.'.';

        return route($prefix.$name, $parameters, $absolute);
    }
}

if (! function_exists('seo')) {
    function seo(): SeoManager
    {
        return app(SeoManager::class);
    }
}

if (! function_exists('page_intro')) {
    /** @return array{heading: ?string, lead: ?string} on-page heading of a listing page */
    function page_intro(string $key): array
    {
        return ['heading' => setting_t("pages.{$key}.heading"), 'lead' => setting_t("pages.{$key}.lead")];
    }
}

if (! function_exists('section_copy')) {
    /** @return array{title: ?string, lead: ?string} heading of a home page section */
    function section_copy(string $key): array
    {
        $copy = (array) setting("home.copy.{$key}", []);

        return ['title' => tr($copy['title'] ?? null), 'lead' => tr($copy['lead'] ?? null)];
    }
}

if (! function_exists('tr_rows')) {
    /**
     * Resolve the translatable fields of a settings list for the current locale.
     *
     * @param  array<int, array<string, mixed>>|null  $rows
     * @return list<array<string, mixed>>
     */
    function tr_rows(?array $rows): array
    {
        return collect($rows ?? [])->map(fn (array $row) => array_map(fn ($value) => is_array($value) ? tr($value) : $value, $row))
            ->filter(fn (array $row) => filled($row['title'] ?? null))->values()->all();
    }
}

if (! function_exists('tr')) {
    /**
     * Resolve a translatable value ({"en": "...", "bn": "..."}) for the
     * current locale with an English fallback.
     */
    function tr(mixed $value, ?string $locale = null): ?string
    {
        if (! is_array($value)) {
            return $value === null ? null : (string) $value;
        }

        $locale ??= app()->getLocale();
        $text = $value[$locale] ?? null;

        if ($text === null || $text === '') {
            $text = $value['en'] ?? null;
        }

        return $text === null ? null : (string) $text;
    }
}
