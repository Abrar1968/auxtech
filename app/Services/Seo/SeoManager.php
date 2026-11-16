<?php

namespace App\Services\Seo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Collects the SEO data for the current request. Controllers fill it in;
 * the root Blade template prints it (title, meta, hreflang, JSON-LD).
 */
class SeoManager
{
    private ?string $title = null;

    private ?string $description = null;

    private ?string $canonical = null;

    private ?string $robots = null;

    private ?string $image = null;

    private string $type = 'website';

    /** @var list<array{name: string, url: string}> */
    private array $breadcrumbs = [];

    /** @var list<array<string, mixed>> */
    private array $schemas = [];

    public function __construct(private readonly SchemaBuilder $schema) {}

    public function builder(): SchemaBuilder
    {
        return $this->schema;
    }

    /** Title and description of a listing page, as edited in Admin > Settings. */
    public function page(string $key): static
    {
        return $this->title(setting_t("pages.{$key}.title"))->description(setting_t("pages.{$key}.description"));
    }

    public function title(?string $title): static
    {
        $this->title = $title ? trim($title) : null;

        return $this;
    }

    public function description(?string $description): static
    {
        $text = trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $description))));
        $this->description = $text === '' ? null : Str::limit($text, 157, '...');

        return $this;
    }

    public function image(?string $url): static
    {
        $this->image = $url;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function canonical(?string $url): static
    {
        $this->canonical = $url;

        return $this;
    }

    public function noindex(): static
    {
        $this->robots = 'noindex, nofollow';

        return $this;
    }

    public function crumb(string $name, string $url): static
    {
        $this->breadcrumbs[] = ['name' => $name, 'url' => $url];

        return $this;
    }

    /** @param array<string, mixed>|null $node */
    public function schema(?array $node): static
    {
        if ($node) {
            $this->schemas[] = $node;
        }

        return $this;
    }

    /**
     * Apply a model's defaults, then any overrides saved in its SEO panel.
     */
    public function for(Model $model, ?string $title, ?string $description, ?string $image = null): static
    {
        $this->title($title)->description($description)->image($image);

        $meta = method_exists($model, 'seo') ? $model->seo : null;

        if ($meta) {
            if ($custom = tr($meta->getTranslations('title'))) {
                $this->title = $custom;
            }
            if ($custom = tr($meta->getTranslations('description'))) {
                $this->description($custom);
            }
            $this->canonical = $meta->canonical ?: $this->canonical;
            $this->robots = $meta->robots ?: $this->robots;
        }

        return $this;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $siteName = (string) setting('company.name', 'Auxtech');
        $suffix = (string) setting('seo.title_suffix', $siteName);

        $title = $this->title
            ? (Str::contains($this->title, $suffix) ? $this->title : $this->title.' | '.$suffix)
            : (setting_t('seo.default_title') ?? $siteName);

        $description = $this->description ?? setting_t('seo.default_description');
        $canonical = $this->canonical ?? url()->current();
        $image = $this->image ?? url('/icon-512.png');

        $graph = [$this->schema->organization(), $this->schema->website()];

        if (count($this->breadcrumbs) > 1) {
            $graph[] = $this->schema->breadcrumbs($this->breadcrumbs);
        }

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $this->robots ?? 'index, follow, max-image-preview:large',
            'image' => $image,
            'type' => $this->type,
            'siteName' => $siteName,
            'locale' => app()->getLocale(),
            'alternates' => $this->alternates(),
            'breadcrumbs' => $this->breadcrumbs,
            'jsonLd' => ['@context' => 'https://schema.org', '@graph' => array_merge($graph, $this->schemas)],
        ];
    }

    /** @return array<string, string> locale => absolute URL of this page in that locale */
    public function alternates(): array
    {
        $route = Route::current();
        $name = $route?->getName();

        if (! $route || ! $name) {
            return [];
        }

        $default = config('auxtech.locales.default');
        $base = Str::startsWith($name, 'bn.') ? Str::after($name, 'bn.') : $name;

        if (! Route::has('bn.'.$base)) {
            return [];
        }

        $parameters = $route->parameters();
        $alternates = [];

        foreach (config('auxtech.locales.supported') as $locale) {
            $alternates[$locale] = route($locale === $default ? $base : $locale.'.'.$base, $parameters);
        }

        return $alternates;
    }
}
