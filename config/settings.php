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
            'en' => 'Software development company',
            'bn' => 'সফটওয়্যার ডেভেলপমেন্ট কোম্পানি',
        ],
        'company.slogan' => [
            'en' => 'Don\'t go for a company that makes simple software, go for a company that can make the software simple.',
            'bn' => 'সাধারণ সফটওয়্যার বানায় এমন কোম্পানি নয়, বেছে নিন এমন কোম্পানি যারা সফটওয়্যারকে সহজ করে তুলতে পারে।',
        ],
        'company.description' => [
            'en' => 'Auxtech is a software development company in Dhaka, Bangladesh. We design, build and support custom software, web applications, e-commerce platforms and mobile apps, and help our clients grow them with data-driven digital marketing.',
            'bn' => 'Auxtech ঢাকা, বাংলাদেশের একটি সফটওয়্যার ডেভেলপমেন্ট কোম্পানি। আমরা কাস্টম সফটওয়্যার, ওয়েব অ্যাপ্লিকেশন, ই-কমার্স প্ল্যাটফর্ম ও মোবাইল অ্যাপ ডিজাইন, তৈরি ও সাপোর্ট করি, আর ডেটা-ভিত্তিক ডিজিটাল মার্কেটিংয়ের মাধ্যমে ক্লায়েন্টদের ব্যবসা বড় করতে সাহায্য করি।',
        ],
        'company.url' => 'https://auxtechsoftware.com',
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

        'home.hero_eyebrow' => [
            'en' => 'Software development company · Dhaka, Bangladesh',
            'bn' => 'সফটওয়্যার ডেভেলপমেন্ট কোম্পানি · ঢাকা, বাংলাদেশ',
        ],
        'home.hero_title' => [
            'en' => 'We engineer software that runs real businesses.',
            'bn' => 'আমরা এমন সফটওয়্যার তৈরি করি, যা দিয়ে চলে বাস্তব ব্যবসা।',
        ],
        'home.hero_subtitle' => [
            'en' => 'Auxtech designs, builds and supports custom software, web platforms, e-commerce systems and mobile apps for growing companies, from the first workshop to long after launch.',
            'bn' => 'Auxtech ক্রমবর্ধমান প্রতিষ্ঠানের জন্য কাস্টম সফটওয়্যার, ওয়েব প্ল্যাটফর্ম, ই-কমার্স সিস্টেম ও মোবাইল অ্যাপ ডিজাইন, তৈরি ও সাপোর্ট করে, প্রথম আলোচনা থেকে লঞ্চের অনেক পর পর্যন্ত।',
        ],
        // Background video behind the home page headline. Leave the video empty to show the poster only.
        // Empty: the hero draws its animated scene. Set to /media/hero-loop.mp4 to use the video instead.
        'home.hero_video' => '',
        'home.hero_video_webm' => '/media/hero-loop.webm',
        'home.hero_poster' => '/media/hero-poster.jpg',
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
        'pages.work.title' => ['en' => 'Portfolio: Software, Web Platforms and E-commerce Projects', 'bn' => 'পোর্টফোলিও: সফটওয়্যার, ওয়েব প্ল্যাটফর্ম ও ই-কমার্স প্রজেক্ট'],
        'pages.work.description' => ['en' => 'Case studies from Auxtech: custom software, e-commerce platforms, reseller systems, learning platforms and corporate websites built for companies in Bangladesh.', 'bn' => 'Auxtech-এর কেস স্টাডি: বাংলাদেশের প্রতিষ্ঠানের জন্য তৈরি কাস্টম সফটওয়্যার, ই-কমার্স প্ল্যাটফর্ম, রিসেলার সিস্টেম, লার্নিং প্ল্যাটফর্ম ও কর্পোরেট ওয়েবসাইট।'],
        'pages.services.title' => ['en' => 'Software Development Services in Bangladesh', 'bn' => 'বাংলাদেশে সফটওয়্যার ডেভেলপমেন্ট সার্ভিস'],
        'pages.services.description' => ['en' => 'Custom software, web application, e-commerce and mobile app development, UI/UX design and digital marketing from Auxtech, Dhaka.', 'bn' => 'Auxtech, ঢাকা থেকে কাস্টম সফটওয়্যার, ওয়েব অ্যাপ্লিকেশন, ই-কমার্স ও মোবাইল অ্যাপ ডেভেলপমেন্ট, UI/UX ডিজাইন এবং ডিজিটাল মার্কেটিং।'],
        'pages.solutions.title' => ['en' => 'Software Platforms: E-commerce, Reseller, LMS and More', 'bn' => 'সফটওয়্যার প্ল্যাটফর্ম: ই-কমার্স, রিসেলার, LMS ও আরও'],
        'pages.solutions.description' => ['en' => 'Proven platforms by Auxtech: Auxtech Ecommerce, a B2B reseller and dropshipping platform, a learning management system and Auxify, adapted to each business.', 'bn' => 'Auxtech-এর প্রমাণিত প্ল্যাটফর্ম: Auxtech Ecommerce, B2B রিসেলার ও ড্রপশিপিং প্ল্যাটফর্ম, লার্নিং ম্যানেজমেন্ট সিস্টেম ও Auxify, প্রতিটি ব্যবসার উপযোগী করে।'],
        'pages.pricing.title' => ['en' => 'Pricing: Software and Website Development Cost', 'bn' => 'প্রাইসিং: সফটওয়্যার ও ওয়েবসাইট ডেভেলপমেন্টের খরচ'],
        'pages.pricing.description' => ['en' => 'Packages and engagement options for software, web, e-commerce and app projects at Auxtech. Get a tailored quote on WhatsApp.', 'bn' => 'Auxtech-এ সফটওয়্যার, ওয়েব, ই-কমার্স ও অ্যাপ প্রজেক্টের প্যাকেজ ও কাজের ধরন। হোয়াটসঅ্যাপে আপনার জন্য কোটেশন নিন।'],
        'pages.about.title' => ['en' => 'About Auxtech: Software Development Company in Dhaka', 'bn' => 'Auxtech সম্পর্কে: ঢাকার সফটওয়্যার ডেভেলপমেন্ট কোম্পানি'],
        'pages.about.description' => ['en' => 'Auxtech is a software development company in Dhaka that designs, builds and supports custom software, web platforms, e-commerce systems and mobile apps.', 'bn' => 'Auxtech ঢাকার একটি সফটওয়্যার ডেভেলপমেন্ট কোম্পানি, যা কাস্টম সফটওয়্যার, ওয়েব প্ল্যাটফর্ম, ই-কমার্স সিস্টেম ও মোবাইল অ্যাপ ডিজাইন, তৈরি ও সাপোর্ট করে।'],
        'pages.faq.title' => ['en' => 'Frequently Asked Questions about Working with Auxtech', 'bn' => 'Auxtech-এর সাথে কাজ নিয়ে সাধারণ জিজ্ঞাসা'],
        'pages.faq.description' => ['en' => 'How Auxtech plans, prices, builds and supports software, web, e-commerce and app projects.', 'bn' => 'Auxtech কীভাবে সফটওয়্যার, ওয়েব, ই-কমার্স ও অ্যাপ প্রজেক্টের পরিকল্পনা, মূল্য নির্ধারণ, ডেভেলপমেন্ট ও সাপোর্ট করে।'],
        'pages.contact.title' => ['en' => 'Contact Auxtech: Start Your Software Project', 'bn' => 'Auxtech-এর সাথে যোগাযোগ: আপনার সফটওয়্যার প্রজেক্ট শুরু করুন'],
        'pages.contact.description' => ['en' => 'Talk to Auxtech on WhatsApp or email about your software, web or app project. Office: West Shewrapara, Begum Rokeya Avenue, Dhaka.', 'bn' => 'আপনার সফটওয়্যার, ওয়েব বা অ্যাপ প্রজেক্ট নিয়ে হোয়াটসঅ্যাপ বা ইমেইলে Auxtech-এর সাথে কথা বলুন। অফিস: পশ্চিম শেওড়াপাড়া, বেগম রোকেয়া এভিনিউ, ঢাকা।'],
        'pages.estimate.title' => ['en' => 'Get a Software Project Estimate', 'bn' => 'সফটওয়্যার প্রজেক্টের খরচের হিসাব নিন'],
        'pages.estimate.description' => ['en' => 'Answer four short questions about your software, website, app or marketing project and receive an estimate on WhatsApp.', 'bn' => 'আপনার সফটওয়্যার, ওয়েবসাইট, অ্যাপ বা মার্কেটিং প্রজেক্ট নিয়ে চারটি ছোট প্রশ্নের উত্তর দিন, হোয়াটসঅ্যাপে খরচের হিসাব পেয়ে যান।'],
        'pages.blog.title' => ['en' => 'Insights: Software, Web Development and Digital Growth', 'bn' => 'ইনসাইটস: সফটওয়্যার, ওয়েব ডেভেলপমেন্ট ও ডিজিটাল গ্রোথ'],
        'pages.blog.description' => ['en' => 'Articles from the Auxtech team on building software, web platforms and online businesses in Bangladesh.', 'bn' => 'বাংলাদেশে সফটওয়্যার, ওয়েব প্ল্যাটফর্ম ও অনলাইন ব্যবসা গড়ে তোলা নিয়ে Auxtech টিমের লেখা।'],

        // Heading and intro line of each home page section.
        'home.copy.clients' => ['title' => ['en' => 'Trusted by businesses across *Bangladesh*', 'bn' => 'বাংলাদেশজুড়ে প্রতিষ্ঠানগুলোর আস্থা'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.stats' => ['title' => ['en' => 'Auxtech in *numbers*', 'bn' => 'সংখ্যায় Auxtech'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.services' => ['title' => ['en' => 'Engineering, design and growth *under one roof*', 'bn' => 'ইঞ্জিনিয়ারিং, ডিজাইন ও গ্রোথ, এক ছাদের নিচে'], 'lead' => ['en' => 'From the first workshop to long-term support, one accountable team plans, builds and runs your software.', 'bn' => 'প্রথম আলোচনা থেকে দীর্ঘমেয়াদি সাপোর্ট পর্যন্ত, একটি দায়বদ্ধ টিম আপনার সফটওয়্যারের পরিকল্পনা, ডেভেলপমেন্ট ও পরিচালনা করে।']],
        'home.copy.flagship' => ['title' => ['en' => 'One platform for selling, fulfilment *and growth*', 'bn' => 'বিক্রি, ডেলিভারি ও গ্রোথের জন্য একটি প্ল্যাটফর্ম'], 'lead' => ['en' => 'Our in-house commerce platform brings the storefront, orders, inventory, point of sale, accounting, couriers and Meta tracking into a single admin panel.', 'bn' => 'আমাদের নিজস্ব কমার্স প্ল্যাটফর্মে স্টোরফ্রন্ট, অর্ডার, ইনভেন্টরি, পয়েন্ট অব সেল, হিসাব, কুরিয়ার ও মেটা ট্র্যাকিং, সব এক অ্যাডমিন প্যানেলে।']],
        'home.copy.work' => ['title' => ['en' => 'Selected *work*', 'bn' => 'আমাদের নির্বাচিত কাজ'], 'lead' => ['en' => 'Platforms and products we have designed, engineered and launched for our clients.', 'bn' => 'ক্লায়েন্টদের জন্য আমাদের ডিজাইন, ডেভেলপ ও লঞ্চ করা প্ল্যাটফর্ম ও প্রোডাক্ট।']],
        'home.copy.solutions' => ['title' => ['en' => 'Platforms we *build on*', 'bn' => 'যে প্ল্যাটফর্মগুলোর ওপর আমরা কাজ করি'], 'lead' => ['en' => 'Proven foundations we adapt to each client, so projects start faster, cost less and are easier to maintain.', 'bn' => 'প্রমাণিত ভিত্তি, যা প্রতিটি ক্লায়েন্টের প্রয়োজনে সাজিয়ে নিই, ফলে প্রজেক্ট দ্রুত শুরু হয়, খরচ কমে এবং রক্ষণাবেক্ষণ সহজ হয়।']],
        'home.copy.process' => ['title' => ['en' => 'How we *deliver*', 'bn' => 'আমরা যেভাবে কাজ করি'], 'lead' => ['en' => 'A clear, five-stage process with working software you can review at every step.', 'bn' => 'পাঁচ ধাপের পরিষ্কার প্রক্রিয়া, প্রতিটি ধাপে চলমান সফটওয়্যার আপনি নিজে দেখে নিতে পারেন।']],
        'home.copy.testimonials' => ['title' => ['en' => 'What our clients *say*', 'bn' => 'ক্লায়েন্টরা যা বলেন'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.reasons' => ['title' => ['en' => 'Why companies choose *Auxtech*', 'bn' => 'কেন প্রতিষ্ঠানগুলো Auxtech বেছে নেয়'], 'lead' => ['en' => 'Product thinking, solid engineering and support that continues after launch.', 'bn' => 'প্রোডাক্ট ভাবনা, মজবুত ইঞ্জিনিয়ারিং এবং লঞ্চের পরেও চলমান সাপোর্ট।']],
        'home.copy.pricing' => ['title' => ['en' => 'Engagement *and pricing*', 'bn' => 'কাজের ধরন ও মূল্য'], 'lead' => ['en' => 'Fixed-scope projects, platform packages and ongoing support. Get a tailored quote on WhatsApp.', 'bn' => 'নির্দিষ্ট স্কোপের প্রজেক্ট, প্ল্যাটফর্ম প্যাকেজ ও চলমান সাপোর্ট। হোয়াটসঅ্যাপে আপনার জন্য কোটেশন নিন।']],
        'home.copy.posts' => ['title' => ['en' => 'Insights from *our team*', 'bn' => 'আমাদের টিমের লেখা'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.faq' => ['title' => ['en' => 'Frequently asked *questions*', 'bn' => 'সাধারণ জিজ্ঞাসা'], 'lead' => ['en' => null, 'bn' => null]],
        'home.copy.cta' => ['title' => ['en' => 'Have a project *in mind?*', 'bn' => 'মাথায় কোনো প্রজেক্ট আছে?'], 'lead' => ['en' => 'Tell us what you want to build. We reply on WhatsApp, usually within minutes, and follow up with a clear plan and estimate.', 'bn' => 'আপনি কী তৈরি করতে চান, জানান। আমরা সাধারণত কয়েক মিনিটেই হোয়াটসঅ্যাপে উত্তর দিই, তারপর পাঠাই একটি পরিষ্কার পরিকল্পনা ও খরচের হিসাব।']],

        // On-page heading (H1) and intro line of each listing page.
        'pages.work.heading' => ['en' => 'Our work', 'bn' => 'আমাদের কাজ'],
        'pages.work.lead' => ['en' => 'Custom software, e-commerce platforms, reseller systems, learning platforms and corporate websites, designed, engineered and launched by Auxtech.', 'bn' => 'কাস্টম সফটওয়্যার, ই-কমার্স প্ল্যাটফর্ম, রিসেলার সিস্টেম, লার্নিং প্ল্যাটফর্ম ও কর্পোরেট ওয়েবসাইট, Auxtech-এর ডিজাইন, ডেভেলপ ও লঞ্চ করা।'],
        'pages.services.heading' => ['en' => 'Services', 'bn' => 'সার্ভিস'],
        'pages.services.lead' => ['en' => 'Software engineering, product design and digital growth, delivered by one team that stays accountable from discovery to support.', 'bn' => 'সফটওয়্যার ইঞ্জিনিয়ারিং, প্রোডাক্ট ডিজাইন ও ডিজিটাল গ্রোথ, এমন এক টিমের হাতে যারা শুরু থেকে সাপোর্ট পর্যন্ত দায়বদ্ধ।'],
        'pages.solutions.heading' => ['en' => 'Platforms', 'bn' => 'প্ল্যাটফর্ম'],
        'pages.solutions.lead' => ['en' => 'Proven platforms we have built and refined across many projects, ready to adapt to your business, and custom software when you need something new.', 'bn' => 'বহু প্রজেক্টে তৈরি ও পরিমার্জিত প্রমাণিত প্ল্যাটফর্ম, আপনার ব্যবসার মতো করে সাজানোর জন্য প্রস্তুত। নতুন কিছু দরকার হলে কাস্টম সফটওয়্যার।'],
        'pages.pricing.heading' => ['en' => 'Pricing', 'bn' => 'প্রাইসিং'],
        'pages.pricing.lead' => ['en' => 'Every project is scoped to your needs. Start with a package or ask for a tailored quote on WhatsApp.', 'bn' => 'প্রতিটি প্রজেক্ট আপনার প্রয়োজন অনুযায়ী নির্ধারিত। একটি প্যাকেজ দিয়ে শুরু করুন অথবা হোয়াটসঅ্যাপে আপনার জন্য কোটেশন নিন।'],
        'pages.about.heading' => ['en' => 'About Auxtech', 'bn' => 'Auxtech সম্পর্কে'],
        'pages.about.lead' => ['en' => 'A software development company in Dhaka that turns business processes into reliable, easy-to-use software.', 'bn' => 'ঢাকার একটি সফটওয়্যার ডেভেলপমেন্ট কোম্পানি, যারা ব্যবসার প্রক্রিয়াকে নির্ভরযোগ্য ও সহজে ব্যবহারযোগ্য সফটওয়্যারে রূপ দেয়।'],
        'pages.faq.heading' => ['en' => 'Frequently asked questions', 'bn' => 'সাধারণ জিজ্ঞাসা'],
        'pages.faq.lead' => ['en' => 'How we plan, price, build and support your project.', 'bn' => 'আমরা কীভাবে আপনার প্রজেক্টের পরিকল্পনা, মূল্য নির্ধারণ, ডেভেলপমেন্ট ও সাপোর্ট করি।'],
        'pages.contact.heading' => ['en' => 'Start a project', 'bn' => 'প্রজেক্ট শুরু করুন'],
        'pages.contact.lead' => ['en' => 'WhatsApp is the fastest way to reach our team. You can also email us or visit our office in Dhaka.', 'bn' => 'আমাদের টিমের কাছে পৌঁছানোর সবচেয়ে দ্রুত উপায় হোয়াটসঅ্যাপ। ইমেইল করতে পারেন বা ঢাকায় আমাদের অফিসেও আসতে পারেন।'],
        'pages.estimate.heading' => ['en' => 'Get an estimate', 'bn' => 'খরচের হিসাব নিন'],
        'pages.estimate.lead' => ['en' => 'Four short questions about your project. We send the estimate on WhatsApp.', 'bn' => 'আপনার প্রজেক্ট নিয়ে চারটি ছোট প্রশ্ন। খরচের হিসাব আমরা হোয়াটসঅ্যাপে পাঠাব।'],
        'pages.blog.heading' => ['en' => 'Insights', 'bn' => 'ইনসাইটস'],
        'pages.blog.lead' => ['en' => 'Notes from our team on building software and growing online businesses.', 'bn' => 'সফটওয়্যার তৈরি ও অনলাইন ব্যবসা বড় করা নিয়ে আমাদের টিমের লেখা।'],

        'home.process_steps' => [
            ['title' => ['en' => 'Discovery', 'bn' => 'প্রয়োজন বোঝা'], 'description' => ['en' => 'We study your business, users and goals, then agree on scope, priorities and how success will be measured.', 'bn' => 'আপনার ব্যবসা, ব্যবহারকারী ও লক্ষ্য বুঝে নিই, তারপর স্কোপ, অগ্রাধিকার ও সাফল্যের মাপকাঠি ঠিক করি।']],
            ['title' => ['en' => 'Design', 'bn' => 'ডিজাইন'], 'description' => ['en' => 'We map user journeys, design the interface and plan the architecture before a line of code is written.', 'bn' => 'কোড লেখার আগেই ইউজার জার্নি সাজাই, ইন্টারফেস ডিজাইন করি এবং আর্কিটেকচারের পরিকল্পনা করি।']],
            ['title' => ['en' => 'Engineering', 'bn' => 'ডেভেলপমেন্ট'], 'description' => ['en' => 'We build in short iterations with code review and testing, and give you a staging site to try as it grows.', 'bn' => 'ছোট ছোট ধাপে কোড রিভিউ ও টেস্টিংসহ তৈরি করি, আর একটি স্টেজিং সাইট দিই যেখানে আপনি অগ্রগতি দেখে নিতে পারেন।']],
            ['title' => ['en' => 'Launch', 'bn' => 'লঞ্চ'], 'description' => ['en' => 'We deploy, move your data, train your team and watch the release closely in its first weeks.', 'bn' => 'ডিপ্লয় করি, আপনার ডেটা স্থানান্তর করি, টিমকে ট্রেনিং দিই এবং প্রথম কয়েক সপ্তাহ গভীরভাবে পর্যবেক্ষণ করি।']],
            ['title' => ['en' => 'Support and growth', 'bn' => 'সাপোর্ট ও গ্রোথ'], 'description' => ['en' => 'We maintain, secure and improve the software, and help you grow it with marketing and analytics.', 'bn' => 'সফটওয়্যার রক্ষণাবেক্ষণ, সুরক্ষা ও উন্নয়ন করি, আর মার্কেটিং ও অ্যানালিটিক্স দিয়ে তা বড় করতে সাহায্য করি।']],
        ],
        'home.reasons' => [
            ['icon' => 'lightbulb', 'title' => ['en' => 'Product thinking, not just code', 'bn' => 'শুধু কোড নয়, প্রোডাক্ট ভাবনা'], 'description' => ['en' => 'We start from your business goals and design software around how your team really works.', 'bn' => 'আপনার ব্যবসার লক্ষ্য থেকে শুরু করি এবং আপনার টিম আসলে যেভাবে কাজ করে, সেভাবে সফটওয়্যার ডিজাইন করি।']],
            ['icon' => 'shield-check', 'title' => ['en' => 'Engineering you can rely on', 'bn' => 'নির্ভরযোগ্য ইঞ্জিনিয়ারিং'], 'description' => ['en' => 'Clean architecture, code review and automated tests keep your software stable as it grows.', 'bn' => 'পরিচ্ছন্ন আর্কিটেকচার, কোড রিভিউ ও অটোমেটেড টেস্ট আপনার সফটওয়্যারকে বড় হওয়ার সাথে সাথেও স্থিতিশীল রাখে।']],
            ['icon' => 'map-pin', 'title' => ['en' => 'Built for the local market', 'bn' => 'স্থানীয় বাজারের জন্য তৈরি'], 'description' => ['en' => 'Bangla and English interfaces, cash on delivery, local couriers and payment gateways, handled from day one.', 'bn' => 'বাংলা ও ইংরেজি ইন্টারফেস, ক্যাশ অন ডেলিভারি, স্থানীয় কুরিয়ার ও পেমেন্ট গেটওয়ে, প্রথম দিন থেকেই।']],
            ['icon' => 'layers', 'title' => ['en' => 'Proven platforms', 'bn' => 'প্রমাণিত প্ল্যাটফর্ম'], 'description' => ['en' => 'Our own commerce, reseller and learning platforms let us deliver faster and with fewer surprises.', 'bn' => 'আমাদের নিজস্ব কমার্স, রিসেলার ও লার্নিং প্ল্যাটফর্মের কারণে দ্রুত ও কম ঝুঁকিতে ডেলিভারি দিতে পারি।']],
            ['icon' => 'layout-dashboard', 'title' => ['en' => 'Transparent delivery', 'bn' => 'স্বচ্ছ ডেলিভারি'], 'description' => ['en' => 'A staging site, regular demos and one point of contact, so you always know where the project stands.', 'bn' => 'স্টেজিং সাইট, নিয়মিত ডেমো ও একজন নির্দিষ্ট যোগাযোগকারী, ফলে প্রজেক্টের অবস্থা সবসময় আপনার জানা থাকে।']],
            ['icon' => 'headphones', 'title' => ['en' => 'Support after launch', 'bn' => 'লঞ্চের পরেও সাপোর্ট'], 'description' => ['en' => 'Maintenance, security updates, new features and growth marketing from the team that built your software.', 'bn' => 'রক্ষণাবেক্ষণ, সিকিউরিটি আপডেট, নতুন ফিচার ও গ্রোথ মার্কেটিং, সেই টিমের কাছ থেকে যারা আপনার সফটওয়্যার তৈরি করেছে।']],
        ],
        'about.story' => [
            'en' => "Auxtech is a software development company based in Dhaka, Bangladesh. We help businesses turn the way they work into reliable, easy-to-use software: platforms that take orders, manage stock and deliveries, run training programmes, connect resellers or serve citizens.\n\nOur work spans custom software, web applications, e-commerce platforms and mobile apps for fashion and lifestyle brands, electronics retailers, grocery and food businesses, reseller networks, educational institutions, industrial companies and civic services. Alongside client projects we build and maintain our own platforms, Auxtech Ecommerce, a B2B reseller and dropshipping platform, and Auxify, which let us deliver proven foundations faster.\n\nWe believe good software should feel simple. That is why we start with discovery and design, engineer in short reviewed iterations, and stay with our clients after launch to maintain, improve and grow what we have built together.",
            'bn' => "Auxtech ঢাকা, বাংলাদেশের একটি সফটওয়্যার ডেভেলপমেন্ট কোম্পানি। আমরা প্রতিষ্ঠানের কাজের ধরনকে নির্ভরযোগ্য ও সহজে ব্যবহারযোগ্য সফটওয়্যারে রূপ দিই: এমন প্ল্যাটফর্ম যা অর্ডার নেয়, স্টক ও ডেলিভারি সামলায়, প্রশিক্ষণ কার্যক্রম চালায়, রিসেলারদের যুক্ত করে বা নাগরিকদের সেবা দেয়।\n\nফ্যাশন ও লাইফস্টাইল ব্র্যান্ড, ইলেকট্রনিক্স রিটেইলার, গ্রোসারি ও খাদ্য ব্যবসা, রিসেলার নেটওয়ার্ক, শিক্ষা প্রতিষ্ঠান, শিল্পপ্রতিষ্ঠান ও নাগরিক সেবার জন্য আমরা কাস্টম সফটওয়্যার, ওয়েব অ্যাপ্লিকেশন, ই-কমার্স প্ল্যাটফর্ম ও মোবাইল অ্যাপ তৈরি করেছি। ক্লায়েন্ট প্রজেক্টের পাশাপাশি আমরা নিজস্ব প্ল্যাটফর্ম, Auxtech Ecommerce, B2B রিসেলার ও ড্রপশিপিং প্ল্যাটফর্ম এবং Auxify, তৈরি ও রক্ষণাবেক্ষণ করি, যা আমাদের দ্রুত ও প্রমাণিত ভিত্তিতে কাজ ডেলিভারি দিতে সাহায্য করে।\n\nআমরা বিশ্বাস করি ভালো সফটওয়্যার ব্যবহারে সহজ হওয়া উচিত। তাই আমরা শুরু করি প্রয়োজন বোঝা ও ডিজাইন দিয়ে, ছোট ছোট রিভিউ করা ধাপে ডেভেলপ করি, এবং লঞ্চের পরেও ক্লায়েন্টের পাশে থাকি, একসাথে তৈরি করা সফটওয়্যারকে রক্ষণাবেক্ষণ, উন্নয়ন ও বড় করতে।",
        ],
        'company.map_url' => null,
        'security.require_two_factor' => false,

        'seo.title_suffix' => 'Auxtech',
        'seo.default_title' => [
            'en' => 'Software Development Company in Bangladesh | Auxtech',
            'bn' => 'বাংলাদেশের সফটওয়্যার ডেভেলপমেন্ট কোম্পানি | Auxtech',
        ],
        'seo.default_description' => [
            'en' => 'Auxtech builds custom software, web applications, e-commerce platforms and mobile apps for businesses in Bangladesh. Talk to our team on WhatsApp.',
            'bn' => 'Auxtech বাংলাদেশের প্রতিষ্ঠানের জন্য কাস্টম সফটওয়্যার, ওয়েব অ্যাপ্লিকেশন, ই-কমার্স প্ল্যাটফর্ম ও মোবাইল অ্যাপ তৈরি করে। হোয়াটসঅ্যাপে কথা বলুন।',
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
            'home.hero_eyebrow' => ['text', 'Small line above the headline'],
            'home.hero_title' => ['textarea', 'Headline (H1)', 'Wrap words in *stars* to show them in yellow, for example *We engineer* software. Without stars the first two words are yellow.'],
            'home.hero_subtitle' => ['textarea', 'Sub-headline'],
            'home.hero_video' => ['text', 'Background video (MP4, optional)', 'Leave empty to show the animated light-trail scene with your featured projects on the devices (recommended). To use a video instead, enter a path or link to an MP4, for example /media/hero-loop.mp4, which ships with the site.'],
            'home.hero_video_webm' => ['text', 'Background video (WebM, optional)', 'A smaller WebM copy of the same video, used by browsers that support it.'],
            'home.hero_poster' => ['text', 'Video still image', 'Only used with a background video: shown while it loads, on phones and when visitors prefer reduced motion.'],
            'home.sections' => ['sections', 'Sections, in order', 'Drag to reorder. Sections with no content (for example testimonials) hide themselves.'],
            'home.copy.clients' => ['copy', 'Client strip'],
            'home.copy.stats' => ['copy', 'Numbers'],
            'home.copy.services' => ['copy', 'Services'],
            'home.copy.flagship' => ['copy', 'Flagship product (Auxtech Ecommerce)'],
            'home.copy.work' => ['copy', 'Featured work'],
            'home.copy.solutions' => ['copy', 'Platforms'],
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
