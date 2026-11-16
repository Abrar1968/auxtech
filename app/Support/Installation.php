<?php

namespace App\Support;

/**
 * Whether the browser installer has completed. Development and test
 * environments set APP_INSTALLED=true and never see the installer.
 */
final class Installation
{
    public static function done(): bool
    {
        return (bool) config('auxtech.installed') || is_file(self::lockFile());
    }

    public static function lockFile(): string
    {
        return storage_path('installed');
    }

    public static function version(): string
    {
        $file = base_path('VERSION');

        return is_file($file) ? trim((string) file_get_contents($file)) : 'dev';
    }
}
