<?php

/*
| Portfolio content seeded by PortfolioSeeder: every project Auxtech has built.
| Facts come from the live sites (checked 18 Nov 2026); technologies were read
| from each site's own headers and assets. No client results or numbers are
| claimed. Images are not seeded: upload them in the admin panel from the
| image pack (see the guide in the pack).
*/

return [
    [
        'slug' => 'paperlesseducation',
        'client' => 'Paperless Education',
        'url' => 'https://paperlesseducation.org',
        'industry' => 'education',
        'solution' => 'lms',
        'type' => 'live',
        'featured' => true,
        'services' => ['custom-software-development', 'web-application-development', 'ui-ux-design'],
        'technologies' => ['react', 'nextjs'],
        'modules' => [],
        'tagline' => [
            'en' => 'A digital exam-preparation library where every book works as a question bank.',
            'bn' => 'একটি ডিজিটাল পরীক্ষা-প্রস্তুতির লাইব্রেরি, যেখানে প্রতিটি বই একটি প্রশ্নব্যাংক।',
        ],
        'summary' => [
            'en' => 'Paperless Education helps candidates prepare for BCS, bank, NTRCA, primary teacher and admission exams with interactive PDF books, courses, model tests and a question bank, all in Bangla.',
            'bn' => 'Paperless Education বিসিএস, ব্যাংক, এনটিআরসিএ, প্রাথমিক শিক্ষক ও ভর্তি পরীক্ষার প্রার্থীদের জন্য ইন্টারঅ্যাক্টিভ পিডিএফ বই, কোর্স, মডেল টেস্ট ও প্রশ্নব্যাংক নিয়ে একটি সম্পূর্ণ বাংলা প্ল্যাটফর্ম।',
        ],
        'challenge' => [
            'en' => '<p>Exam candidates in Bangladesh juggle printed guides, scattered PDFs and separate test apps. Paperless Education wanted one place where a book, its practice questions and the learner\'s progress live together, and where new books, courses and exam packages can be published without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We designed and built a fast, mobile-first learning platform in Bangla:</p><ul><li>A catalogue of digital books organised by exam: BCS preliminary, bank jobs, NTRCA teacher registration, primary assistant teacher, 11th to 20th grade and admission.</li><li>Books that double as question banks, with chapter-wise model tests and notes on where a learner got stuck.</li><li>Courses, timed exams and a searchable question bank.</li><li>Subscription packages and a free trial, with secure learner accounts.</li><li>A blog and content pages for search visibility.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Learners get a single, Bangla-first study space that works on any phone, and the Paperless Education team manages books, courses, exams and packages from its own admin panel.</p>',
        ],
    ],
    [
        'slug' => 'cleanbdx',
        'client' => 'CleanBDX',
        'url' => 'https://cleanbdx.com',
        'industry' => 'environment',
        'solution' => 'custom-software',
        'type' => 'live',
        'featured' => true,
        'services' => ['custom-software-development', 'web-application-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [],
        'tagline' => [
            'en' => 'A behaviour-driven digital waste management platform for Bangladesh.',
            'bn' => 'বাংলাদেশের জন্য আচরণভিত্তিক ডিজিটাল বর্জ্য ব্যবস্থাপনা প্ল্যাটফর্ম।',
        ],
        'summary' => [
            'en' => 'CleanBDX connects citizens, waste sellers and collection riders so household waste is tracked, collected and recycled transparently, with incentives that reward responsible behaviour.',
            'bn' => 'CleanBDX নাগরিক, বর্জ্য বিক্রেতা ও সংগ্রহকারী রাইডারদের যুক্ত করে, যাতে গৃহস্থালি বর্জ্য স্বচ্ছভাবে ট্র্যাক, সংগ্রহ ও রিসাইকেল হয় এবং দায়িত্বশীল আচরণে প্রণোদনা মেলে।',
        ],
        'challenge' => [
            'en' => '<p>Waste management in Bangladesh involves many parties and little shared data. CleanBDX needed a platform that brings citizens, sellers and riders into one verified ecosystem and shows the environmental impact of every collection in real time.</p>',
        ],
        'approach' => [
            'en' => '<p>We engineered a multi-role web platform:</p><ul><li>Separate journeys and accounts for citizens, waste sellers and riders.</li><li>Collection requests, verification and transparent tracking of recovered waste.</li><li>An incentive model that rewards recycling behaviour.</li><li>A live impact tracker showing waste collected and carbon emissions saved.</li><li>Public pages for the problem, solutions, impact and media coverage.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>CleanBDX runs its recycling programme on a single platform, and anyone can follow its impact live on the website.</p>',
        ],
    ],
    [
        'slug' => 'e7training',
        'client' => 'E7 Training',
        'url' => 'https://e7training.com',
        'industry' => 'education',
        'solution' => 'lms',
        'type' => 'live',
        'featured' => true,
        'services' => ['web-application-development', 'custom-software-development'],
        'technologies' => ['vuejs', 'nuxt'],
        'modules' => [],
        'tagline' => [
            'en' => 'An online learning platform with separate student and trainer portals.',
            'bn' => 'আলাদা স্টুডেন্ট ও ট্রেইনার পোর্টালসহ একটি অনলাইন লার্নিং প্ল্যাটফর্ম।',
        ],
        'summary' => [
            'en' => 'E7 Learning sells and delivers online courses across categories and skill levels, with trainer-led content, student accounts and certificates on completion.',
            'bn' => 'E7 Learning বিভিন্ন ক্যাটাগরি ও লেভেলের অনলাইন কোর্স বিক্রি ও পরিচালনা করে, যেখানে আছে ট্রেইনারদের কনটেন্ট, স্টুডেন্ট অ্যাকাউন্ট ও কোর্স শেষে সার্টিফিকেট।',
        ],
        'challenge' => [
            'en' => '<p>E7 Training wanted to move its training programmes online and let trainers publish and manage their own courses, while students browse, enrol and learn in one place.</p>',
        ],
        'approach' => [
            'en' => '<p>We built the platform on our learning management system:</p><ul><li>A course catalogue with search and category browsing.</li><li>Student login for enrolment, lessons and progress.</li><li>A trainer portal for creating and managing courses.</li><li>Certificates issued on completion.</li><li>Policy pages for terms, refunds, privacy and delivery.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>E7 Training runs paid online courses with trainers and students each working in their own portal.</p>',
        ],
    ],
    [
        'slug' => 'wtbl',
        'client' => 'Water Technology BD Limited',
        'url' => 'https://wtbl.com.bd',
        'industry' => 'corporate',
        'solution' => 'corporate-website',
        'type' => 'live',
        'featured' => true,
        'services' => ['web-application-development', 'ui-ux-design', 'seo'],
        'technologies' => ['react', 'nextjs'],
        'modules' => [],
        'tagline' => [
            'en' => 'A corporate website for an industrial water and wastewater treatment company.',
            'bn' => 'একটি শিল্পভিত্তিক পানি ও বর্জ্যপানি শোধন প্রতিষ্ঠানের কর্পোরেট ওয়েবসাইট।',
        ],
        'summary' => [
            'en' => 'WTBL designs, builds and maintains ETP, STP, RO, MBR and zero liquid discharge systems for industry. Its website presents the divisions, services, partners and trading business to industrial buyers.',
            'bn' => 'WTBL শিল্পকারখানার জন্য ইটিপি, এসটিপি, আরও, এমবিআর ও জিরো লিকুইড ডিসচার্জ সিস্টেম ডিজাইন, নির্মাণ ও রক্ষণাবেক্ষণ করে। ওয়েবসাইটটি শিল্প ক্রেতাদের কাছে তাদের বিভাগ, সার্ভিস, পার্টনার ও ট্রেডিং ব্যবসা তুলে ধরে।',
        ],
        'challenge' => [
            'en' => '<p>Industrial buyers research suppliers carefully before they make contact. WTBL needed a fast, credible website that explains complex treatment technologies clearly and is easy to find in search.</p>',
        ],
        'approach' => [
            'en' => '<p>We designed and built a high-performance corporate site:</p><ul><li>Service pages for effluent treatment (ETP), sewage treatment (STP), reverse osmosis and membrane systems, zero liquid discharge and water treatment plants.</li><li>Sections for divisions, international partner brands and the trading business.</li><li>A consultation request flow for project enquiries.</li><li>Search-friendly structure with descriptive titles and metadata.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>WTBL has a modern, fast website that presents its engineering capability to industrial clients and turns visits into consultation requests.</p>',
        ],
    ],
    [
        'slug' => 'auxify',
        'client' => 'Auxify',
        'url' => 'https://auxify.store',
        'industry' => 'saas',
        'solution' => 'auxify',
        'type' => 'product',
        'featured' => true,
        'services' => ['saas-development', 'ecommerce-development'],
        'technologies' => ['laravel'],
        'modules' => [],
        'tagline' => [
            'en' => 'Auxtech\'s SaaS store builder: an online shop live in minutes.',
            'bn' => 'Auxtech-এর SaaS স্টোর বিল্ডার: কয়েক মিনিটেই অনলাইন শপ লাইভ।',
        ],
        'summary' => [
            'en' => 'Auxify is our own software-as-a-service platform. Businesses open a store with a free subdomain and SSL, choose a ready-made design and manage products, orders, stock, discounts and customers from one dashboard, in Bangla or English.',
            'bn' => 'Auxify আমাদের নিজস্ব SaaS প্ল্যাটফর্ম। ফ্রি সাবডোমেইন ও SSL দিয়ে স্টোর খুলে, রেডিমেড ডিজাইন বেছে নিয়ে বাংলা বা ইংরেজিতে এক ড্যাশবোর্ড থেকে প্রোডাক্ট, অর্ডার, স্টক, ডিসকাউন্ট ও কাস্টমার পরিচালনা করা যায়।',
        ],
        'challenge' => [
            'en' => '<p>Many small businesses need an online store but cannot fund a custom build or manage a server. We wanted to give them a professional store they can launch themselves.</p>',
        ],
        'approach' => [
            'en' => '<p>We designed, built and operate Auxify as a multi-tenant SaaS:</p><ul><li>Self-service sign-up with a free auxify.store address and SSL, or the owner\'s own domain.</li><li>Products and variants, orders and delivery, stock and warehouses.</li><li>Discounts and campaigns, customers and accounts, reports and analytics.</li><li>Payments and checkout, plus a gallery of storefront designs.</li><li>Monthly plans that grow with the store.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Businesses go live without coding or hosting, and upgrade to a custom Auxtech build when they outgrow it.</p>',
        ],
    ],
    [
        'slug' => 'rongtorongob2b',
        'client' => 'Rong Torongo',
        'url' => 'https://rongtorongob2b.com',
        'industry' => 'reselling',
        'solution' => 'reseller-platform',
        'type' => 'live',
        'featured' => true,
        'services' => ['custom-software-development', 'ecommerce-development'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [],
        'tagline' => [
            'en' => 'A dropshipping and reselling platform for women\'s clothing.',
            'bn' => 'নারীদের পোশাকের জন্য একটি ড্রপশিপিং ও রিসেলিং প্ল্যাটফর্ম।',
        ],
        'summary' => [
            'en' => 'Rong Torongo lets resellers anywhere in Bangladesh sell sarees, three-pieces, gowns and kurtis from its catalogue without buying stock, while Rong Torongo handles packaging and cash-on-delivery shipping to all 64 districts.',
            'bn' => 'রঙ তরঙ্গ বাংলাদেশের যেকোনো প্রান্তের রিসেলারদের স্টক না কিনেই শাড়ি, থ্রিপিস, গাউন ও কুর্তি বিক্রির সুযোগ দেয়, আর প্যাকেজিং ও ৬৪ জেলায় ক্যাশ অন ডেলিভারি সামলায় রঙ তরঙ্গ।',
        ],
        'challenge' => [
            'en' => '<p>Running a reseller network on Facebook groups and spreadsheets does not scale. Rong Torongo needed a platform where resellers register, pick products, set their own price and track orders and payouts.</p>',
        ],
        'approach' => [
            'en' => '<p>We delivered it on our B2B reseller platform:</p><ul><li>Reseller registration, packages and a dashboard for orders and earnings.</li><li>A catalogue of sarees, three-pieces, gowns and more, organised by category.</li><li>Orders placed at the reseller\'s own price with white-label parcels.</li><li>Payout tracking for resellers.</li><li>Pages explaining how the programme works.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Rong Torongo manages its reseller network, orders and payouts from one platform instead of chat threads.</p>',
        ],
    ],
    [
        'slug' => 'greenland-tax',
        'client' => 'Greenland Business & Compliance',
        'url' => 'https://tax.greenlandmarketingcompany.com',
        'industry' => 'finance',
        'solution' => 'custom-software',
        'type' => 'live',
        'featured' => false,
        'services' => ['custom-software-development', 'web-application-development'],
        'technologies' => ['laravel'],
        'modules' => [],
        'tagline' => [
            'en' => 'A Bangladesh income tax calculator that generates filing-ready certificates.',
            'bn' => 'বাংলাদেশের আয়কর হিসাবের ক্যালকুলেটর, যা রিটার্ন জমার উপযোগী সার্টিফিকেট তৈরি করে।',
        ],
        'summary' => [
            'en' => 'Greenland Business & Compliance lets taxpayers calculate their income tax for Bangladesh, optimise investment rebates and download a tax certificate for NBR filing in three steps.',
            'bn' => 'Greenland Business & Compliance করদাতাদের তিন ধাপে বাংলাদেশের আয়কর হিসাব, বিনিয়োগ রেয়াত অপটিমাইজ এবং এনবিআর রিটার্নের জন্য ট্যাক্স সার্টিফিকেট ডাউনলোডের সুযোগ দেয়।',
        ],
        'challenge' => [
            'en' => '<p>Calculating income tax, rebates and taxable income by hand is slow and error-prone. Greenland wanted a secure online tool that individuals can use themselves and that produces a professional certificate.</p>',
        ],
        'approach' => [
            'en' => '<p>We built a custom web application:</p><ul><li>A rules-based tax engine for Bangladesh income tax slabs.</li><li>Investment tracking to calculate the tax rebate.</li><li>Instant, downloadable tax certificates.</li><li>Secure user accounts so each taxpayer keeps their own records.</li><li>A simple three-step flow: create an account, enter details, get the certificate.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Taxpayers can work out their tax and download a certificate in minutes, without spreadsheets or manual calculation.</p>',
        ],
    ],
    [
        'slug' => 'gearzen',
        'client' => 'Gearzen',
        'url' => 'https://gearzen.com.bd',
        'industry' => 'gadgets',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => true,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'An online store for smartphones, laptops and smart gadgets.',
            'bn' => 'স্মার্টফোন, ল্যাপটপ ও স্মার্ট গ্যাজেটের অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Gearzen sells phones, MacBooks, tablets and iPads, cameras, earbuds, speakers and drones, with flash deals, product comparison, wishlists and order tracking.',
            'bn' => 'Gearzen ফোন, ম্যাকবুক, ট্যাবলেট ও আইপ্যাড, ক্যামেরা, ইয়ারবাড, স্পিকার ও ড্রোন বিক্রি করে; আছে ফ্ল্যাশ ডিল, প্রোডাক্ট কম্পেয়ার, উইশলিস্ট ও অর্ডার ট্র্যাকিং।',
        ],
        'challenge' => [
            'en' => '<p>Gearzen needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for phones, MacBooks, tablets, iPads, cameras, earbuds, speakers and drones.</li><li>Product variants for storage and memory options.</li><li>Flash deals, product comparison and wishlists.</li><li>Order tracking and a visible hotline.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Gearzen takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'dhakaculture',
        'client' => 'Dhaka Culture',
        'url' => 'https://dhakaculture.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => true,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [
            'product-management',
            'order-management',
            'customer-management',
            'dashboard',
            'pre-order-management',
            'campaign-management',
            'order-tracking',
        ],
        'tagline' => [
            'en' => 'An online store for a streetwear brand built on stories, art and culture.',
            'bn' => 'গল্প, শিল্প ও সংস্কৃতিকে ঘিরে গড়া একটি স্ট্রিটওয়্যার ব্র্যান্ডের অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Dhaka Culture sells semi-drop T-shirts, couple tees, shirts, polos, pants, caps and bandanas, with pre-orders for new drops and a dedicated offers page.',
            'bn' => 'Dhaka Culture সেমি-ড্রপ টি-শার্ট, কাপল টি, শার্ট, পোলো, প্যান্ট, ক্যাপ ও ব্যান্ডানা বিক্রি করে; নতুন ড্রপের জন্য আছে প্রি-অর্ডার ও আলাদা অফার পেজ।',
        ],
        'challenge' => [
            'en' => '<p>Dhaka Culture needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Collections for semi-drop T-shirts, shirts, polos, pants, caps, events and bandanas.</li><li>A top-sellers section on the home page.</li><li>Pre-orders for upcoming drops.</li><li>An offers page and order tracking for customers.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Dhaka Culture takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'seasonalbd',
        'client' => 'Seasonal',
        'url' => 'https://seasonalbd.com',
        'industry' => 'food-grocery',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => true,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'An online store for natural and authentic food sourced from farmers.',
            'bn' => 'কৃষকের কাছ থেকে সংগ্রহ করা প্রাকৃতিক ও খাঁটি খাবারের অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Seasonal sells honey, cold-pressed oil, aromatic rice, spices, ghee and dairy, seasonal fruits, superfoods, nuts and tea, with bilingual product names, an offer zone and a complaint desk.',
            'bn' => 'Seasonal মধু, কোল্ড-প্রেসড তেল, সুগন্ধি চাল, মসলা, ঘি ও দুগ্ধজাত পণ্য, মৌসুমি ফল, সুপারফুড, বাদাম ও চা বিক্রি করে; আছে দ্বিভাষিক প্রোডাক্ট নাম, অফার জোন ও অভিযোগ ডেস্ক।',
        ],
        'challenge' => [
            'en' => '<p>Seasonal needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for honey, cold-pressed oil, rice, spices, ghee and dairy, seasonal fruit, superfood, nuts and tea.</li><li>Product names in Bangla and English.</li><li>An offer zone, order tracking and a complaint desk.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Seasonal takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'asthareseller',
        'client' => 'Astha Reseller',
        'url' => 'https://asthareseller.com',
        'industry' => 'reselling',
        'solution' => 'reseller-platform',
        'type' => 'live',
        'featured' => false,
        'services' => ['custom-software-development', 'ecommerce-development'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [],
        'tagline' => [
            'en' => 'A B2B catalogue and dropshipping network for resellers.',
            'bn' => 'রিসেলারদের জন্য একটি B2B ক্যাটালগ ও ড্রপশিপিং নেটওয়ার্ক।',
        ],
        'summary' => [
            'en' => 'Astha Reseller gives resellers a ready catalogue, product media and white-label shipping, with sourcing, quality checks, packaging and cash on delivery to 64 districts handled centrally.',
            'bn' => 'Astha Reseller রিসেলারদের দেয় রেডি ক্যাটালগ, প্রোডাক্ট মিডিয়া ও হোয়াইট-লেবেল শিপিং; সোর্সিং, কোয়ালিটি চেক, প্যাকেজিং ও ৬৪ জেলায় ক্যাশ অন ডেলিভারি কেন্দ্রীয়ভাবে সামলানো হয়।',
        ],
        'challenge' => [
            'en' => '<p>Astha wanted to let anyone start an online business with no stock risk, while keeping control of product quality, delivery and reseller payouts.</p>',
        ],
        'approach' => [
            'en' => '<p>We set up our reseller platform for Astha:</p><ul><li>A four-step onboarding: register, share products, submit orders, collect profit.</li><li>A catalogue with high-quality product media resellers can reuse.</li><li>White-label parcels and cash memos carrying the reseller\'s own brand.</li><li>Flexible payouts and dedicated merchant support.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Resellers start selling the same day they register, and Astha runs sourcing, fulfilment and payouts from one admin panel.</p>',
        ],
    ],
    [
        'slug' => 'ehubdropshop',
        'client' => 'E Hub Dropshop',
        'url' => 'https://ehubdropshop.com',
        'industry' => 'reselling',
        'solution' => 'reseller-platform',
        'type' => 'live',
        'featured' => false,
        'services' => ['custom-software-development', 'ecommerce-development'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [],
        'tagline' => [
            'en' => 'A zero-capital dropshipping platform for new entrepreneurs.',
            'bn' => 'নতুন উদ্যোক্তাদের জন্য বিনা পুঁজির ড্রপশিপিং প্ল্যাটফর্ম।',
        ],
        'summary' => [
            'en' => 'E Hub lets entrepreneurs start selling with no investment: E Hub handles stocking, packaging and delivery, and resellers focus on sales.',
            'bn' => 'E Hub উদ্যোক্তাদের কোনো বিনিয়োগ ছাড়াই বিক্রি শুরুর সুযোগ দেয়: স্টক, প্যাকেজিং ও ডেলিভারি সামলায় E Hub, রিসেলাররা মনোযোগ দেন বিক্রিতে।',
        ],
        'challenge' => [
            'en' => '<p>New sellers worry about capital, delivery, payment and returns. E Hub needed a platform that answers those questions clearly and runs the whole operation behind the scenes.</p>',
        ],
        'approach' => [
            'en' => '<p>We delivered E Hub on our reseller platform:</p><ul><li>Reseller registration and a product catalogue by category.</li><li>Order submission with delivery handled by E Hub.</li><li>Payment and profit tracking for resellers.</li><li>An FAQ covering capital, delivery, payouts, branding and returns.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>E Hub onboards new resellers online and manages their orders and earnings in one system.</p>',
        ],
    ],
    [
        'slug' => 'sstyping',
        'client' => 'SS Typing & Travels',
        'url' => 'https://sstyping.com',
        'industry' => 'travel',
        'solution' => 'corporate-website',
        'type' => 'live',
        'featured' => false,
        'services' => ['web-application-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [],
        'tagline' => [
            'en' => 'A website for a UAE-based Hajj, Umrah and typing services company.',
            'bn' => 'আরব আমিরাতভিত্তিক হজ, ওমরাহ ও টাইপিং সার্ভিস প্রতিষ্ঠানের ওয়েবসাইট।',
        ],
        'summary' => [
            'en' => 'SS Group Typing & Travels offers Hajj and Umrah packages and professional typing services such as visa applications and government paperwork in the UAE. The website presents both lines of business.',
            'bn' => 'SS Group Typing & Travels আরব আমিরাতে হজ ও ওমরাহ প্যাকেজ এবং ভিসা আবেদন ও সরকারি কাগজপত্রের মতো পেশাদার টাইপিং সার্ভিস দেয়। ওয়েবসাইটটি দুই ধরনের সেবাই তুলে ধরে।',
        ],
        'challenge' => [
            'en' => '<p>The company serves two very different audiences, pilgrims and people who need documents processed, and needed a site that speaks clearly to both.</p>',
        ],
        'approach' => [
            'en' => '<p>We designed and built a focused business website:</p><ul><li>A Hajj and Umrah section with package information and service highlights.</li><li>A typing services section for visa applications, document processing and government paperwork.</li><li>Clear calls to action for both services.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Customers in the UAE find the right service quickly and contact the company directly from the site.</p>',
        ],
    ],
    [
        'slug' => 'fablixbd',
        'client' => 'Fablix',
        'url' => 'https://fablixbd.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design', 'meta-pixel-capi'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking', 'meta-pixel-gtm'],
        'tagline' => [
            'en' => 'An online store for a men\'s fashion brand.',
            'bn' => 'একটি পুরুষদের ফ্যাশন ব্র্যান্ডের অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Fablix sells hoodies, Henley T-shirts, combo sets, stripe and Oxford shirts, formal shirts and baggy jeans, with bundle offers and Meta Pixel tracking for its ads.',
            'bn' => 'Fablix হুডি, হেনলি টি-শার্ট, কম্বো সেট, স্ট্রাইপ ও অক্সফোর্ড শার্ট, ফর্মাল শার্ট ও ব্যাগি জিন্স বিক্রি করে; আছে বান্ডেল অফার ও বিজ্ঞাপনের জন্য মেটা পিক্সেল ট্র্যাকিং।',
        ],
        'challenge' => [
            'en' => '<p>Fablix needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Popular categories for hoodies, Henley tees, combo sets, shirts and baggy jeans.</li><li>Combo sets priced as bundles.</li><li>Meta Pixel installed so ad campaigns can optimise on real purchases.</li><li>Order tracking for customers.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Fablix takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'ventirebd',
        'client' => 'Ventire',
        'url' => 'https://ventirebd.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'An online store for men\'s casual wear.',
            'bn' => 'পুরুষদের ক্যাজুয়াল পোশাকের অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Ventire sells hoodies, casual combo sets, casual and Oxford shirts, oversize shirts and bottom wear, with an offer zone and order tracking.',
            'bn' => 'Ventire হুডি, ক্যাজুয়াল কম্বো সেট, ক্যাজুয়াল ও অক্সফোর্ড শার্ট, ওভারসাইজ শার্ট ও বটম ওয়্যার বিক্রি করে; আছে অফার জোন ও অর্ডার ট্র্যাকিং।',
        ],
        'challenge' => [
            'en' => '<p>Ventire needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for hoodies, combo sets, casual, Oxford and oversize shirts and bottom wear.</li><li>A most-popular section on the home page.</li><li>An offer zone for discounted items.</li><li>Customer sign-in and order tracking.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Ventire takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'signaturemartbd',
        'client' => 'Signature Mart BD',
        'url' => 'https://signaturemartbd.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management'],
        'tagline' => [
            'en' => 'An online store for men\'s winter wear, polos and panjabis.',
            'bn' => 'পুরুষদের শীতের পোশাক, পোলো ও পাঞ্জাবির অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Signature Mart BD sells a winter collection, polo T-shirts and premium panjabis, and runs store-wide campaigns such as its anniversary discount.',
            'bn' => 'Signature Mart BD শীতের কালেকশন, পোলো টি-শার্ট ও প্রিমিয়াম পাঞ্জাবি বিক্রি করে এবং বার্ষিকী ডিসকাউন্টের মতো স্টোরজুড়ে ক্যাম্পেইন চালায়।',
        ],
        'challenge' => [
            'en' => '<p>Signature Mart BD needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Collections for winter wear, polo T-shirts and premium panjabis.</li><li>Product variants for colours and sizes.</li><li>Store-wide campaigns with announcement bars.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Signature Mart BD takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'queensclubbd',
        'client' => 'Queens Club BD',
        'url' => 'https://queensclubbd.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['vuejs', 'nuxt'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'order-tracking'],
        'tagline' => [
            'en' => 'A fashion store for women, teenagers and kids, with wholesale ordering.',
            'bn' => 'নারী, টিনএজার ও শিশুদের ফ্যাশন স্টোর, পাইকারি অর্ডারের সুবিধাসহ।',
        ],
        'summary' => [
            'en' => 'Queens Club Fashion House sells lehengas, embroidered three-piece sets, kurtis and festive wear for women, teenagers and kids, and takes wholesale orders.',
            'bn' => 'Queens Club Fashion House নারী, টিনএজার ও শিশুদের জন্য লেহেঙ্গা, এমব্রয়ডারি করা থ্রিপিস, কুর্তি ও উৎসবের পোশাক বিক্রি করে এবং পাইকারি অর্ডার নেয়।',
        ],
        'challenge' => [
            'en' => '<p>Queens Club BD needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Shops for women, teenagers and kids.</li><li>A trending looks section and a bridal collection.</li><li>Wishlist and customer accounts.</li><li>Wholesale enquiries promoted across the site.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Queens Club BD takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'nokshacraft',
        'client' => 'Noksha Craft',
        'url' => 'https://nokshacraft.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard'],
        'tagline' => [
            'en' => 'An online store for women\'s dresses, bedsheets and traditional Nokshi Katha.',
            'bn' => 'নারীদের পোশাক, বেডশিট ও ঐতিহ্যবাহী নকশিকাঁথার অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Noksha Craft sells women\'s dresses such as its Afghani collection, twill cotton and premium bedsheets, and handmade Nokshi Katha.',
            'bn' => 'Noksha Craft আফগানি কালেকশনসহ নারীদের পোশাক, টুইল কটন ও প্রিমিয়াম বেডশিট এবং হাতে তৈরি নকশিকাঁথা বিক্রি করে।',
        ],
        'challenge' => [
            'en' => '<p>Noksha Craft needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for women\'s dresses, bedsheets and Nokshi Katha.</li><li>Bilingual product names in Bangla and English.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Noksha Craft takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'weeshopbd',
        'client' => 'WeeShop BD',
        'url' => 'https://weeshopbd.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'An online store for casual shoes, leather sandals and watches.',
            'bn' => 'ক্যাজুয়াল জুতা, লেদার স্যান্ডেল ও ঘড়ির অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'WeeShop BD sells casual shoes, genuine leather sandals and watches for men, women and couples, with hot deals and order tracking.',
            'bn' => 'WeeShop BD ক্যাজুয়াল জুতা, জেনুইন লেদার স্যান্ডেল এবং পুরুষ, নারী ও কাপলদের ঘড়ি বিক্রি করে; আছে হট ডিল ও অর্ডার ট্র্যাকিং।',
        ],
        'challenge' => [
            'en' => '<p>WeeShop BD needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for casual shoes, leather and watches for men, women and couples.</li><li>New, trending, popular and special collections.</li><li>Hot deals and offers pages.</li><li>Order tracking.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>WeeShop BD takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'technmorebd',
        'client' => 'Tech N More',
        'url' => 'https://technmorebd.com',
        'industry' => 'gadgets',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [
            'product-management',
            'order-management',
            'customer-management',
            'dashboard',
            'pre-order-management',
            'product-request-management',
            'campaign-management',
            'order-tracking',
        ],
        'tagline' => [
            'en' => 'An online store for phone accessories, organised by brand.',
            'bn' => 'ব্র্যান্ড অনুযায়ী সাজানো ফোন অ্যাকসেসরিজের অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'Tech N More sells covers and accessories for Honor, Redmi, OnePlus, Nothing, Vivo and iQOO phones, with combo offers, pre-orders and a product request service.',
            'bn' => 'Tech N More অনার, রেডমি, ওয়ানপ্লাস, নাথিং, ভিভো ও আইকিউওও ফোনের কভার ও অ্যাকসেসরিজ বিক্রি করে; আছে কম্বো অফার, প্রি-অর্ডার ও প্রোডাক্ট রিকোয়েস্ট সার্ভিস।',
        ],
        'challenge' => [
            'en' => '<p>Tech N More needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories by phone brand: Honor, Redmi, OnePlus, Nothing, Vivo and iQOO.</li><li>A trending products section.</li><li>Pre-orders and combo offers.</li><li>Product requests so customers can ask for items not yet listed.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Tech N More takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'unigear',
        'client' => 'UniGear',
        'url' => 'https://unigear.com.bd',
        'industry' => 'gadgets',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design', 'meta-pixel-capi'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [
            'product-management',
            'order-management',
            'customer-management',
            'dashboard',
            'reviews-ratings',
            'campaign-management',
            'meta-pixel-gtm',
        ],
        'tagline' => [
            'en' => 'An online store for speakers, tripods, microphones and smart watches.',
            'bn' => 'স্পিকার, ট্রাইপড, মাইক্রোফোন ও স্মার্টওয়াচের অনলাইন স্টোর।',
        ],
        'summary' => [
            'en' => 'UniGear sells Bluetooth speakers, tripods and gimbals, microphones, lights, neckbands, smart watches and mini UPS units, with product comparison, reviews and Meta Pixel tracking.',
            'bn' => 'UniGear ব্লুটুথ স্পিকার, ট্রাইপড ও জিম্বাল, মাইক্রোফোন, লাইট, নেকব্যান্ড, স্মার্টওয়াচ ও মিনি ইউপিএস বিক্রি করে; আছে প্রোডাক্ট কম্পেয়ার, রিভিউ ও মেটা পিক্সেল ট্র্যাকিং।',
        ],
        'challenge' => [
            'en' => '<p>UniGear needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Featured categories for speakers, tripods, microphones, lights, neckbands, smart watches and mini UPS.</li><li>Product reviews and ratings.</li><li>Offers and product comparison.</li><li>Meta Pixel installed for ad measurement.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>UniGear takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'gadgetpanelbd',
        'client' => 'Gadget Panel BD',
        'url' => 'https://gadgetpanelbd.com',
        'industry' => 'gadgets',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [
            'product-management',
            'order-management',
            'customer-management',
            'dashboard',
            'pre-order-management',
            'campaign-management',
            'order-tracking',
        ],
        'tagline' => [
            'en' => 'An online store for mobile accessories and wearables, with pre-order and free pickup.',
            'bn' => 'মোবাইল অ্যাকসেসরিজ ও ওয়্যারেবলের অনলাইন স্টোর, প্রি-অর্ডার ও ফ্রি পিকআপসহ।',
        ],
        'summary' => [
            'en' => 'Gadget Panel BD sells mobile and tablet accessories, audio, chargers, smart wearables, smart home gadgets, car accessories and grooming products, with shop-by-brand browsing, pre-orders and free pickup.',
            'bn' => 'Gadget Panel BD মোবাইল ও ট্যাবলেট অ্যাকসেসরিজ, অডিও, চার্জার, স্মার্ট ওয়্যারেবল, স্মার্ট হোম গ্যাজেট, কার অ্যাকসেসরিজ ও গ্রুমিং পণ্য বিক্রি করে; আছে ব্র্যান্ড অনুযায়ী কেনাকাটা, প্রি-অর্ডার ও ফ্রি পিকআপ।',
        ],
        'challenge' => [
            'en' => '<p>Gadget Panel BD needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for mobile, audio, chargers, wearables, smart home, car accessories and grooming.</li><li>Shop by brand.</li><li>Pre-orders and offers.</li><li>A free pickup option and order tracking.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Gadget Panel BD takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'bottolabazar',
        'client' => 'Bottola Bazar',
        'url' => 'https://bottolabazar.com',
        'industry' => 'general-retail',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [
            'product-management',
            'order-management',
            'customer-management',
            'dashboard',
            'campaign-management',
            'order-tracking',
            'reviews-ratings',
        ],
        'tagline' => [
            'en' => 'A multi-category online shop with order tracking and a complaint desk.',
            'bn' => 'অর্ডার ট্র্যাকিং ও অভিযোগ ডেস্কসহ একটি মাল্টি-ক্যাটাগরি অনলাইন শপ।',
        ],
        'summary' => [
            'en' => 'Bottola Bazar sells women\'s fashion, tech accessories, life-hack gadgets, home appliances, bags and organic food such as premium dates, with an offer zone, reviews and a complaint desk.',
            'bn' => 'Bottola Bazar নারীদের ফ্যাশন, টেক অ্যাকসেসরিজ, লাইফ-হ্যাক গ্যাজেট, হোম অ্যাপ্লায়েন্স, ব্যাগ এবং প্রিমিয়াম খেজুরের মতো অর্গানিক খাবার বিক্রি করে; আছে অফার জোন, রিভিউ ও অভিযোগ ডেস্ক।',
        ],
        'challenge' => [
            'en' => '<p>Bottola Bazar needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for fashion, electronics accessories, gadgets, home appliances, bags and organic food.</li><li>Product reviews and ratings.</li><li>An offer zone and order tracking.</li><li>A complaint desk for after-sales support.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Bottola Bazar takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'greenlandmarketingcompany',
        'client' => 'Greenland Marketing Company',
        'url' => 'https://greenlandmarketingcompany.com',
        'industry' => 'general-retail',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['vuejs', 'nuxt'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'A retail and corporate purchasing store with brand pages and weekly discounts.',
            'bn' => 'ব্র্যান্ড পেজ ও সাপ্তাহিক ডিসকাউন্টসহ খুচরা ও কর্পোরেট ক্রয়ের স্টোর।',
        ],
        'summary' => [
            'en' => 'Greenland Marketing Company supplies beverages, cleaning supplies, computer accessories, food, health products, stationery and snacks to households and companies, with shop-by-brand browsing, a blog and weekly discounts.',
            'bn' => 'Greenland Marketing Company পরিবার ও প্রতিষ্ঠানে পানীয়, ক্লিনিং সাপ্লাই, কম্পিউটার অ্যাকসেসরিজ, খাবার, স্বাস্থ্যপণ্য, স্টেশনারি ও স্ন্যাকস সরবরাহ করে; আছে ব্র্যান্ড অনুযায়ী কেনাকাটা, ব্লগ ও সাপ্তাহিক ডিসকাউন্ট।',
        ],
        'challenge' => [
            'en' => '<p>Greenland Marketing Company needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>A deep category tree for beverages, cleaning, computer accessories, food, health, stationery and snacks.</li><li>Shop by brand.</li><li>Weekly discounts and corporate purchase enquiries.</li><li>A blog and order tracking.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Greenland Marketing Company takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'bikolpoo',
        'client' => 'Bikolpoo',
        'url' => 'https://bikolpoo.com',
        'industry' => 'general-retail',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'An online shop for home appliances, home decor and skin care.',
            'bn' => 'হোম অ্যাপ্লায়েন্স, হোম ডেকর ও স্কিন কেয়ারের অনলাইন শপ।',
        ],
        'summary' => [
            'en' => 'Bikolpoo sells home appliances, home decor, skin care, grooming tools and baby bikes, with trending, new arrival, signature and stock clearance collections.',
            'bn' => 'Bikolpoo হোম অ্যাপ্লায়েন্স, হোম ডেকর, স্কিন কেয়ার, গ্রুমিং টুলস ও বেবি বাইক বিক্রি করে; আছে ট্রেন্ডিং, নতুন, সিগনেচার ও স্টক ক্লিয়ারেন্স কালেকশন।',
        ],
        'challenge' => [
            'en' => '<p>Bikolpoo needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for home appliances, decor, skin care and baby bikes.</li><li>Trending, new arrival, signature and stock clearance collections.</li><li>Discount pricing and order tracking.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Bikolpoo takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'cartexbd',
        'client' => 'Cartex',
        'url' => 'https://cartexbd.com',
        'industry' => 'general-retail',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [
            'product-management',
            'order-management',
            'customer-management',
            'dashboard',
            'pre-order-management',
            'campaign-management',
            'order-tracking',
        ],
        'tagline' => [
            'en' => 'An online shop for gadgets, wellness, menswear and travel accessories.',
            'bn' => 'গ্যাজেট, ওয়েলনেস, পুরুষদের পোশাক ও ট্রাভেল অ্যাকসেসরিজের অনলাইন শপ।',
        ],
        'summary' => [
            'en' => 'Cartex sells electronics and smart gadgets, health and personal care, men\'s clothing, outdoor, travel and car accessories, and home and living products, with pre-orders and free delivery offers.',
            'bn' => 'Cartex ইলেকট্রনিক্স ও স্মার্ট গ্যাজেট, স্বাস্থ্য ও পার্সোনাল কেয়ার, পুরুষদের পোশাক, আউটডোর, ট্রাভেল ও কার অ্যাকসেসরিজ এবং হোম অ্যান্ড লিভিং পণ্য বিক্রি করে; আছে প্রি-অর্ডার ও ফ্রি ডেলিভারি অফার।',
        ],
        'challenge' => [
            'en' => '<p>Cartex needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for gadgets, wellness, menswear, outdoor and travel, and home and living.</li><li>Free delivery offer collections.</li><li>Pre-orders, offers and order tracking.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Cartex takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'gulshanbakingshop',
        'client' => 'Gulshan Baking Shop',
        'url' => 'https://gulshanbakingshop.com',
        'industry' => 'food-grocery',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard'],
        'tagline' => [
            'en' => 'An online store for imported groceries and baking supplies, with 24-hour delivery in Dhaka.',
            'bn' => 'আমদানি করা গ্রোসারি ও বেকিং সামগ্রীর অনলাইন স্টোর, ঢাকায় ২৪ ঘণ্টায় ডেলিভারি।',
        ],
        'summary' => [
            'en' => 'Gulshan Baking Shop sells Korean groceries, imported vegetables, kitchen and baking tools, nuts and spices, sauces and combo packages, with free shipping in Dhaka.',
            'bn' => 'Gulshan Baking Shop কোরিয়ান গ্রোসারি, আমদানি করা সবজি, কিচেন ও বেকিং টুলস, বাদাম ও মসলা, সস ও কম্বো প্যাকেজ বিক্রি করে; ঢাকায় ফ্রি শিপিং।',
        ],
        'challenge' => [
            'en' => '<p>Gulshan Baking Shop needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for Korean items, imported vegetables, baking tools, nuts and spices, sauces and combo packages.</li><li>Product search for groceries and kitchen essentials.</li><li>Free shipping and 24-hour delivery messages across the store.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Gulshan Baking Shop takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'bdfreshbazar',
        'client' => 'BD Fresh Bazar',
        'url' => 'https://bdfreshbazar.com',
        'industry' => 'food-grocery',
        'solution' => 'ecommerce',
        'type' => 'live',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'An online dairy store for yogurt, ghee and milk.',
            'bn' => 'দই, ঘি ও দুধের অনলাইন ডেইরি স্টোর।',
        ],
        'summary' => [
            'en' => 'BD Fresh Bazar sells sweetened and sour yogurt, ghee and standardized milk online, with quick-add ordering and seasonal campaigns such as Ramadan specials.',
            'bn' => 'BD Fresh Bazar অনলাইনে মিষ্টি ও টক দই, ঘি ও স্ট্যান্ডার্ডাইজড দুধ বিক্রি করে; আছে দ্রুত অর্ডার ও রমজান স্পেশালের মতো মৌসুমি ক্যাম্পেইন।',
        ],
        'challenge' => [
            'en' => '<p>BD Fresh Bazar needed a fast, mobile-friendly store that customers in Bangladesh trust, with cash on delivery, clear categories and an admin panel the team can run every day without developer help.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>A focused catalogue for yogurt, ghee and milk.</li><li>Quick-add buttons for repeat purchases.</li><li>Seasonal campaigns and order tracking.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>BD Fresh Bazar takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'resellerv2',
        'client' => 'Auxtech Reseller',
        'url' => 'https://resellerv2.auxtechsoftware.com',
        'industry' => 'reselling',
        'solution' => 'reseller-platform',
        'type' => 'demo',
        'featured' => false,
        'services' => ['custom-software-development', 'ecommerce-development'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => [],
        'tagline' => [
            'en' => 'Live demo of the Auxtech B2B reseller and dropshipping platform.',
            'bn' => 'Auxtech B2B রিসেলার ও ড্রপশিপিং প্ল্যাটফর্মের লাইভ ডেমো।',
        ],
        'summary' => [
            'en' => 'Our reference reseller platform, ready to brand for a new business: reseller sign-up, product catalogue, packages, white-label shipping and payouts.',
            'bn' => 'আমাদের রেফারেন্স রিসেলার প্ল্যাটফর্ম, নতুন ব্যবসার ব্র্যান্ডে সাজানোর জন্য প্রস্তুত: রিসেলার সাইন-আপ, প্রোডাক্ট ক্যাটালগ, প্যাকেজ, হোয়াইট-লেবেল শিপিং ও পে-আউট।',
        ],
        'challenge' => [
            'en' => '<p>Businesses considering a reseller programme want to see exactly how it works before they commit.</p>',
        ],
        'approach' => [
            'en' => '<p>This demo shows the full platform:</p><ul><li>Reseller registration, login and packages.</li><li>Trending categories and best-selling products.</li><li>Ordering at the reseller\'s own price with delivery to 64 districts.</li><li>Services, about and contact pages.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Prospective clients can try the platform end to end before we configure it for their brand.</p>',
        ],
    ],
    [
        'slug' => 'kalki',
        'client' => 'Kalki',
        'url' => 'https://kalki.auxdemo.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'demo',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['vuejs', 'nuxt'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard'],
        'tagline' => [
            'en' => 'Demo of a full family fashion store: women, men, kids, newborn and footwear.',
            'bn' => 'পুরো পরিবারের ফ্যাশন স্টোরের ডেমো: নারী, পুরুষ, শিশু, নবজাতক ও জুতা।',
        ],
        'summary' => [
            'en' => 'A complete storefront demo with a deep catalogue: lehengas, kameez and sarees, shirts and panjabis, kids\' and newborn sets, footwear and accessories.',
            'bn' => 'গভীর ক্যাটালগসহ একটি সম্পূর্ণ স্টোরফ্রন্ট ডেমো: লেহেঙ্গা, কামিজ ও শাড়ি, শার্ট ও পাঞ্জাবি, শিশু ও নবজাতকের সেট, জুতা ও অ্যাকসেসরিজ।',
        ],
        'challenge' => [
            'en' => '<p>Kalki needed a complete storefront ready before launch, to test the catalogue, offers and ordering flow with real customers.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>A multi-level menu for women, men, kids, newborn, footwear and accessories.</li><li>Trending looks and what\'s new sections.</li><li>Free shipping and delivery-time messages.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Kalki takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'thepatchee',
        'client' => 'Jamilooms',
        'url' => 'https://thepatchee.auxtechsoftware.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'demo',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management', 'order-tracking'],
        'tagline' => [
            'en' => 'A pre-launch store for T-shirts, bags, caps and shoes.',
            'bn' => 'টি-শার্ট, ব্যাগ, ক্যাপ ও জুতার প্রি-লঞ্চ স্টোর।',
        ],
        'summary' => [
            'en' => 'Jamilooms sells T-shirts, clutch bags, accessories, caps, semi-drop tees, bandanas and shoes, with seasonal campaigns and a watch-and-shop video section.',
            'bn' => 'Jamilooms টি-শার্ট, ক্লাচ ব্যাগ, অ্যাকসেসরিজ, ক্যাপ, সেমি-ড্রপ টি, ব্যান্ডানা ও জুতা বিক্রি করে; আছে মৌসুমি ক্যাম্পেইন ও ভিডিও দেখে কেনার সেকশন।',
        ],
        'challenge' => [
            'en' => '<p>Jamilooms needed a complete storefront ready before launch, to test the catalogue, offers and ordering flow with real customers.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for T-shirts, clutch bags, accessories, caps, bandanas and shoes.</li><li>A watch-and-shop section with product videos.</li><li>Seasonal campaigns and order tracking.</li><li>A complaint form for after-sales issues.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Jamilooms takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'lavieworld',
        'client' => 'La Vie World',
        'url' => 'https://lavieworld.auxtechsoftware.com',
        'industry' => 'fashion',
        'solution' => 'ecommerce',
        'type' => 'demo',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard'],
        'tagline' => [
            'en' => 'A pre-launch store for handbags, sling bags and perfumes.',
            'bn' => 'হ্যান্ডব্যাগ, স্লিং ব্যাগ ও পারফিউমের প্রি-লঞ্চ স্টোর।',
        ],
        'summary' => [
            'en' => 'La Vie World sells handbags, sling bags, clutches and potlis, wallets and perfumes, with a watch-and-shop section and one-tap cash-on-delivery ordering.',
            'bn' => 'La Vie World হ্যান্ডব্যাগ, স্লিং ব্যাগ, ক্লাচ ও পটলি, ওয়ালেট ও পারফিউম বিক্রি করে; আছে ভিডিও দেখে কেনা ও এক ট্যাপে ক্যাশ অন ডেলিভারি অর্ডার।',
        ],
        'challenge' => [
            'en' => '<p>La Vie World needed a complete storefront ready before launch, to test the catalogue, offers and ordering flow with real customers.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for handbags, sling bags, perfumes, clutches and wallets.</li><li>A watch-and-shop section with product videos.</li><li>One-tap cash-on-delivery ordering.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>La Vie World takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
    [
        'slug' => 'seasonal-demo',
        'client' => 'Seasonal (demo)',
        'url' => 'https://seasonal.auxtechsoftware.com',
        'industry' => 'food-grocery',
        'solution' => 'ecommerce',
        'type' => 'demo',
        'featured' => false,
        'services' => ['ecommerce-development', 'ui-ux-design'],
        'technologies' => ['laravel', 'vuejs', 'inertiajs'],
        'modules' => ['product-management', 'order-management', 'customer-management', 'dashboard', 'campaign-management'],
        'tagline' => [
            'en' => 'Demo of the natural food storefront.',
            'bn' => 'প্রাকৃতিক খাবারের স্টোরফ্রন্টের ডেমো।',
        ],
        'summary' => [
            'en' => 'A demo of the grocery storefront with honey, oil and ghee, dates, spices and nuts, used to show food businesses how the store works.',
            'bn' => 'মধু, তেল ও ঘি, খেজুর, মসলা ও বাদামসহ গ্রোসারি স্টোরফ্রন্টের ডেমো, খাদ্য ব্যবসাকে স্টোরটি কীভাবে কাজ করে তা দেখানোর জন্য।',
        ],
        'challenge' => [
            'en' => '<p>Seasonal (demo) needed a complete storefront ready before launch, to test the catalogue, offers and ordering flow with real customers.</p>',
        ],
        'approach' => [
            'en' => '<p>We launched the store on Auxtech Ecommerce and configured it around the catalogue:</p><ul><li>Categories for honey, oil and ghee, dates, spices and nuts.</li><li>Offer zone and one-tap ordering.</li></ul>',
        ],
        'outcome' => [
            'en' => '<p>Seasonal (demo) takes and manages orders online from one admin panel, with products, customers and orders in the same place.</p>',
        ],
    ],
];
