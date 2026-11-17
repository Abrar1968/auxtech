<?php

use App\Support\Installation;
use App\Support\Installer;

beforeEach(function () {
    $this->envFile = storage_path('framework/testing-install.env');
    @unlink($this->envFile);
    @unlink(Installation::lockFile());
});

afterEach(function () {
    @unlink($this->envFile);
    @unlink(Installation::lockFile());
});

it('sends every visitor to the installer until the site is installed', function () {
    config(['auxtech.installed' => false]);

    $this->get('/')->assertRedirect('/install');
    $this->get('/admin')->assertRedirect('/install');
    $this->get('/install')->assertOk()->assertSee('Install your website')->assertSee('PHP 8.2 or newer')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

it('hides the installer once the site is installed', function () {
    $this->get('/install')->assertNotFound();
    $this->post('/install', [])->assertNotFound();
});

it('explains what is wrong instead of half-installing', function () {
    config(['auxtech.installed' => false]);

    $this->post('/install', ['app_url' => 'not-a-url', 'db_host' => 'localhost', 'db_port' => 3306, 'db_name' => 'x', 'db_user' => 'y',
        'admin_name' => 'A', 'admin_email' => 'bad', 'admin_password' => 'short', 'admin_password_confirmation' => 'different'])
        ->assertStatus(422)
        ->assertSee('app url')->assertSee('admin email')->assertSee('admin password');

    // Valid form, unreachable database.
    $this->post('/install', ['app_url' => 'https://auxtechbd.com', 'db_host' => '127.0.0.1', 'db_port' => 1, 'db_name' => 'nope', 'db_user' => 'nobody', 'db_password' => 'wrong',
        'admin_name' => 'Owner', 'admin_email' => 'owner@example.com', 'admin_password' => 'a-long-password', 'admin_password_confirmation' => 'a-long-password'])
        ->assertStatus(422)->assertSee('database');

    expect(Installation::done())->toBeFalse();
});

it('reports missing server requirements in plain language', function () {
    $checks = collect(app(Installer::class)->requirements());

    expect($checks->pluck('label')->implode(' '))->toContain('PHP 8.2', 'pdo_mysql', 'gd', 'storage', 'public/uploads')
        ->and($checks->every(fn ($check) => $check['hint'] !== ''))->toBeTrue();
});

it('writes the environment file with a fresh key and without the development flag', function () {
    app()->useEnvironmentPath(dirname($this->envFile));
    app()->loadEnvironmentFrom(basename($this->envFile));

    app(Installer::class)->writeEnvironment(['APP_URL' => 'https://auxtechbd.com', 'DB_PASSWORD' => 'p@ss "word" $1', 'APP_KEY' => 'base64:abc=']);

    $env = file_get_contents($this->envFile);

    expect($env)->toContain('APP_URL=https://auxtechbd.com')
        ->toContain('APP_KEY=base64:abc=')
        ->toContain('DB_PASSWORD="p@ss \\"word\\" \\$1"')
        ->toContain('PUSHER_APP_KEY=')
        ->not->toContain('APP_INSTALLED');
});
