<?php

namespace App\Services\Seo;

use Illuminate\Support\Str;

/**
 * JSON-LD nodes. Each method returns one schema.org node; SeoManager
 * joins them into a single @graph per page.
 */
class SchemaBuilder
{
    public function siteUrl(): string
    {
        return rtrim((string) config('app.url'), '/');
    }

    /** @return array<string, mixed> */
    public function organization(): array
    {
        $url = $this->siteUrl();
        $sameAs = array_values(array_filter((array) setting('social', [])));

        return array_filter([
            '@type' => ['Organization', 'ProfessionalService'],
            '@id' => $url.'/#organization',
            'name' => setting('company.name'),
            'url' => $url.'/',
            'logo' => $url.config('auxtech.brand.logo'),
            'image' => $url.'/icon-512.png',
            'description' => setting_t('company.description'),
            'slogan' => setting_t('company.slogan'),
            'email' => setting('company.email'),
            'telephone' => setting('company.phone'),
            'foundingDate' => setting('company.founded_year') ? (string) setting('company.founded_year') : null,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('company.street'),
                'addressLocality' => setting('company.city'),
                'postalCode' => setting('company.postal_code'),
                'addressCountry' => setting('company.country'),
            ],
            'geo' => setting('company.latitude') && setting('company.longitude') ? [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) setting('company.latitude'),
                'longitude' => (float) setting('company.longitude'),
            ] : null,
            'openingHoursSpecification' => [[
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => setting('hours.open_days'),
                'opens' => setting('hours.opens'),
                'closes' => setting('hours.closes'),
            ]],
            'areaServed' => ['@type' => 'Country', 'name' => 'Bangladesh'],
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'contactType' => 'sales',
                'telephone' => setting('company.phone'),
                'email' => setting('company.email'),
                'availableLanguage' => ['en', 'bn'],
            ]],
            'sameAs' => $sameAs ?: null,
        ]);
    }

    /** @return array<string, mixed> */
    public function website(): array
    {
        $url = $this->siteUrl();

        return [
            '@type' => 'WebSite',
            '@id' => $url.'/#website',
            'url' => $url.'/',
            'name' => setting('company.name'),
            'inLanguage' => ['en', 'bn'],
            'publisher' => ['@id' => $url.'/#organization'],
        ];
    }

    /**
     * @param  list<array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public function breadcrumbs(array $items): array
    {
        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(fn (array $item, int $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ], $items, array_keys($items)),
        ];
    }

    /** @return array<string, mixed> */
    public function service(string $name, ?string $description, string $url): array
    {
        return array_filter([
            '@type' => 'Service',
            'name' => $name,
            'description' => $this->plain($description),
            'url' => $url,
            'provider' => ['@id' => $this->siteUrl().'/#organization'],
            'areaServed' => ['@type' => 'Country', 'name' => 'Bangladesh'],
        ]);
    }

    /**
     * @param  list<string>  $features
     * @return array<string, mixed>
     */
    public function softwareApplication(string $name, ?string $description, string $url, array $features = []): array
    {
        return array_filter([
            '@type' => 'SoftwareApplication',
            'name' => $name,
            'description' => $this->plain($description),
            'url' => $url,
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'featureList' => $features ?: null,
            'publisher' => ['@id' => $this->siteUrl().'/#organization'],
        ]);
    }

    /** @return array<string, mixed> */
    public function creativeWork(string $name, ?string $description, string $url, ?string $image = null, ?string $about = null, ?string $date = null): array
    {
        return array_filter([
            '@type' => 'CreativeWork',
            'name' => $name,
            'description' => $this->plain($description),
            'url' => $url,
            'image' => $image,
            'about' => $about,
            'dateCreated' => $date,
            'creator' => ['@id' => $this->siteUrl().'/#organization'],
        ]);
    }

    /** @return array<string, mixed> */
    public function article(string $title, ?string $description, string $url, ?string $image, ?string $author, ?string $published, ?string $modified): array
    {
        return array_filter([
            '@type' => 'Article',
            'headline' => Str::limit($title, 108, ''),
            'description' => $this->plain($description),
            'url' => $url,
            'mainEntityOfPage' => $url,
            'image' => $image,
            'datePublished' => $published,
            'dateModified' => $modified ?? $published,
            'author' => $author ? ['@type' => 'Person', 'name' => $author] : ['@id' => $this->siteUrl().'/#organization'],
            'publisher' => ['@id' => $this->siteUrl().'/#organization'],
        ]);
    }

    /**
     * @param  iterable<array{question: ?string, answer: ?string}>  $faqs
     * @return array<string, mixed>|null
     */
    public function faqPage(iterable $faqs): ?array
    {
        $entities = [];

        foreach ($faqs as $faq) {
            if (! empty($faq['question']) && ! empty($faq['answer'])) {
                $entities[] = [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $this->plain($faq['answer'])],
                ];
            }
        }

        return $entities === [] ? null : ['@type' => 'FAQPage', 'mainEntity' => $entities];
    }

    /**
     * @param  list<array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public function itemList(string $name, array $items): array
    {
        return [
            '@type' => 'ItemList',
            'name' => $name,
            'itemListElement' => array_map(fn (array $item, int $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'url' => $item['url'],
            ], $items, array_keys($items)),
        ];
    }

    private function plain(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $text = trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html))));

        return $text === '' ? null : $text;
    }
}
