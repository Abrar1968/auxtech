<?php

namespace App\Support;

use App\Services\Settings\SettingsRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;
use Spatie\Permission\PermissionRegistrar;
use Throwable;

/**
 * Everything the browser installer does: environment file, application
 * key, database tables, starter content and the first admin account.
 */
class Installer
{
    public const REQUIRED_EXTENSIONS = ['pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'fileinfo', 'gd', 'curl', 'dom', 'json'];

    /** @return list<array{label: string, ok: bool, hint: string}> */
    public function requirements(): array
    {
        $checks = [[
            'label' => 'PHP 8.2 or newer (found '.PHP_VERSION.')',
            'ok' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'hint' => 'In cPanel open "Select PHP Version" (or MultiPHP Manager) and choose PHP 8.2 or newer for this domain.',
        ]];

        foreach (self::REQUIRED_EXTENSIONS as $extension) {
            $checks[] = [
                'label' => "PHP extension: {$extension}",
                'ok' => extension_loaded($extension),
                'hint' => "Enable \"{$extension}\" under cPanel > Select PHP Version > Extensions.",
            ];
        }

        foreach ($this->writablePaths() as $label => $path) {
            if (! is_dir($path)) {
                @mkdir($path, 0755, true);
            }

            $checks[] = [
                'label' => "Writable folder: {$label}",
                'ok' => is_dir($path) && is_writable($path),
                'hint' => "In File Manager set the permissions of \"{$label}\" to 755 (or 775).",
            ];
        }

        return $checks;
    }

    public function requirementsMet(): bool
    {
        return collect($this->requirements())->every(fn (array $check) => $check['ok']);
    }

    /**
     * Returns null when the database accepts the credentials, or a
     * plain-language reason when it does not.
     *
     * @param  array{host: string, port: int|string, database: string, username: string, password: ?string}  $db
     */
    public function databaseError(array $db): ?string
    {
        try {
            new PDO(
                "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']};charset=utf8mb4",
                $db['username'], (string) $db['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 8],
            );

            return null;
        } catch (Throwable $e) {
            return match (true) {
                str_contains($e->getMessage(), '1045') => 'The database user name or password is wrong.',
                str_contains($e->getMessage(), '1049') => 'That database does not exist. Create it in cPanel > MySQL Databases first.',
                str_contains($e->getMessage(), '1044') => 'The user is not added to this database. In cPanel > MySQL Databases add the user to the database with all privileges.',
                str_contains($e->getMessage(), '2002') => 'The database server could not be reached. The host is usually "localhost".',
                default => 'Could not connect to the database: '.$e->getMessage(),
            };
        }
    }

    /**
     * @param  array<string, mixed>  $connection  a Laravel database connection config
     * @param  array{name: string, email: string, password: string}  $admin
     */
    public function run(array $connection, array $admin, string $url, ?string $mailPassword = null): void
    {
        $key = 'base64:'.base64_encode(random_bytes(32));
        $driver = (string) $connection['driver'];

        $this->writeEnvironment([
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'APP_URL' => rtrim($url, '/'),
            'APP_KEY' => $key,
            'DB_CONNECTION' => $driver,
            'DB_HOST' => (string) ($connection['host'] ?? ''),
            'DB_PORT' => (string) ($connection['port'] ?? ''),
            'DB_DATABASE' => (string) $connection['database'],
            'DB_USERNAME' => (string) ($connection['username'] ?? ''),
            'DB_PASSWORD' => (string) ($connection['password'] ?? ''),
        ]);

        // Use the new settings for the rest of this request.
        config([
            'app.key' => $key,
            'app.url' => rtrim($url, '/'),
            'database.default' => $driver,
            "database.connections.{$driver}" => array_merge(config("database.connections.{$driver}", []), $connection),
            'auxtech.install_admin' => $admin,
            // Anything cached so far points at the pre-install database; use memory for this request.
            'cache.default' => 'array',
        ]);
        app()->forgetInstance('encrypter');
        app('cache')->forgetDriver(['database', 'file', 'array']);
        app()->forgetInstance(PermissionRegistrar::class);
        DB::purge($driver);
        DB::reconnect($driver);

        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);

        if (filled($mailPassword)) {
            app(SettingsRepository::class)->set(['mail.password' => $mailPassword]);
        }

        app(SettingsRepository::class)->flush();

        if (@file_put_contents(Installation::lockFile(), now()->toIso8601String()) === false) {
            throw new RuntimeException('Could not write to the storage folder.');
        }
    }

    /** @param array<string, string> $values */
    public function writeEnvironment(array $values): void
    {
        $template = collect(['.env.production', '.env.example'])
            ->map(fn (string $file) => base_path($file))
            ->first(fn (string $path) => is_file($path));

        $content = $template ? (string) file_get_contents($template) : '';

        foreach ($values as $name => $value) {
            $line = $name.'='.$this->quote($value);
            $content = preg_match("/^{$name}=.*$/m", $content) === 1
                ? (string) preg_replace("/^{$name}=.*$/m", addcslashes($line, '\\$'), $content, 1)
                : rtrim($content)."\n".$line."\n";
        }

        // Production is marked installed by the lock file, not by this flag.
        $content = (string) preg_replace('/^APP_INSTALLED=.*\n?/m', '', $content);

        if (@file_put_contents($this->environmentPath(), $content) === false) {
            throw new RuntimeException('Could not write the .env file. Make the site folder writable (755) and try again.');
        }

        @chmod($this->environmentPath(), 0600);
    }

    public function environmentPath(): string
    {
        return app()->environmentFilePath();
    }

    /** @return array<string, string> */
    private function writablePaths(): array
    {
        return [
            'storage' => storage_path(),
            'storage/framework' => storage_path('framework'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
            'public/uploads' => public_path('uploads'),
        ];
    }

    private function quote(string $value): string
    {
        return $value === '' || preg_match('/^[A-Za-z0-9_.:\/@+=-]+$/', $value) === 1
            ? $value
            : '"'.addcslashes($value, '"\\$').'"';
    }
}
