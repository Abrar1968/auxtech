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
            ['key' => 'process', 'enabled' => true],
            ['key' => 'testimonials', 'enabled' => true],
            ['key' => 'reasons', 'enabled' => true],
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

        // Heading and intro line of each home page section.
        'home.copy.clients' => ['title' => ['en' => 'Trusted by brands across Bangladesh', 'bn' => 'বাংলাদেশজুড়ে ব্র্যান্ডগুলোর আস্থা'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.stats' => ['title' => ['en' => 'Auxtech in numbers', 'bn' => 'সংখ্যায় Auxtech'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.services' => ['title' => ['en' => 'What we do', 'bn' => 'আমরা যা করি'], 'lead' => ['en' => 'Websites, apps and marketing for businesses that sell online.', 'bn' => 'অনলাইনে বিক্রি করা ব্যবসার জন্য ওয়েবসাইট, অ্যাপ ও মার্কেটিং।']],
        'home.copy.flagship' => ['title' => ['en' => 'Everything a Bangladeshi online store needs, in one admin panel', 'bn' => 'বাংলাদেশি অনলাইন স্টোরের যা দরকার, সব এক অ্যাডমিন প্যানেলে'], 'lead' => ['en' => 'Orders, stock, POS, accounting, courier and Meta tracking, built in.', 'bn' => 'অর্ডার, স্টক, POS, অ্যাকাউন্টিং, কুরিয়ার ও মেটা ট্র্যাকিং, সব বিল্ট-ইন।']],
        'home.copy.work' => ['title' => ['en' => 'Stores and platforms we have built', 'bn' => 'আমাদের তৈরি স্টোর ও প্ল্যাটফর্ম'], 'lead' => ['en' => 'Live sites you can visit today.', 'bn' => 'লাইভ সাইট, আজই ঘুরে দেখতে পারেন।']],
        'home.copy.solutions' => ['title' => ['en' => 'Beyond e-commerce', 'bn' => 'ই-কমার্সের বাইরেও'], 'lead' => ['en' => 'Reseller platforms, learning systems, corporate sites and custom software.', 'bn' => 'রিসেলার প্ল্যাটফর্ম, লার্নিং সিস্টেম, কর্পোরেট সাইট ও কাস্টম সফটওয়্যার।']],
        'home.copy.process' => ['title' => ['en' => 'How we work', 'bn' => 'আমরা যেভাবে কাজ করি'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.testimonials' => ['title' => ['en' => 'What clients say', 'bn' => 'ক্লায়েন্টরা যা বলেন'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.reasons' => ['title' => ['en' => 'Why brands choose Auxtech', 'bn' => 'কেন ব্র্যান্ডগুলো Auxtech বেছে নেয়'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.pricing' => ['title' => ['en' => 'Simple packages', 'bn' => 'সহজ প্যাকেজ'], 'lead' => ['en' => 'Get an exact quote on WhatsApp in minutes.', 'bn' => 'কয়েক মিনিটে হোয়াটসঅ্যাপে সঠিক কোটেশন নিন।']],
        'home.copy.posts' => ['title' => ['en' => 'Guides for online sellers', 'bn' => 'অনলাইন বিক্রেতাদের জন্য গাইড'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.faq' => ['title' => ['en' => 'Questions, answered', 'bn' => 'প্রশ্ন ও উত্তর'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.cta' => ['title' => ['en' => 'Ready to start selling online?', 'bn' => 'অনলাইনে বিক্রি শুরু করতে প্রস্তুত?'], 'lead' => ['en' => 'Tell us about your business on WhatsApp. We usually reply within minutes.', 'bn' => 'হোয়াটসঅ্যাপে আপনার ব্যবসার কথা বলুন। আমরা সাধারণত কয়েক মিনিটেই উত্তর দিই।']],

        // On-page heading (H1) and intro line of each listing page.
        'pages.work.heading' => ['en' => 'Our work', 'bn' => 'আমাদের কাজ'],
        'pages.work.lead' => ['en' => 'Online stores, reseller platforms and custom software for brands across Bangladesh.', 'bn' => 'বাংলাদেশের ব্র্যান্ডগুলোর জন্য অনলাইন স্টোর, রিসেলার প্ল্যাটফর্ম ও কাস্টম সফটওয়্যার।'],
        'pages.services.heading' => ['en' => 'Services', 'bn' => 'সার্ভিস'],
        'pages.services.lead' => ['en' => 'Website development, app development and digital marketing from one team.', 'bn' => 'একটি টিম থেকে ওয়েবসাইট ডেভেলপমেন্ট, অ্যাপ ডেভেলপমেন্ট ও ডিজিটাল মার্কেটিং।'],
        'pages.solutions.heading' => ['en' => 'Solutions', 'bn' => 'সলিউশন'],
        'pages.solutions.lead' => ['en' => 'Ready-made systems you can launch fast, and custom software when you need something new.', 'bn' => 'দ্রুত চালু করার মতো রেডিমেড সিস্টেম, আর নতুন কিছু দরকার হলে কাস্টম সফটওয়্যার।'],
        'pages.pricing.heading' => ['en' => 'Pricing', 'bn' => 'প্রাইসিং'],
        'pages.pricing.lead' => ['en' => 'Clear packages. Message us on WhatsApp for an exact quote.', 'bn' => 'পরিষ্কার প্যাকেজ। সঠিক কোটেশনের জন্য হোয়াটসঅ্যাপে মেসেজ দিন।'],
        'pages.about.heading' => ['en' => 'About Auxtech', 'bn' => 'Auxtech সম্পর্কে'],
        'pages.about.lead' => ['en' => 'A Dhaka team that builds the technology behind online businesses.', 'bn' => 'ঢাকার একটি টিম, যারা অনলাইন ব্যবসার পেছনের প্রযুক্তি তৈরি করে।'],
        'pages.faq.heading' => ['en' => 'Frequently asked questions', 'bn' => 'সাধারণ জিজ্ঞাসা'],
        'pages.faq.lead' => ['en' => 'Cost, timeline, features and support.', 'bn' => 'খরচ, সময়, ফিচার ও সাপোর্ট।'],
        'pages.contact.heading' => ['en' => 'Let\'s talk', 'bn' => 'চলুন কথা বলি'],
        'pages.contact.lead' => ['en' => 'WhatsApp is the fastest way to reach us. Email works too.', 'bn' => 'আমাদের কাছে পৌঁছানোর সবচেয়ে দ্রুত উপায় হোয়াটসঅ্যাপ। ইমেইলও করতে পারেন।'],
        'pages.estimate.heading' => ['en' => 'Get an estimate', 'bn' => 'খরচের হিসাব নিন'],
        'pages.estimate.lead' => ['en' => 'Four quick questions. We send the quote on WhatsApp.', 'bn' => 'চারটি ছোট প্রশ্ন। কোটেশন আমরা হোয়াটসঅ্যাপে পাঠাব।'],
        'pages.blog.heading' => ['en' => 'Blog', 'bn' => 'ব্লগ'],
        'pages.blog.lead' => ['en' => 'Practical guides for selling online in Bangladesh.', 'bn' => 'বাংলাদেশে অনলাইনে বিক্রির ব্যবহারিক গাইড।'],

        'home.process_steps' => [
            ['title' => ['en' => 'Discover', 'bn' => 'আলোচনা'], 'description' => ['en' => 'We learn how you sell today and what you need next.', 'bn' => 'আপনি এখন কীভাবে বিক্রি করেন আর পরবর্তীতে কী দরকার, তা আমরা বুঝে নিই।']],
            ['title' => ['en' => 'Design', 'bn' => 'ডিজাইন'], 'description' => ['en' => 'We plan pages, features and the look of your brand.', 'bn' => 'পেজ, ফিচার আর আপনার ব্র্যান্ডের লুক পরিকল্পনা করি।']],
            ['title' => ['en' => 'Build', 'bn' => 'ডেভেলপমেন্ট'], 'description' => ['en' => 'We build and connect payments, courier and tracking.', 'bn' => 'পেমেন্ট, কুরিয়ার ও ট্র্যাকিংসহ পুরো সিস্টেম তৈরি করি।']],
            ['title' => ['en' => 'Launch', 'bn' => 'লঞ্চ'], 'description' => ['en' => 'We go live, train your team and check every order flow.', 'bn' => 'লাইভ করি, আপনার টিমকে ট্রেনিং দিই এবং প্রতিটি অর্ডার ফ্লো যাচাই করি।']],
            ['title' => ['en' => 'Grow', 'bn' => 'গ্রোথ'], 'description' => ['en' => 'We help you bring buyers with ads, SEO and tracking.', 'bn' => 'অ্যাড, SEO ও ট্র্যাকিং দিয়ে ক্রেতা আনতে সাহায্য করি।']],
        ],
        'home.reasons' => [
            ['icon' => 'truck', 'title' => ['en' => 'Built for cash on delivery', 'bn' => 'ক্যাশ অন ডেলিভারির জন্য তৈরি'], 'description' => ['en' => 'Order confirmation, courier booking and tracking that fit how Bangladesh buys.', 'bn' => 'অর্ডার কনফার্মেশন, কুরিয়ার বুকিং ও ট্র্যাকিং, বাংলাদেশের কেনাকাটার ধরন অনুযায়ী।']],
            ['icon' => 'shopping-cart', 'title' => ['en' => 'Recover lost orders', 'bn' => 'হারানো অর্ডার ফিরিয়ে আনুন'], 'description' => ['en' => 'Incomplete checkouts are saved so your team can follow up.', 'bn' => 'অসম্পূর্ণ চেকআউট সেভ থাকে, যাতে আপনার টিম ফলো-আপ করতে পারে।']],
            ['icon' => 'activity', 'title' => ['en' => 'Ads that see every sale', 'bn' => 'প্রতিটি বিক্রি দেখে এমন অ্যাড'], 'description' => ['en' => 'Meta Pixel and server-side Conversions API come set up.', 'bn' => 'মেটা পিক্সেল ও সার্ভার-সাইড কনভার্সন এপিআই সেটআপ করা থাকে।']],
            ['icon' => 'monitor', 'title' => ['en' => 'Shop and showroom together', 'bn' => 'অনলাইন ও শোরুম একসাথে'], 'description' => ['en' => 'Point of sale and inventory share the same stock.', 'bn' => 'পয়েন্ট অব সেল ও ইনভেন্টরি একই স্টক থেকে চলে।']],
            ['icon' => 'layout-dashboard', 'title' => ['en' => 'One panel for the business', 'bn' => 'পুরো ব্যবসা এক প্যানেলে'], 'description' => ['en' => 'Orders, accounts, staff and campaigns in one place.', 'bn' => 'অর্ডার, হিসাব, স্টাফ ও ক্যাম্পেইন এক জায়গায়।']],
            ['icon' => 'message-circle', 'title' => ['en' => 'A local team', 'bn' => 'স্থানীয় টিম'], 'description' => ['en' => 'Talk to the people who built your store, in Bangla or English.', 'bn' => 'যারা আপনার স্টোর বানিয়েছে, তাদের সাথেই বাংলা বা ইংরেজিতে কথা বলুন।']],
        ],
        'about.story' => [
            'en' => "Auxtech is a Dhaka-based web, app and digital marketing company. Most of what we build is e-commerce: online stores for fashion, gadget, grocery and general retail brands, all running on our own commerce engine with POS, inventory, courier integration and Meta tracking built in.\n\nWe also build reseller and dropshipping platforms, learning systems, corporate websites and custom software, and we run Auxify, our own store builder.",
            'bn' => null,
        ],
        'company.map_url' => null,
        'security.require_two_factor' => false,

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

    /*
    | How Admin > Settings shows each key. Types: text, textarea, url, email,
    | number, toggle, secret, days, sections, copy, list (with item fields).
    | Translatable values (en/bn) are detected from their defaults.
    */
    'schema' => [
        'company' => ['label' => 'Company', 'fields' => [
            'company.name' => ['text', 'Company name'],
            'company.tagline' => ['text', 'Tagline'],
            'company.slogan' => ['textarea', 'Slogan'],
            'company.description' => ['textarea', 'Short description', 'Used in search results, AI summaries and the footer. Keep it factual.'],
            'company.email' => ['email', 'Email'],
            'company.phone' => ['text', 'Phone (international format)', 'e.g. +8801805019415'],
            'company.phone_display' => ['text', 'Phone as shown on the site'],
            'company.address' => ['textarea', 'Address'],
            'company.street' => ['text', 'Street (for search engines)'],
            'company.city' => ['text', 'City'],
            'company.postal_code' => ['text', 'Postal code'],
            'company.founded_year' => ['number', 'Founding year', 'Shown only when filled in.'],
            'company.map_url' => ['url', 'Google Maps link', 'Paste the "Share" link of your office on Google Maps.'],
            'company.latitude' => ['text', 'Latitude'],
            'company.longitude' => ['text', 'Longitude'],
            'about.story' => ['textarea', 'About page: our story'],
        ]],
        'contact' => ['label' => 'Contact & hours', 'fields' => [
            'whatsapp.number' => ['text', 'WhatsApp number', 'International format without +, e.g. 8801805019415. Every WhatsApp button uses it.'],
            'hours.text' => ['text', 'Office hours (as shown)'],
            'hours.opens' => ['text', 'Opens at (24h)', 'e.g. 10:00'],
            'hours.closes' => ['text', 'Closes at (24h)', 'e.g. 18:00'],
            'hours.open_days' => ['days', 'Open days'],
            'social.facebook' => ['url', 'Facebook'],
            'social.instagram' => ['url', 'Instagram'],
            'social.linkedin' => ['url', 'LinkedIn'],
            'social.youtube' => ['url', 'YouTube'],
            'social.google_business' => ['url', 'Google Business Profile'],
        ]],
        'whatsapp' => ['label' => 'WhatsApp messages', 'fields' => [
            'whatsapp.templates.default' => ['textarea', 'General message'],
            'whatsapp.templates.service' => ['textarea', 'From a service page', ':name is replaced with the service name.'],
            'whatsapp.templates.solution' => ['textarea', 'From a solution page', ':name is replaced with the solution name.'],
            'whatsapp.templates.project' => ['textarea', 'From a case study', ':name is replaced with the client name.'],
            'whatsapp.templates.plan' => ['textarea', 'From a pricing plan', ':name is replaced with the plan name.'],
            'whatsapp.templates.estimator' => ['textarea', 'After the estimator', ':name is replaced with the answers.'],
        ]],
        'home' => ['label' => 'Home page', 'fields' => [
            'home.hero_title' => ['textarea', 'Headline (H1)'],
            'home.hero_subtitle' => ['textarea', 'Sub-headline'],
            'home.sections' => ['sections', 'Sections, in order', 'Drag to reorder. Sections with no content (for example testimonials) hide themselves.'],
            'home.copy.clients' => ['copy', 'Client strip'],
            'home.copy.stats' => ['copy', 'Numbers'],
            'home.copy.services' => ['copy', 'Services'],
            'home.copy.flagship' => ['copy', 'Auxtech Ecommerce'],
            'home.copy.work' => ['copy', 'Featured work'],
            'home.copy.solutions' => ['copy', 'Beyond e-commerce'],
            'home.copy.process' => ['copy', 'Process'],
            'home.process_steps' => ['list', 'Process steps', null, ['title' => 'text', 'description' => 'textarea']],
            'home.copy.testimonials' => ['copy', 'Testimonials'],
            'home.copy.reasons' => ['copy', 'Why Auxtech'],
            'home.reasons' => ['list', 'Reasons', null, ['icon' => 'icon', 'title' => 'text', 'description' => 'textarea']],
            'home.copy.pricing' => ['copy', 'Pricing'],
            'home.copy.posts' => ['copy', 'Blog'],
            'home.copy.faq' => ['copy', 'FAQ'],
            'home.copy.cta' => ['copy', 'Closing call to action'],
        ]],
        'pages' => ['label' => 'Page headings', 'fields' => [
            'pages.work.heading' => ['text', 'Work: heading'], 'pages.work.lead' => ['textarea', 'Work: intro'],
            'pages.services.heading' => ['text', 'Services: heading'], 'pages.services.lead' => ['textarea', 'Services: intro'],
            'pages.solutions.heading' => ['text', 'Solutions: heading'], 'pages.solutions.lead' => ['textarea', 'Solutions: intro'],
            'pages.pricing.heading' => ['text', 'Pricing: heading'], 'pages.pricing.lead' => ['textarea', 'Pricing: intro'],
            'pages.about.heading' => ['text', 'About: heading'], 'pages.about.lead' => ['textarea', 'About: intro'],
            'pages.faq.heading' => ['text', 'FAQ: heading'], 'pages.faq.lead' => ['textarea', 'FAQ: intro'],
            'pages.contact.heading' => ['text', 'Contact: heading'], 'pages.contact.lead' => ['textarea', 'Contact: intro'],
            'pages.estimate.heading' => ['text', 'Estimate: heading'], 'pages.estimate.lead' => ['textarea', 'Estimate: intro'],
            'pages.blog.heading' => ['text', 'Blog: heading'], 'pages.blog.lead' => ['textarea', 'Blog: intro'],
        ]],
        'seo' => ['label' => 'Search & analytics', 'fields' => [
            'seo.default_title' => ['text', 'Home page title', 'Shown in Google results. Up to 60 characters.'],
            'seo.default_description' => ['textarea', 'Home page description', 'Up to 155 characters.'],
            'seo.title_suffix' => ['text', 'Added to every page title'],
            'pages.work.title' => ['text', 'Work: search title'], 'pages.work.description' => ['textarea', 'Work: search description'],
            'pages.services.title' => ['text', 'Services: search title'], 'pages.services.description' => ['textarea', 'Services: search description'],
            'pages.solutions.title' => ['text', 'Solutions: search title'], 'pages.solutions.description' => ['textarea', 'Solutions: search description'],
            'pages.pricing.title' => ['text', 'Pricing: search title'], 'pages.pricing.description' => ['textarea', 'Pricing: search description'],
            'pages.about.title' => ['text', 'About: search title'], 'pages.about.description' => ['textarea', 'About: search description'],
            'pages.faq.title' => ['text', 'FAQ: search title'], 'pages.faq.description' => ['textarea', 'FAQ: search description'],
            'pages.contact.title' => ['text', 'Contact: search title'], 'pages.contact.description' => ['textarea', 'Contact: search description'],
            'pages.estimate.title' => ['text', 'Estimate: search title'], 'pages.estimate.description' => ['textarea', 'Estimate: search description'],
            'pages.blog.title' => ['text', 'Blog: search title'], 'pages.blog.description' => ['textarea', 'Blog: search description'],
            'seo.google_site_verification' => ['text', 'Google Search Console code'],
            'seo.bing_site_verification' => ['text', 'Bing Webmaster code'],
            'seo.robots_extra' => ['textarea', 'Extra robots.txt rules'],
            'tracking.clarity_id' => ['text', 'Microsoft Clarity project ID'],
            'tracking.gtm_id' => ['text', 'Google Tag Manager ID', 'e.g. GTM-XXXXXXX'],
            'tracking.meta_pixel_id' => ['text', 'Meta Pixel ID'],
            'tracking.meta_capi_token' => ['secret', 'Meta Conversions API token'],
        ]],
        'mail' => ['label' => 'Email', 'fields' => [
            'leads.notify_email' => ['email', 'Send new leads to'],
            'mail.username' => ['email', 'Gmail address used to send'],
            'mail.password' => ['secret', 'Google App Password', 'Google Account > Security > 2-Step Verification > App passwords.'],
            'mail.from_name' => ['text', 'Sender name'],
            'mail.host' => ['text', 'SMTP host'],
            'mail.port' => ['number', 'SMTP port'],
        ]],
        'security' => ['label' => 'Security', 'fields' => [
            'security.require_two_factor' => ['toggle', 'Require two-factor sign-in for Super Admins and Admins', 'Each of them sets it up under My account before they can continue.'],
        ]],
    ],

    // Key prefixes that the public site may read.
    'public' => ['company.', 'hours.', 'social.', 'whatsapp.number', 'home.', 'pages.', 'about.'],

    // Stored encrypted and never sent to the browser.
    'secret' => ['mail.password', 'tracking.meta_capi_token'],

];
