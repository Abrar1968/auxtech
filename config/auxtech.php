<?php

/*
|--------------------------------------------------------------------------
| Auxtech company facts
|--------------------------------------------------------------------------
|
| Bootstrap defaults for the site. In Phase 1 these seed the `settings`
| table, after which the admin panel is the source of truth.
|
*/

return [

    'company' => [
        'name' => 'Auxtech',
        'tagline' => 'Website development, app development and digital marketing',
        'address' => [
            'street' => '754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue',
            'locality' => 'Dhaka',
            'postal_code' => '1216',
            'country' => 'BD',
            'full' => '754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue, Sharini 1216, Dhaka, Bangladesh',
        ],
        'phone' => [
            'local' => '01805019415',
            'e164' => '+8801805019415',
        ],
    ],

    'whatsapp' => [
        // International format without "+", as required by wa.me links.
        'number' => env('WHATSAPP_NUMBER', '8801805019415'),
        'base_url' => 'https://wa.me/',
    ],

    'brand' => [
        'color' => '#0145FF',
        'logo' => '/brand/auxtech-logo.webp',
    ],

    'locales' => [
        'default' => 'en',
        'supported' => ['en', 'bn'],
    ],

    'leads' => [
        'notify_email' => env('LEADS_NOTIFY_EMAIL'),
    ],

    'tracking' => [
        'gtm_id' => env('GTM_ID'),
        'meta_pixel_id' => env('META_PIXEL_ID'),
        'meta_capi_token' => env('META_CAPI_TOKEN'),
    ],

];
