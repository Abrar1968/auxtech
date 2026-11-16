<?php

use App\Http\Middleware\CaptureAttribution;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureInstalled;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HandleRedirects;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function (): void {
            Route::middleware([])->group(base_path('routes/install.php'));

            Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')
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
        $middleware->alias(['admin' => EnsureAdmin::class]);

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
