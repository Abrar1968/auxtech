<?php

/*
|--------------------------------------------------------------------------
| Site settings: defaults
|--------------------------------------------------------------------------
|
| Every value here is editable in Admin > Settings and stored in the
| `settings` table; these are only the starting values. Arrays with
| `en` / `bn` keys are translatable.
|
*/

return [

    'defaults' => [
        'company.name' => 'Auxtech',
        'company.tagline' => [
            'en' => 'Website development, app development and digital marketing',
            'bn' => 'ওয়েবসাইট ডেভেলপমেন্ট, অ্যাপ ডেভেলপমেন্ট ও ডিজিটাল মার্কেটিং',
        ],
        'company.slogan' => [
            'en' => "Don't go for a company that makes simple software, go for a company that can make the software simple.",
            'bn' => null,
        ],
        'company.description' => [
            'en' => 'Auxtech is a Dhaka-based web, app and digital marketing company that specialises in e-commerce platforms for Bangladeshi brands.',
            'bn' => 'Auxtech ঢাকা-ভিত্তিক একটি ওয়েব, অ্যাপ ও ডিজিটাল মার্কেটিং কোম্পানি, যা বাংলাদেশি ব্র্যান্ডের জন্য ই-কমার্স প্ল্যাটফর্ম তৈরিতে বিশেষজ্ঞ।',
        ],
        'company.url' => 'https://auxtechbd.com',
        'company.email' => 'auxtechbd@gmail.com',
        'company.phone' => '+8801805019415',
        'company.phone_display' => '01805019415',
        'company.address' => [
            'en' => '754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue, Sharini 1216, Dhaka, Bangladesh',
            'bn' => '৭৫৪/১, ৫ম তলা, পশ্চিম শেওড়াপাড়া, বেগম রোকেয়া এভিনিউ, ঢাকা ১২১৬, বাংলাদেশ',
        ],
        'company.street' => '754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue',
        'company.city' => 'Dhaka',
        'company.postal_code' => '1216',
        'company.country' => 'BD',
        'company.founded_year' => null,
        'company.latitude' => null,
        'company.longitude' => null,

        'hours.text' => [
            'en' => 'Every day except Friday, 10am to 6pm',
            'bn' => 'শুক্রবার ছাড়া প্রতিদিন, সকাল ১০টা থেকে সন্ধ্যা ৬টা',
        ],
        'hours.opens' => '10:00',
        'hours.closes' => '18:00',
        'hours.open_days' => ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'],

        'social.facebook' => 'https://www.facebook.com/auxtechs/',
        'social.instagram' => 'https://www.instagram.com/auxtech.bd/',
        'social.linkedin' => null,
        'social.youtube' => null,
        'social.google_business' => null,

        'whatsapp.number' => '8801805019415',
        'whatsapp.templates.default' => [
            'en' => 'Hi Auxtech, I would like to talk about a project.',
            'bn' => 'হ্যালো Auxtech, আমি একটি প্রজেক্ট নিয়ে কথা বলতে চাই।',
        ],
        'whatsapp.templates.service' => [
            'en' => 'Hi Auxtech, I am interested in your :name service.',
            'bn' => 'হ্যালো Auxtech, আমি আপনাদের :name সার্ভিস সম্পর্কে জানতে চাই।',
        ],
        'whatsapp.templates.solution' => [
            'en' => 'Hi Auxtech, I am interested in :name. Can I see a demo?',
            'bn' => 'হ্যালো Auxtech, আমি :name সম্পর্কে আগ্রহী। একটি ডেমো দেখতে পারি?',
        ],
        'whatsapp.templates.project' => [
            'en' => 'Hi Auxtech, I saw your work for :name and want a similar website.',
            'bn' => 'হ্যালো Auxtech, আমি :name-এর কাজটি দেখেছি এবং এরকম একটি ওয়েবসাইট চাই।',
        ],
        'whatsapp.templates.plan' => [
            'en' => 'Hi Auxtech, I am interested in the :name package.',
            'bn' => 'হ্যালো Auxtech, আমি :name প্যাকেজটি নিতে আগ্রহী।',
        ],
        'whatsapp.templates.estimator' => [
            'en' => 'Hi Auxtech, I need a quote. :name',
            'bn' => 'হ্যালো Auxtech, আমার একটি কোটেশন দরকার। :name',
        ],

        'home.hero_title' => [
            'en' => 'We build the online stores Bangladesh buys from.',
            'bn' => 'আমরা তৈরি করি সেই অনলাইন স্টোর, যেখান থেকে বাংলাদেশ কেনাকাটা করে।',
        ],
        'home.hero_subtitle' => [
            'en' => 'Website development, app development and digital marketing from one Dhaka team.',
            'bn' => 'ওয়েবসাইট ডেভেলপমেন্ট, অ্যাপ ডেভেলপমেন্ট ও ডিজিটাল মার্কেটিং, ঢাকার একটি টিম থেকে।',
        ],
        // Order and visibility of home page sections.
        'home.sections' => [
            ['key' => 'hero', 'enabled' => true],
            ['key' => 'clients', 'enabled' => true],
            ['key' => 'stats', 'enabled' => true],
            ['key' => 'services', 'enabled' => true],
            ['key' => 'flagship', 'enabled' => true],
            ['key' => 'work', 'enabled' => true],
            ['key' => 'solutions', 'enabled' => true],
            ['key' => 'testimonials', 'enabled' => true],
            ['key' => 'pricing', 'enabled' => true],
            ['key' => 'posts', 'enabled' => true],
            ['key' => 'faq', 'enabled' => true],
            ['key' => 'cta', 'enabled' => true],
        ],

        // Title and description of each listing page (search results and browser tab).
        'pages.work.title' => ['en' => 'Our Work: E-commerce Websites and Software Built by Auxtech', 'bn' => 'আমাদের কাজ: Auxtech-এর তৈরি ই-কমার্স ওয়েবসাইট ও সফটওয়্যার'],
        'pages.work.description' => ['en' => 'Browse live projects by Auxtech: fashion, gadget, grocery and general e-commerce stores, reseller platforms, LMS and custom software in Bangladesh.', 'bn' => 'Auxtech-এর লাইভ প্রজেক্টগুলো দেখুন: ফ্যাশন, গ্যাজেট, গ্রোসারি ই-কমার্স স্টোর, রিসেলার প্ল্যাটফর্ম, LMS ও কাস্টম সফটওয়্যার।'],
        'pages.services.title' => ['en' => 'Services: Website, App Development and Digital Marketing', 'bn' => 'সার্ভিস: ওয়েবসাইট, অ্যাপ ডেভেলপমেন্ট ও ডিজিটাল মার্কেটিং'],
        'pages.services.description' => ['en' => 'Auxtech services: website development, app development and digital marketing for businesses in Bangladesh.', 'bn' => 'Auxtech-এর সার্ভিস: বাংলাদেশের ব্যবসার জন্য ওয়েবসাইট ডেভেলপমেন্ট, অ্যাপ ডেভেলপমেন্ট ও ডিজিটাল মার্কেটিং।'],
        'pages.solutions.title' => ['en' => 'Solutions: E-commerce, Reseller Platform, LMS and Custom Software', 'bn' => 'সলিউশন: ই-কমার্স, রিসেলার প্ল্যাটফর্ম, LMS ও কাস্টম সফটওয়্যার'],
        'pages.solutions.description' => ['en' => 'Ready-made and custom software from Auxtech: e-commerce with POS and inventory, reseller and dropshipping platforms, LMS and more.', 'bn' => 'Auxtech-এর রেডিমেড ও কাস্টম সফটওয়্যার: POS ও ইনভেন্টরিসহ ই-কমার্স, রিসেলার ও ড্রপশিপিং প্ল্যাটফর্ম, LMS এবং আরও অনেক কিছু।'],
        'pages.pricing.title' => ['en' => 'Pricing: Website and E-commerce Development Cost in Bangladesh', 'bn' => 'প্রাইসিং: বাংলাদেশে ওয়েবসাইট ও ই-কমার্স ডেভেলপমেন্টের খরচ'],
        'pages.pricing.description' => ['en' => 'Packages and prices for e-commerce websites, business websites and apps from Auxtech. Ask for a quote on WhatsApp.', 'bn' => 'Auxtech-এর ই-কমার্স ওয়েবসাইট, বিজনেস ওয়েবসাইট ও অ্যাপের প্যাকেজ ও মূল্য। হোয়াটসঅ্যাপে কোটেশন নিন।'],
        'pages.about.title' => ['en' => 'About Auxtech: Web, App and Digital Marketing Company in Dhaka', 'bn' => 'Auxtech সম্পর্কে: ঢাকার ওয়েব, অ্যাপ ও ডিজিটাল মার্কেটিং কোম্পানি'],
        'pages.about.description' => ['en' => 'Auxtech is a Dhaka-based web, app and digital marketing company that specialises in e-commerce platforms for Bangladeshi brands.', 'bn' => 'Auxtech ঢাকা-ভিত্তিক একটি ওয়েব, অ্যাপ ও ডিজিটাল মার্কেটিং কোম্পানি, যা বাংলাদেশি ব্র্যান্ডের জন্য ই-কমার্স প্ল্যাটফর্ম তৈরিতে বিশেষজ্ঞ।'],
        'pages.faq.title' => ['en' => 'Frequently Asked Questions', 'bn' => 'সাধারণ জিজ্ঞাসা'],
        'pages.faq.description' => ['en' => 'Answers about e-commerce website cost, timeline, features, hosting and support from Auxtech.', 'bn' => 'ই-কমার্স ওয়েবসাইটের খরচ, সময়, ফিচার, হোস্টিং ও সাপোর্ট নিয়ে Auxtech-এর উত্তর।'],
        'pages.contact.title' => ['en' => 'Contact Auxtech on WhatsApp or Email', 'bn' => 'Auxtech-এর সাথে যোগাযোগ: হোয়াটসঅ্যাপ বা ইমেইল'],
        'pages.contact.description' => ['en' => 'Talk to Auxtech on WhatsApp or email. Office: West Shewrapara, Begum Rokeya Avenue, Dhaka.', 'bn' => 'হোয়াটসঅ্যাপ বা ইমেইলে Auxtech-এর সাথে কথা বলুন। অফিস: পশ্চিম শেওড়াপাড়া, বেগম রোকেয়া এভিনিউ, ঢাকা।'],
        'pages.estimate.title' => ['en' => 'Get a Project Estimate', 'bn' => 'প্রজেক্টের আনুমানিক খরচ জানুন'],
        'pages.estimate.description' => ['en' => 'Answer four short questions and get a quote for your website, app or marketing project on WhatsApp.', 'bn' => 'চারটি ছোট প্রশ্নের উত্তর দিন এবং আপনার ওয়েবসাইট, অ্যাপ বা মার্কেটিং প্রজেক্টের কোটেশন হোয়াটসঅ্যাপে নিন।'],
        'pages.blog.title' => ['en' => 'Blog: E-commerce, Web Development and Marketing Guides', 'bn' => 'ব্লগ: ই-কমার্স, ওয়েব ডেভেলপমেন্ট ও মার্কেটিং গাইড'],
        'pages.blog.description' => ['en' => 'Practical guides for online businesses in Bangladesh from the Auxtech team.', 'bn' => 'বাংলাদেশের অনলাইন ব্যবসার জন্য Auxtech টিমের ব্যবহারিক গাইড।'],

        'seo.title_suffix' => 'Auxtech',
        'seo.default_title' => [
            'en' => 'Web Development Company in Bangladesh | Auxtech',
            'bn' => 'বাংলাদেশের ওয়েব ডেভেলপমেন্ট কোম্পানি | Auxtech',
        ],
        'seo.default_description' => [
            'en' => 'Auxtech builds e-commerce websites, apps and digital marketing for brands in Bangladesh. 30+ live stores. Chat with us on WhatsApp.',
            'bn' => 'Auxtech বাংলাদেশের ব্র্যান্ডের জন্য ই-কমার্স ওয়েবসাইট, অ্যাপ ও ডিজিটাল মার্কেটিং করে। হোয়াটসঅ্যাপে কথা বলুন।',
        ],
        'seo.robots_extra' => null,
        'seo.google_site_verification' => null,
        'seo.bing_site_verification' => null,

        'tracking.clarity_id' => 'zehuflmwqj',
        'tracking.gtm_id' => null,
        'tracking.meta_pixel_id' => null,
        'tracking.meta_capi_token' => null,

        'leads.notify_email' => 'auxtechbd@gmail.com',

        // Gmail SMTP with a Google App Password (docs/02 §11.2).
        'mail.host' => 'smtp.gmail.com',
        'mail.port' => 587,
        'mail.username' => 'auxtechbd@gmail.com',
        'mail.password' => null,
        'mail.from_name' => 'Auxtech Website',
    ],

    // Key prefixes that the public site may read.
    'public' => ['company.', 'hours.', 'social.', 'whatsapp.number', 'home.'],

    // Stored encrypted and never sent to the browser.
    'secret' => ['mail.password', 'tracking.meta_capi_token'],

];
