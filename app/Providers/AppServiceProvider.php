<?php

namespace App\Providers;

use App\Admin\ModuleRegistry;
use App\Models\LandingPage;
use App\Models\Service;
use App\Models\Solution;
use App\Services\Seo\SeoManager;
use App\Services\Settings\SettingsRepository;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsRepository::class);
        $this->app->scoped(SeoManager::class);
        $this->app->singleton(ModuleRegistry::class, fn () => new ModuleRegistry(config('admin.modules', [])));
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
        Date::use(CarbonImmutable::class);
        JsonResource::withoutWrapping();

        // Follow the address the site was installed on; force HTTPS once it uses it.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Missing Bangla text falls back to English everywhere.
        Translatable::fallback(fallbackLocale: 'en');

        // Short, stable names for the "attach to" pickers in the admin panel.
        Relation::morphMap([
            'service' => Service::class,
            'solution' => Solution::class,
            'landing_page' => LandingPage::class,
        ]);

        Gate::before(fn ($user) => $user->hasRole('Super Admin') ? true : null);

        RateLimiter::for('leads', fn (Request $request) => [
            Limit::perMinute(5)->by('lead-m:'.$request->ip()),
            Limit::perDay(30)->by('lead-d:'.$request->ip()),
        ]);
        RateLimiter::for('events', fn (Request $request) => Limit::perMinute(60)->by('event:'.$request->ip()));
        RateLimiter::for('search', fn (Request $request) => Limit::perMinute(30)->by('search:'.$request->ip()));
    }
}
