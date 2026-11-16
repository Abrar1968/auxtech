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
        ->and(config('auxtech.locales.supported'))->toBe(['en', 'bn']);
});
