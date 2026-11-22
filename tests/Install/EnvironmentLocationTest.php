<?php

use App\Support\Installer;

/*
 | The installer writes the site's settings to storage/.env, which update zips
 | never contain, so extracting a new release cannot wipe them.
 */
it('writes the installed settings to storage, away from the template .env in release zips', function () {
    $previous = app()->environmentPath();
    app()->useEnvironmentPath(base_path());

    try {
        expect(app(Installer::class)->environmentPath())->toBe(storage_path('.env'));
    } finally {
        app()->useEnvironmentPath($previous);
    }
});

it('loads storage/.env when it exists', function () {
    $bootstrap = (string) file_get_contents(base_path('bootstrap/app.php'));

    expect($bootstrap)->toContain("\$app->useEnvironmentPath(\$app->storagePath())")
        ->and((string) file_get_contents(base_path('scripts/build-release.sh')))->toContain('--exclude=./storage/.env');
});
