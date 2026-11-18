<?php

use App\Models\Faq;
use App\Models\Redirect;
use Database\Seeders\CatalogueSeeder;

beforeEach(function () {
    $this->seed(CatalogueSeeder::class);
});

function headOf(string $html): string
{
    return substr($html, 0, (int) strpos($html, '</head>'));
}

it('prints exactly one title, description and canonical per page', function (string $path) {
    project(['slug' => 'gearzen']);
    $head = headOf($this->get($path)->getContent());

    expect(substr_count($head, '<title>'))->toBe(1)
        ->and(substr_count($head, '<meta name="description"'))->toBe(1)
        ->and(substr_count($head, 'rel="canonical"'))->toBe(1)
        ->and($head)->toContain('rel="canonical" href="'.url($path === '/' ? '' : $path).'"');

    preg_match('/<title>(.*?)<\/title>/', $head, $title);
    preg_match('/<meta name="description" content="(.*?)">/', $head, $description);

    expect(mb_strlen(html_entity_decode($title[1])))->toBeLessThanOrEqual(70)
        ->and(mb_strlen(html_entity_decode($description[1])))->toBeLessThanOrEqual(160);
})->with(['/', '/work', '/work/gearzen', '/services/website-development', '/solutions/ecommerce', '/contact']);

it('links both languages with reciprocal hreflang tags', function () {
    project(['slug' => 'gearzen']);

    foreach (['/work/gearzen', '/bn/work/gearzen'] as $path) {
        $head = headOf($this->get($path)->getContent());

        expect($head)
            ->toContain('hreflang="en" href="'.url('/work/gearzen').'"')
            ->toContain('hreflang="bn" href="'.url('/bn/work/gearzen').'"')
            ->toContain('hreflang="x-default" href="'.url('/work/gearzen').'"');
    }
});

it('outputs valid JSON-LD with the organisation facts on every page', function () {
    $html = $this->get('/')->getContent();
    preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $match);
    $graph = collect(json_decode($match[1], true, flags: JSON_THROW_ON_ERROR)['@graph']);

    $organisation = $graph->first(fn ($node) => in_array('Organization', (array) $node['@type'], true));

    expect($organisation['name'])->toBe('Auxtech')
        ->and($organisation['telephone'])->toBe('+8801805019415')
        ->and($organisation['email'])->toBe('auxtechbd@gmail.com')
        ->and($organisation['address']['postalCode'])->toBe('1216')
        ->and($organisation['sameAs'])->toContain('https://www.facebook.com/auxtechs/')
        ->and($organisation['openingHoursSpecification'][0]['dayOfWeek'])->not->toContain('Friday')
        ->and($graph->pluck('@type')->flatten())->toContain('WebSite', 'SoftwareApplication');
});

it('adds the page-specific schema type', function (string $path, string $type) {
    project(['slug' => 'gearzen']);
    post(['slug' => 'cost-guide']);
    Faq::query()->create(['question' => ['en' => 'Q?'], 'answer' => ['en' => '<p>A.</p>']]);

    preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $this->get($path)->getContent(), $match);

    expect(collect(json_decode($match[1], true)['@graph'])->pluck('@type')->flatten())->toContain($type);
})->with([
    ['/work/gearzen', 'CreativeWork'], ['/work/gearzen', 'BreadcrumbList'], ['/services/website-development', 'Service'],
    ['/solutions/ecommerce', 'SoftwareApplication'], ['/blog/cost-guide', 'Article'], ['/faq', 'FAQPage'], ['/work', 'ItemList'],
]);

it('lets the SEO panel override the generated title and description', function () {
    $project = project(['slug' => 'gearzen']);
    $project->seo()->create(['title' => ['en' => 'Custom Gadget Store Title'], 'description' => ['en' => 'Custom description.'], 'robots' => 'noindex, follow']);

    $this->get('/work/gearzen')
        ->assertSee('<title>Custom Gadget Store Title | Auxtech</title>', false)
        ->assertSee('content="Custom description."', false)
        ->assertSee('<meta name="robots" content="noindex, follow">', false);
});

it('serves a sitemap with every published URL in both languages', function () {
    project(['slug' => 'gearzen']);
    $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    $xml = simplexml_load_string($response->getContent());

    expect($xml)->not->toBeFalse()
        ->and($response->getContent())
        ->toContain('<loc>'.url('/work/gearzen').'</loc>')
        ->toContain('<loc>'.url('/bn/work/gearzen').'</loc>')
        ->toContain('<loc>'.url('/solutions/ecommerce').'</loc>')
        ->not->toContain('/admin');
});

it('rebuilds cached feeds when content changes', function () {
    $this->get('/sitemap.xml')->assertDontSee('new-store');

    $this->actingAs(admin())->post('/admin/projects', [
        'title' => ['en' => 'New Store'], 'slug' => 'new-store', 'type' => 'live', 'status' => 'published',
    ])->assertSessionHasNoErrors();

    $this->get('/sitemap.xml')->assertSee('new-store');
});

it('serves robots.txt that blocks the admin, welcomes AI crawlers and links the sitemap', function () {
    $this->get('/robots.txt')->assertOk()
        ->assertSee('Disallow: /admin')
        ->assertSee('User-agent: GPTBot')
        ->assertSee('User-agent: ClaudeBot')
        ->assertSee('User-agent: PerplexityBot')
        ->assertSee('Sitemap: '.url('/sitemap.xml'));
});

it('serves llms.txt and llms-full.txt with the company facts', function () {
    project(['slug' => 'gearzen']);

    $this->get('/llms.txt')->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('# Auxtech')->assertSee('+8801805019415')->assertSee('auxtechbd@gmail.com')->assertSee('Web Development');

    $this->get('/llms-full.txt')->assertOk()->assertSee('Order Management')->assertSee('Gearzen');
});

it('serves the blog feed', function () {
    post(['slug' => 'cost-guide']);

    $this->get('/feed.xml')->assertOk()->assertSee('E-commerce website cost in Bangladesh')->assertSee(url('/blog/cost-guide'));
});

it('applies redirects in both languages and counts hits', function () {
    Redirect::query()->create(['from_path' => '/old-page', 'to_url' => '/about', 'status_code' => 301]);

    $this->get('/old-page')->assertRedirect('/about')->assertStatus(301);
    $this->get('/bn/old-page')->assertRedirect('/bn/about');

    expect(Redirect::query()->first()->hits)->toBe(2);
});

it('leaves a 301 behind when a slug changes and collapses chains', function () {
    $project = project(['slug' => 'first-name']);

    $project->update(['slug' => 'second-name']);
    $project->update(['slug' => 'third-name']);

    $this->get('/work/first-name')->assertRedirect('/work/third-name')->assertStatus(301);
    $this->get('/work/second-name')->assertRedirect('/work/third-name');
    $this->get('/work/third-name')->assertOk();
});

it('keeps admin pages out of search engines', function () {
    $this->actingAs(admin())->get('/admin')->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
});

it('gives every industry page its own description when none is written', function () {
    industry();
    DB::table('industries')->where('slug', 'fashion')->update(['description' => null]);

    $this->get('/industries/fashion')->assertSee('<meta name="description" content="Auxtech builds software, websites and online stores for Fashion &amp; Apparel businesses in Bangladesh.', false);
    $this->get('/bn/industries/fashion')->assertSee('<meta name="description" content="Auxtech বাংলাদেশের ', false)->assertSee('ব্যবসার জন্য সফটওয়্যার, ওয়েবসাইট ও অনলাইন স্টোর তৈরি করে', false);
});
