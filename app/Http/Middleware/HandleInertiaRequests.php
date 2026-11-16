<?php

namespace App\Http\Middleware;

use App\Models\Service;
use App\Models\Solution;
use App\Services\Settings\SettingsRepository;
use App\Services\WhatsApp\LinkBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    public const NAV_CACHE_KEY = 'site.navigation';

    protected $rootView = 'app';

    /** @return array<string, mixed> */
    public function share(Request $request): array
    {
        $admin = $request->is('admin', 'admin/*');

        return [
            ...parent::share($request),
            'ziggy' => fn (): array => [...(new Ziggy)->toArray(), 'location' => $request->url()],
            'locale' => fn (): string => app()->getLocale(),
            'locales' => config('auxtech.locales.supported'),
            'flash' => fn (): array => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'seo' => fn (): array => seo()->toArray(),
            'site' => fn (): array => $this->site(),
            'navigation' => fn (): array => $admin ? [] : $this->navigation(),
            'auth' => fn (): ?array => $admin && $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'roles' => $request->user()->getRoleNames()->all(),
                'permissions' => $request->user()->getAllPermissions()->pluck('name')->all(),
            ] : null,
        ];
    }

    /** @return array<string, mixed> public settings resolved for the current locale */
    private function site(): array
    {
        $settings = app(SettingsRepository::class);
        $whatsapp = app(LinkBuilder::class);

        return [
            'name' => $settings->get('company.name'),
            'tagline' => $settings->translated('company.tagline'),
            'slogan' => $settings->translated('company.slogan'),
            'description' => $settings->translated('company.description'),
            'email' => $settings->get('company.email'),
            'phone' => $settings->get('company.phone'),
            'phoneDisplay' => $settings->get('company.phone_display'),
            'address' => $settings->translated('company.address'),
            'hours' => $settings->translated('hours.text'),
            'social' => array_filter($settings->group('social')),
            'logo' => config('auxtech.brand'),
            'whatsapp' => [
                'number' => $whatsapp->number(),
                'url' => $whatsapp->url(),
                'templates' => $whatsapp->templates(),
            ],
        ];
    }

    /** @return array<string, list<array{title: ?string, url: string}>> */
    private function navigation(): array
    {
        $locale = app()->getLocale();

        return Cache::rememberForever(self::NAV_CACHE_KEY.'.'.$locale, fn (): array => [
            'services' => Service::query()->published()->whereNull('parent_id')->ordered()->get()
                ->map(fn (Service $s) => ['title' => $s->title, 'url' => lroute('services.show', $s->slug)])->all(),
            'solutions' => Solution::query()->published()->ordered()->get()
                ->map(fn (Solution $s) => ['title' => $s->name, 'url' => lroute('solutions.show', $s->slug)])->all(),
        ]);
    }

    public static function forgetNavigation(): void
    {
        foreach (config('auxtech.locales.supported') as $locale) {
            Cache::forget(self::NAV_CACHE_KEY.'.'.$locale);
        }
    }
}
