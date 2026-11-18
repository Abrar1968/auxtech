<?php

use App\Models\Project;
use App\Models\User;
use App\Support\Installation;
use App\Support\Installer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/*
| Runs the real installer end to end against its own database file.
| Kept apart from the Feature suite because it does not use the shared,
| transaction-wrapped test database.
*/

beforeEach(function () {
    $this->envFile = storage_path('framework/testing-install.env');
    $this->database = storage_path('framework/testing-install.sqlite');
    array_map(fn (string $file) => @unlink($file), [$this->envFile, $this->database, Installation::lockFile()]);
    touch($this->database);

    app()->useEnvironmentPath(dirname($this->envFile));
    app()->loadEnvironmentFrom(basename($this->envFile));
    config(['auxtech.installed' => false, 'filesystems.disks.public.root' => storage_path('framework/testing-install-uploads')]);
});

afterEach(function () {
    DB::purge();
    array_map(fn (string $file) => @unlink($file), [$this->envFile, $this->database, Installation::lockFile()]);
    File::deleteDirectory(storage_path('framework/testing-install-uploads'));
});

it('installs: tables, starter content, admin account, mail password and lock file', function () {
    app(Installer::class)->run(
        ['driver' => 'sqlite', 'database' => $this->database, 'prefix' => '', 'foreign_key_constraints' => true],
        ['name' => 'Owner', 'email' => 'owner@auxtechbd.com', 'password' => 'a-long-password'],
        'https://auxtechbd.com',
        'abcdefghijklmnop',
    );

    $admin = User::query()->where('email', 'owner@auxtechbd.com')->sole();
    $env = file_get_contents($this->envFile);

    expect(Installation::done())->toBeTrue()
        ->and($admin->hasRole('Super Admin'))->toBeTrue()
        ->and(Hash::check('a-long-password', $admin->password))->toBeTrue()
        ->and(Project::query()->count())->toBe(33)
        ->and(Project::query()->first()->getFirstMedia('cover'))->toBeNull() // images are uploaded from the image pack, not seeded
        ->and(Project::query()->first()->getTranslation('summary', 'en'))->not->toBeEmpty()
        ->and(DB::table('solution_features')->count())->toBe(23)
        ->and(DB::table('stats')->count())->toBe(0)
        ->and(DB::table('testimonials')->count())->toBe(0)
        ->and(DB::table('pricing_plans')->count())->toBe(0)
        ->and(setting('mail.password'))->toBe('abcdefghijklmnop')
        ->and($env)->toContain('APP_ENV=production')->toContain('APP_DEBUG=false')->toContain('APP_KEY=base64:')
        ->toContain('APP_URL=https://auxtechbd.com')->not->toContain('APP_INSTALLED');

    // Once installed, the site is served and the installer is gone.
    $this->get('/install')->assertNotFound();
    $this->get('/work/gearzen')->assertOk()->assertSee('Gearzen');
});
