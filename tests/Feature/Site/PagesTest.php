<?php

use App\Enums\ProjectType;
use App\Enums\PublishStatus;
use App\Models\Faq;
use App\Models\LandingPage;
use App\Models\Page;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\Stat;
use Database\Seeders\CatalogueSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(CatalogueSeeder::class);
});

it('serves every public page in English and Bangla', function (string $path) {
    $category = PostCategory::query()->create(['name' => ['en' => 'Guides']]);
    post(['post_category_id' => $category->id, 'slug' => 'cost-guide']);
    project(['slug' => 'gearzen']);
    Page::query()->create(['slug' => 'privacy-policy', 'title' => ['en' => 'Privacy Policy'], 'body' => ['en' => '<p>Text</p>'], 'status' => PublishStatus::Published]);

    $this->get($path)->assertOk();
    $this->get('/bn'.rtrim($path, '/'))->assertOk()->assertSee('<html lang="bn">', false);
})->with([
    '/', '/work', '/work/gearzen', '/services', '/services/website-development', '/solutions', '/solutions/ecommerce',
    '/industries/fashion', '/pricing', '/about', '/faq', '/contact', '/estimate', '/blog', '/blog/cost-guide',
    '/blog/category/guides', '/privacy-policy',
]);

it('builds the home page from admin-managed content', function () {
    project(['is_featured' => true]);
    Stat::query()->create(['value' => '30', 'suffix' => '+', 'label' => ['en' => 'Stores launched']]);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->component('Site/Home')
        ->where('hero.title', 'We engineer software that runs real businesses.')
        ->has('services', 6)
        ->has('projects', 1)
        ->where('stats.0.value', '30')
        ->where('flagship.slug', 'ecommerce')
        ->has('flagship.feature_groups', 4)
        ->has('testimonials', 0)
        ->where('site.whatsapp.number', '8801805019415')
        ->where('site.email', 'auxtechbd@gmail.com'));
});

it('lists all 23 Auxtech Ecommerce modules in four groups', function () {
    $this->get('/solutions/ecommerce')->assertInertia(fn (Assert $page) => $page
        ->has('solution.feature_groups', 4)
        ->where('solution.feature_groups', fn ($groups) => collect($groups)->sum(fn ($g) => count($g['features'])) === 23));
});

it('filters work by industry, solution and service', function () {
    $fashion = project(['name' => 'Fablix']);
    $gadget = project(['name' => 'Gearzen', 'industry_id' => industry('gadgets', 'Gadgets')->id]);
    $gadget->services()->attach(Service::query()->where('slug', 'ecommerce-development')->value('id'));

    $this->get('/work')->assertInertia(fn (Assert $page) => $page->has('projects', 2)->has('industries', 2));
    $this->get('/work?industry=gadgets')->assertInertia(fn (Assert $page) => $page->has('projects', 1)->where('projects.0.title', 'Gearzen'));
    $this->get('/work?service=ecommerce-development')->assertInertia(fn (Assert $page) => $page->has('projects', 1));
    $this->get('/work?industry=nothing')->assertInertia(fn (Assert $page) => $page->has('projects', 0));
});

it('never exposes drafts or scheduled content', function () {
    project(['name' => 'Draft Co', 'slug' => 'draft-co', 'status' => PublishStatus::Draft]);
    project(['name' => 'Later Co', 'slug' => 'later-co', 'published_at' => now()->addDay()]);
    project(['name' => 'Live Co', 'slug' => 'live-co', 'published_at' => now()->subMinute()]);

    $this->get('/work/draft-co')->assertNotFound();
    $this->get('/work/later-co')->assertNotFound();
    $this->get('/work/live-co')->assertOk();
    $this->get('/work')->assertInertia(fn (Assert $page) => $page->has('projects', 1));
    $this->get('/sitemap.xml')->assertDontSee('draft-co')->assertDontSee('later-co')->assertSee('live-co');
});

it('publishes scheduled content by itself once its time has passed', function () {
    project(['name' => 'Later Co', 'slug' => 'later-co', 'published_at' => now()->addHour()]);

    $this->get('/work/later-co')->assertNotFound();
    $this->travel(2)->hours();
    $this->get('/work/later-co')->assertOk();
});

