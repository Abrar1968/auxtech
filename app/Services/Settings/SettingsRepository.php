<?php

namespace App\Services\Settings;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Throwable;

/**
 * Site settings: defaults from config/settings.php overridden by the
 * `settings` table. Read through the setting() helper.
 */
class SettingsRepository
{
    public const CACHE_KEY = 'settings.all';

    /** @var array<string, mixed>|null */
    private ?array $loaded = null;

    /** @return array<string, mixed> flat map of key => value */
    public function all(): array
    {
        if ($this->loaded !== null) {
            return $this->loaded;
        }

        $defaults = config('settings.defaults', []);

        try {
            $stored = Cache::rememberForever(self::CACHE_KEY, fn () => Setting::query()->pluck('value', 'key')->all());
        } catch (Throwable) {
            // Database not reachable yet (before installation).
            return $defaults;
        }

        return $this->loaded = array_merge($defaults, $stored);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        if (array_key_exists($key, $all)) {
            $value = $all[$key];

            if ($value !== null && $this->isSecret($key)) {
                try {
                    return Crypt::decryptString((string) $value);
                } catch (Throwable) {
                    return $default;
                }
            }

            return $value ?? $default;
        }

        // A prefix returns the whole group as a nested array.
        $group = $this->group($key);

        return $group === [] ? $default : $group;
    }

    public function translated(string $key, ?string $default = null): ?string
    {
        return tr($this->get($key)) ?? $default;
    }

    /** @return array<string, mixed> */
    public function group(string $prefix): array
    {
        $prefix = rtrim($prefix, '.').'.';
        $group = [];

        foreach ($this->all() as $key => $value) {
            if (str_starts_with($key, $prefix) && ! $this->isSecret($key)) {
                Arr::set($group, substr($key, strlen($prefix)), $value);
            }
        }

        return $group;
    }

    /** @return array<string, mixed> nested, safe for the browser */
    public function public(): array
    {
        $public = [];

        foreach ($this->all() as $key => $value) {
            foreach (config('settings.public', []) as $prefix) {
                if (str_starts_with($key, $prefix)) {
                    Arr::set($public, $key, $value);
                }
            }
        }

        return $public;
    }

    /** @param array<string, mixed> $values flat map of key => value */
    public function set(array $values): void
    {
        $known = config('settings.defaults', []);

        foreach ($values as $key => $value) {
            if (! array_key_exists($key, $known)) {
                continue;
            }

            if ($this->isSecret($key)) {
                // An empty secret field means "keep the current value".
                if ($value === null || $value === '') {
                    continue;
                }

                $value = Crypt::encryptString((string) $value);
            }

            Setting::query()->updateOrCreate(['key' => $key], [
                'value' => $value,
                'is_public' => $this->isPublic($key),
            ]);
        }

        $this->flush();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->loaded = null;
    }

    public function isSecret(string $key): bool
    {
        return in_array($key, config('settings.secret', []), true);
    }

    public function isPublic(string $key): bool
    {
        foreach (config('settings.public', []) as $prefix) {
            if (str_starts_with($key, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
