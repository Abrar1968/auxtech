<?php

use App\Http\Middleware\CaptureAttribution;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureInstalled;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HandleRedirects;
use App\Http\Middleware\RequireTwoFactor;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function (): void {
            Route::middleware([])->group(base_path('routes/install.php'));

            Route::middleware(['web', 'auth', 'admin', 'two-factor'])->prefix('admin')->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Runs before sessions and cookies, which need an application key.
        $middleware->prepend(EnsureInstalled::class);
        $middleware->append(SecurityHeaders::class);

        $middleware->web(append: [
            SetLocale::class,
            HandleInertiaRequests::class,
        ]);

        $middleware->group('site', [HandleRedirects::class, CaptureAttribution::class]);
        $middleware->alias(['admin' => EnsureAdmin::class, 'two-factor' => RequireTwoFactor::class]);

        // Click beacons are sent with navigator.sendBeacon, which cannot attach a CSRF header.
        $middleware->validateCsrfTokens(except: ['api/events']);
        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Branded error pages for visitors; admin and JSON requests keep the defaults.
        $exceptions->respond(function (Response $response, Throwable $e, Request $request) {
            $status = $response->getStatusCode();

            if (! in_array($status, [403, 404, 500, 503], true) || $request->expectsJson() && ! $request->header('X-Inertia')
                || $request->is('admin', 'admin/*', 'install', 'api/*') || (config('app.debug') && $status === 500)) {
                return $response;
            }

            try {
                seo()->title((string) $status)->noindex();

                return Inertia::render('Site/Error', ['status' => $status])->toResponse($request)->setStatusCode($status);
            } catch (Throwable) {
                return $response;
            }
        });
    })->create();

/*
 | Settings survive updates. Release zips include a template .env (no app key),
 | so extracting an update over the site replaces the top-level file. The site
 | therefore keeps a backup in storage/.env (never in a zip):
 |  - a top-level .env with a real APP_KEY is always used, and the backup is
 |    refreshed from it whenever it changes;
 |  - when the top-level .env is the empty template, the backup is used.
 | The installer writes the settings to storage/.env.
 */
if (getenv('APP_ENV') !== 'testing') {
    $backup = $app->storagePath('.env');
    $root = $app->basePath('.env');
    $rootIsReal = is_file($root) && preg_match('/^APP_KEY=\S+/m', (string) @file_get_contents($root)) === 1;
    if ($rootIsReal) {
        if ((! is_file($backup) || filemtime($root) > filemtime($backup)) && is_dir($app->storagePath())) {
            @copy($root, $backup);
            @chmod($backup, 0600);
        }
    } elseif (is_file($backup)) {
        $app->useEnvironmentPath($app->storagePath());
    }
}

return $app;
