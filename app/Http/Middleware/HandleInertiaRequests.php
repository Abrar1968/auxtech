<?php

namespace App\Http\Middleware;

use App\Admin\ModuleRegistry;
use App\Models\Service;
use App\Models\Solution;
use App\Services\Settings\SettingsRepository;
use App\Services\WhatsApp\LinkBuilder;
use App\Support\Realtime;
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
            'adminMenu' => fn (): array => $admin && $request->user() ? $this->adminMenu($request) : [],
            'notifications' => fn (): ?array => $admin && $request->user() ? [
                'unread' => $request->user()->unreadNotifications()->count(),
                'items' => $request->user()->notifications()->latest()->limit(8)->get()->map(fn ($n) => [
                    'id' => $n->id, 'data' => $n->data, 'read' => $n->read_at !== null, 'created_at' => $n->created_at?->toIso8601String(),
                ]),
            ] : null,
            'realtime' => fn (): array => $admin ? ['enabled' => Realtime::enabled()] : [],
            'auth' => fn (): ?array => $admin && $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'roles' => $request->user()->getRoleNames()->all(),
                'permissions' => $request->user()->getAllPermissions()->pluck('name')->all(),
                'twoFactor' => $request->user()->hasTwoFactor(),
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
            'tracking' => [
                'gtm' => $settings->get('tracking.gtm_id'),
                'pixel' => $settings->get('tracking.meta_pixel_id'),
            ],
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

    /** @return list<array{label: string, items: list<array{label: string, href: string, icon: string}>}> */
    private function adminMenu(Request $request): array
    {
        $user = $request->user();
        $can = fn (string $ability): bool => $user->can($ability);
        $groups = [];

        $add = function (string $group, string $label, string $href, string $icon) use (&$groups): void {
            $groups[$group][] = compact('label', 'href', 'icon');
        };

        $add('Overview', 'Dashboard', '/admin', 'layout-dashboard');

        if ($can('leads.view')) {
            $add('Overview', 'Leads', '/admin/leads', 'message-circle');
        }

        $icons = ['projects' => 'briefcase', 'clients' => 'users', 'testimonials' => 'star', 'services' => 'layers',
            'solutions' => 'package', 'solution-feature-groups' => 'boxes', 'solution-features' => 'circle-check',
            'pricing-plans' => 'wallet', 'stats' => 'chart-column', 'faqs' => 'message-square-plus', 'team' => 'id-card',
            'pages' => 'file-text', 'posts' => 'pen-tool', 'post-categories' => 'layout-template', 'tags' => 'ticket-percent',
            'industries' => 'store', 'technologies' => 'cpu', 'landing-pages' => 'target', 'redirects' => 'network', 'users' => 'shield-check'];

        foreach (app(ModuleRegistry::class)->all() as $module) {
            if ($can($module->permission().'.view')) {
                $add($module->group(), $module->label(), '/admin/'.$module->key(), $icons[$module->key()] ?? 'file-text');
            }
        }

        if ($can('landing-pages.view')) {
            $add('SEO', 'SEO report', '/admin/seo-report', 'search');
        }
        if ($can('settings.view')) {
            $add('System', 'Settings', '/admin/settings', 'settings');
        }
        if ($can('activity.view')) {
            $add('System', 'Activity log', '/admin/activity', 'activity');
        }
        if ($can('system.manage')) {
            $add('System', 'System', '/admin/system', 'server');
        }

        $order = ['Overview', 'Content', 'Catalogue', 'Blog', 'SEO', 'System'];
        uksort($groups, fn ($a, $b) => array_search($a, $order, true) <=> array_search($b, $order, true));

        return collect($groups)->map(fn (array $items, string $label) => compact('label', 'items'))->values()->all();
    }

    public static function forgetNavigation(): void
    {
        foreach (config('auxtech.locales.supported') as $locale) {
            Cache::forget(self::NAV_CACHE_KEY.'.'.$locale);
        }
    }
}
