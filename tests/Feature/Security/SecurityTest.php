<?php

use App\Models\Lead;
use App\Models\User;
use Database\Seeders\CatalogueSeeder;

/** Security checks from docs/04-TESTING.md section 7.6 (TC-N04 to TC-N07, TC-N12). */
it('sends a nonce-based Content Security Policy that matches the page', function () {
    $response = $this->get('/');
    $policy = $response->headers->get('Content-Security-Policy');

    preg_match("/'nonce-([^']+)'/", $policy, $nonce);

    expect($policy)->toContain("object-src 'none'", "frame-ancestors 'self'", "base-uri 'self'", "'strict-dynamic'")
        ->and($response->getContent())->toContain('nonce="'.$nonce[1].'"');

    // Every executable inline script carries the nonce.
    preg_match_all('/<script(?![^>]*type="application\/(?:json|ld\+json)")([^>]*)>/', $response->getContent(), $scripts);
    foreach ($scripts[1] as $attributes) {
        expect($attributes)->toContain('nonce="'.$nonce[1].'"');
    }
});

it('uses a different nonce on every request', function () {
    $first = $this->get('/')->headers->get('Content-Security-Policy');
    $second = $this->get('/')->headers->get('Content-Security-Policy');

    expect($first)->not->toBe($second);
});

it('adds HSTS and upgrades insecure requests only over HTTPS', function () {
    $this->get('https://localhost/')
        ->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
        ->assertHeader('Content-Security-Policy');

    expect($this->get('https://localhost/')->headers->get('Content-Security-Policy'))->toContain('upgrade-insecure-requests');
    $this->get('http://localhost/')->assertHeaderMissing('Strict-Transport-Security');
});

it('rejects form posts without a CSRF token', function () {
    // The framework skips the CSRF check while running tests; switch that off for this test.
    $this->app['env'] = 'local';

    $this->post('/api/leads', leadPayload())->assertStatus(419);
    $this->post('/login', ['email' => 'a@b.c', 'password' => 'x'])->assertStatus(419);

    expect(Lead::query()->count())->toBe(0);
});

it('handles injection attempts in search, filters and forms safely', function (string $payload) {
    $this->seed(CatalogueSeeder::class);
    project(['slug' => 'gearzen']);

    $this->getJson('/api/search?q='.urlencode($payload))->assertOk();
    $this->get('/work?industry='.urlencode($payload).'&service='.urlencode($payload))->assertOk();
    $this->actingAs(admin())->get('/admin/projects?search='.urlencode($payload).'&status='.urlencode($payload))->assertOk();
    $this->get('/admin/leads?search='.urlencode($payload).'&status='.urlencode($payload))->assertOk();

    $this->postJson('/api/leads', leadPayload(['name' => $payload, 'message' => $payload]))->assertCreated();
    // Stored as typed (parameter binding), escaped when shown.
    expect(Lead::query()->first()->name)->toBe($payload)->and(Lead::query()->count())->toBe(1);
})->with([
    "' OR 1=1 --",
    '"; DROP TABLE leads; --',
    '%_%',
    '<script>alert(1)</script>',
    '{{7*7}}',
]);

it('escapes visitor-supplied text on admin and public pages', function () {
    $this->postJson('/api/leads', leadPayload(['name' => '<img src=x onerror=alert(1)>', 'message' => '<script>alert(1)</script>']))->assertCreated();
    $lead = Lead::query()->sole();

    $html = $this->actingAs(admin())->get("/admin/leads/{$lead->id}")->getContent();

    expect($html)->not->toContain('<img src=x onerror')->not->toContain('<script>alert(1)</script>');
});

it('ignores fields a visitor must not set on a lead', function () {
    $this->postJson('/api/leads', leadPayload(['status' => 'won', 'assigned_to' => 1, 'ip_hash' => 'x', 'uuid' => 'mine']))->assertCreated();

    $lead = Lead::query()->sole();

    expect($lead->status->value)->toBe('new')->and($lead->assigned_to)->toBeNull()->and($lead->uuid)->not->toBe('mine');
});

it('stops roles from reaching records outside their area by guessing IDs', function () {
    $project = project();
    $lead = Lead::query()->create(['name' => 'X', 'phone' => '+8801805019415', 'source' => 'contact_form', 'status' => 'new']);

    $this->actingAs(admin('Sales'))->get("/admin/projects/{$project->id}/edit")->assertForbidden();
    $this->post("/admin/projects/{$project->id}", ['title' => ['en' => 'Hacked'], 'type' => 'live', 'status' => 'published'])->assertForbidden();
    $this->delete("/admin/projects/{$project->id}")->assertForbidden();

    $this->actingAs(admin('Editor'))->get("/admin/leads/{$lead->id}")->assertForbidden();
    $this->patch("/admin/leads/{$lead->id}", ['status' => 'won'])->assertForbidden();
    $this->post('/admin/users', ['name' => 'Me', 'email' => 'me@example.com', 'password' => 'a-long-password', 'role' => 'Super Admin'])->assertForbidden();
    $this->post('/admin/system/migrate')->assertForbidden();

    expect($project->fresh()->title)->not->toBe('Hacked')->and($lead->fresh()->status->value)->toBe('new')
        ->and(User::query()->where('email', 'me@example.com')->exists())->toBeFalse();
});

it('does not reveal error details in production', function () {
    config(['app.debug' => false]);
    app()->detectEnvironment(fn () => 'production');
    Route::get('/_test/boom/now', fn () => throw new RuntimeException('secret internal detail'))->middleware('web');

    $this->get('/_test/boom/now')->assertStatus(500)->assertDontSee('secret internal detail')->assertDontSee('RuntimeException');
});

it('sets secure, HttpOnly, SameSite session cookies', function () {
    expect(config('session.http_only'))->toBeTrue()
        ->and(config('session.same_site'))->toBe('lax');

    $cookie = collect($this->get('/')->headers->getCookies())->first(fn ($c) => $c->getName() === config('session.cookie'));

    expect($cookie->isHttpOnly())->toBeTrue()->and($cookie->getSameSite())->toBe('lax');
});

it('keeps the admin panel and installer out of search results', function () {
    $this->get('/login')->assertSee('<meta name="robots" content="noindex', false);
    $this->get('/robots.txt')->assertSee('Disallow: /admin')->assertSee('Disallow: /install');
});
