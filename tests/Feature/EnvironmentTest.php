<?php

use Inertia\Testing\AssertableInertia as Assert;

it('serves the home page through Inertia', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Site/Home')
            ->where('company.name', 'Auxtech')
            ->where('whatsappUrl', 'https://wa.me/8801805019415'));
});

it('exposes the health endpoint', function () {
    $this->get('/up')->assertOk();
});

it('holds the company facts in config', function () {
    expect(config('auxtech.company.name'))->toBe('Auxtech')
        ->and(config('auxtech.company.phone.e164'))->toBe('+8801805019415')
        ->and(config('auxtech.company.domain'))->toBe('auxtechbd.com')
        ->and(config('auxtech.company.email'))->toBe('auxtechbd@gmail.com')
        ->and(config('auxtech.hours.closed_days'))->toBe(['friday'])
        ->and(config('auxtech.social.facebook'))->toBe('https://www.facebook.com/auxtechs/')
        ->and(config('auxtech.locales.supported'))->toBe(['en', 'bn']);
});

it('ships the brand assets', function (string $file) {
    expect(public_path($file))->toBeFile();
})->with([
    'brand/auxtech-logo.svg',
    'brand/auxtech-logo-white.svg',
    'brand/auxtech-icon.svg',
    'favicon.svg',
    'favicon.ico',
    'apple-touch-icon.png',
    'site.webmanifest',
]);

it('keeps the remaining config intact', function () {
    expect(config('auxtech.whatsapp.number'))->toBe('8801805019415');
});

it('is configured to broadcast through Pusher on the ap2 cluster', function () {
    expect(config('broadcasting.connections.pusher.driver'))->toBe('pusher')
        ->and(config('broadcasting.connections.pusher.options.cluster'))->not->toBeNull();
});

it('loads Microsoft Clarity on public pages in production only', function () {
    $this->get('/')->assertOk()->assertDontSee('clarity.ms/tag', false);

    app()->detectEnvironment(fn () => 'production');

    $this->get('/')->assertOk()
        ->assertSee('clarity.ms/tag', false)
        ->assertSee('zehuflmwqj', false);
});

it('keeps server-side rendering off because production has no Node runtime', function () {
    expect(config('inertia.ssr.enabled'))->toBeFalse();
});
