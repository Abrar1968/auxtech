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

    // True in development and tests; production is marked installed by the browser installer.
    'installed' => (bool) env('APP_INSTALLED', false),

    'company' => [
        'name' => 'Auxtech',
        'tagline' => 'Website development, app development and digital marketing',
        'slogan' => "Don't go for a company that makes simple software, go for a company that can make the software simple.",
        'domain' => 'auxtechbd.com',
        'url' => 'https://auxtechbd.com',
        'email' => 'auxtechbd@gmail.com',
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

    // Open every day except Friday, 10:00-18:00 Bangladesh time (Asia/Dhaka).
    'hours' => [
        'timezone' => 'Asia/Dhaka',
        'opens' => '10:00',
        'closes' => '18:00',
        'open_days' => ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'],
        'closed_days' => ['friday'],
    ],

    'social' => [
        'facebook' => 'https://www.facebook.com/auxtechs/',
        'instagram' => 'https://www.instagram.com/auxtech.bd/',
    ],

    'whatsapp' => [
        // International format without "+", as required by wa.me links.
        'number' => env('WHATSAPP_NUMBER', '8801805019415'),
        'base_url' => 'https://wa.me/',
    ],

    'brand' => [
        'color' => '#0145FF',
        'logo' => '/brand/auxtech-logo.svg',
        'logo_white' => '/brand/auxtech-logo-white.svg',
        'icon' => '/brand/auxtech-icon.svg',
        'icon_white' => '/brand/auxtech-icon-white.svg',
    ],

    'locales' => [
        'default' => 'en',
        'supported' => ['en', 'bn'],
    ],

    'leads' => [
        // Every notification goes to this inbox.
        'notify_email' => env('LEADS_NOTIFY_EMAIL', 'auxtechbd@gmail.com'),
    ],

    'tracking' => [
        'clarity_id' => env('CLARITY_PROJECT_ID', 'zehuflmwqj'),
        'gtm_id' => env('GTM_ID'),
        'meta_pixel_id' => env('META_PIXEL_ID'),
        'meta_capi_token' => env('META_CAPI_TOKEN'),
    ],

];