it('labels demo projects', function () {
    project(['name' => 'Kalki', 'slug' => 'kalki', 'type' => ProjectType::Demo]);

    $this->get('/work/kalki')
        ->assertInertia(fn (Assert $page) => $page->where('project.is_demo', true)->where('project.type', 'demo'))
        ->assertSee('Demo project');
});

it('falls back to English when a Bangla translation is missing and uses Bangla when present', function () {
    project(['name' => 'Gearzen', 'slug' => 'gearzen']);

    $this->get('/bn/work/gearzen')->assertInertia(fn (Assert $page) => $page
        ->where('project.title', 'Gearzen')
        ->where('project.industry.name', 'ফ্যাশন ও পোশাক')
        ->where('locale', 'bn'));
});

it('prints the page content as plain HTML for crawlers without JavaScript', function () {
    project(['name' => 'Gearzen', 'slug' => 'gearzen']);
    Faq::query()->create(['question' => ['en' => 'How do I contact Auxtech?'], 'answer' => ['en' => '<p>On WhatsApp.</p>']]);

    $this->get('/')->assertSee('data-fallback', false)
        ->assertSee('<h1>We engineer software that runs real businesses.</h1>', false)
        ->assertSee('Web Development')
        ->assertSee('Order Management')
        ->assertSee('How do I contact Auxtech?')
        ->assertSee('https://wa.me/8801805019415', false)
        ->assertSee('mailto:auxtechbd@gmail.com', false);

    $this->get('/work/gearzen')->assertSee('<h1>Gearzen</h1>', false)->assertSee('Online store for Gearzen.');
    $this->get('/services/website-development')->assertSee('<h1>Web Development</h1>', false);
    $this->get('/solutions/ecommerce')->assertSee('<h1>Auxtech Ecommerce</h1>', false)->assertSee('Conversions API (CAPI)');
});

it('pre-fills WhatsApp messages for the page context and language', function () {
    project(['name' => 'Gearzen', 'slug' => 'gearzen']);

    $this->get('/work/gearzen')->assertInertia(fn (Assert $page) => $page
        ->where('project.whatsapp_url', 'https://wa.me/8801805019415?text='.rawurlencode('Hi Auxtech, I saw your work for Gearzen and want a similar website.')));

    $this->get('/bn/services/website-development')->assertInertia(fn (Assert $page) => $page
        ->where('whatsappUrl', fn (string $url) => str_contains(rawurldecode($url), 'ওয়েব ডেভেলপমেন্ট')));
});

it('serves landing pages and CMS pages from the top-level URL space without shadowing real routes', function () {
    LandingPage::query()->create([
        'slug' => 'ecommerce-website-development-mirpur', 'title' => ['en' => 'E-commerce Website Development in Mirpur'],
        'intro' => ['en' => 'For shops in Mirpur.'], 'status' => PublishStatus::Published,
    ]);

    $this->get('/ecommerce-website-development-mirpur')->assertOk()->assertSee('E-commerce Website Development in Mirpur');
    $this->get('/admin')->assertRedirect('/login');
    $this->get('/unknown-page')->assertNotFound();
});

it('shows a branded 404 page with the right status', function () {
    $this->get('/work/does-not-exist')->assertNotFound()
        ->assertSee('Page not found')
        ->assertSee('noindex', false);
});

it('redirects /auxify to the Auxify solution page', function () {
    $this->get('/auxify')->assertRedirect('/solutions/auxify');
    $this->get('/solutions/auxify')->assertOk()->assertSee('https://auxify.store', false);
});

it('answers site search across content types', function () {
    project(['name' => 'Gearzen', 'slug' => 'gearzen']);

    $this->getJson('/api/search?q=gear')->assertOk()->assertJsonPath('results.0.type', 'project');
    $this->getJson('/api/search?q=ওয়েবসাইট&locale=bn')->assertOk()->assertJsonFragment(['type' => 'service']);
    $this->getJson('/api/search?q=a')->assertStatus(422);
});

it('loads Microsoft Clarity on public pages in production only', function () {
    $this->get('/')->assertDontSee('clarity.ms/tag', false);

    app()->detectEnvironment(fn () => 'production');

    $this->get('/')->assertSee('clarity.ms/tag', false)->assertSee('zehuflmwqj', false);
});

it('sends security headers', function () {
    $this->get('/')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});
