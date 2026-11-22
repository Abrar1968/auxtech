<?php

/*
| Services, platforms, industries, technologies and FAQs seeded by
| CatalogueSeeder and ContentSeeder. Everything stays editable in the admin.
*/

return [
    'services' => [
        [
            'slug' => 'custom-software-development',
            'parent' => null,
            'icon' => 'code-xml',
            'featured' => true,
            'title' => [
                'en' => 'Custom Software Development',
                'bn' => 'কাস্টম সফটওয়্যার ডেভেলপমেন্ট',
            ],
            'tagline' => [
                'en' => 'Software designed around the way your business works.',
                'bn' => 'আপনার ব্যবসার কাজের ধরন অনুযায়ী ডিজাইন করা সফটওয়্যার।',
            ],
            'excerpt' => [
                'en' => 'Business systems, SaaS products and multi-role platforms built from scratch when off-the-shelf tools do not fit.',
                'bn' => 'রেডিমেড টুল কাজে না এলে শুরু থেকে তৈরি বিজনেস সিস্টেম, SaaS প্রোডাক্ট ও মাল্টি-রোল প্ল্যাটফর্ম।',
            ],
            'body' => [
                'en' => '<p>When spreadsheets, chat groups and generic tools start slowing your team down, custom software puts your process into one reliable system. We design and build software that fits how you already work, then improve it as your business changes.</p><h2>What we build</h2><ul><li>Operations and business management systems: orders, inventory, accounts, staff and reporting.</li><li>Multi-role platforms that connect customers, partners, riders or resellers.</li><li>Calculators, portals and workflow tools that replace manual paperwork.</li><li>Software-as-a-service products, from first version to paying customers.</li></ul><h2>How we work</h2><p>Every project starts with discovery and design, so the scope is clear before development begins. We build in short iterations on a staging site you can use, and we stay after launch to maintain and extend the software.</p>',
            ],
            'features' => [
                [
                    'title' => [
                        'en' => 'Business process analysis',
                        'bn' => 'ব্যবসায়িক প্রক্রিয়া বিশ্লেষণ',
                    ],
                    'description' => [
                        'en' => 'We map how work flows today and where software saves the most time.',
                        'bn' => 'বর্তমানে কাজ কীভাবে চলে এবং কোথায় সফটওয়্যার সবচেয়ে বেশি সময় বাঁচায়, তা আমরা চিহ্নিত করি।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Role-based access',
                        'bn' => 'রোল-ভিত্তিক অ্যাক্সেস',
                    ],
                    'description' => [
                        'en' => 'Separate permissions and dashboards for each type of user.',
                        'bn' => 'প্রতিটি ধরনের ব্যবহারকারীর জন্য আলাদা অনুমতি ও ড্যাশবোর্ড।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Reports and dashboards',
                        'bn' => 'রিপোর্ট ও ড্যাশবোর্ড',
                    ],
                    'description' => [
                        'en' => 'The numbers your team needs, updated in real time.',
                        'bn' => 'আপনার টিমের প্রয়োজনীয় সংখ্যা, রিয়েল টাইমে আপডেট।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Integrations',
                        'bn' => 'ইন্টিগ্রেশন',
                    ],
                    'description' => [
                        'en' => 'Payment gateways, couriers, SMS, email and third-party APIs.',
                        'bn' => 'পেমেন্ট গেটওয়ে, কুরিয়ার, এসএমএস, ইমেইল ও থার্ড-পার্টি এপিআই।',
                    ],
                ],
            ],
            'process_steps' => [
                [
                    'title' => [
                        'en' => 'Discovery',
                        'bn' => 'প্রয়োজন বোঝা',
                    ],
                    'description' => [
                        'en' => 'Workshops to understand goals, users and constraints, ending in an agreed scope.',
                        'bn' => 'লক্ষ্য, ব্যবহারকারী ও সীমাবদ্ধতা বুঝতে আলোচনা, শেষে একটি সম্মত স্কোপ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Design',
                        'bn' => 'ডিজাইন',
                    ],
                    'description' => [
                        'en' => 'User journeys, interface design and technical architecture, reviewed with you.',
                        'bn' => 'ইউজার জার্নি, ইন্টারফেস ডিজাইন ও টেকনিক্যাল আর্কিটেকচার, আপনার সাথে রিভিউ করে।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Build and test',
                        'bn' => 'ডেভেলপমেন্ট ও টেস্টিং',
                    ],
                    'description' => [
                        'en' => 'Short iterations with code review, testing and a staging site you can try.',
                        'bn' => 'কোড রিভিউ, টেস্টিং ও স্টেজিং সাইটসহ ছোট ছোট ধাপে কাজ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Launch and support',
                        'bn' => 'লঞ্চ ও সাপোর্ট',
                    ],
                    'description' => [
                        'en' => 'Deployment, training and ongoing maintenance after go-live.',
                        'bn' => 'ডিপ্লয়মেন্ট, ট্রেনিং এবং লাইভের পর নিয়মিত রক্ষণাবেক্ষণ।',
                    ],
                ],
            ],
        ],
        [
            'slug' => 'saas-development',
            'parent' => 'custom-software-development',
            'icon' => 'cloud',
            'featured' => false,
            'title' => [
                'en' => 'SaaS Product Development',
                'bn' => 'SaaS প্রোডাক্ট ডেভেলপমেন্ট',
            ],
            'tagline' => [
                'en' => 'From idea to a subscription product customers pay for.',
                'bn' => 'আইডিয়া থেকে এমন সাবস্ক্রিপশন প্রোডাক্ট, যার জন্য গ্রাহক অর্থ দেয়।',
            ],
            'excerpt' => [
                'en' => 'Multi-tenant platforms with sign-up, plans, billing and an admin for your team, built by the team behind Auxify.',
                'bn' => 'সাইন-আপ, প্ল্যান, বিলিং ও টিমের জন্য অ্যাডমিনসহ মাল্টি-টেন্যান্ট প্ল্যাটফর্ম, Auxify-এর নির্মাতা টিমের হাতে।',
            ],
            'body' => [
                'en' => '<p>We build and run our own SaaS product, Auxify, so we know what it takes: self-service onboarding, plans that grow with the customer, reliable hosting and a support workflow. We bring that experience to your product.</p><ul><li>Multi-tenant architecture with per-customer data separation.</li><li>Sign-up, onboarding, plans and subscription management.</li><li>Custom domains, SSL and storefront or workspace themes.</li><li>An internal admin for support, billing and analytics.</li></ul>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'api-integrations',
            'parent' => 'custom-software-development',
            'icon' => 'plug',
            'featured' => false,
            'title' => [
                'en' => 'API Development and Integrations',
                'bn' => 'এপিআই ডেভেলপমেন্ট ও ইন্টিগ্রেশন',
            ],
            'tagline' => [
                'en' => 'Connect your systems so data flows without copy and paste.',
                'bn' => 'আপনার সিস্টেমগুলো যুক্ত করুন, যাতে কপি-পেস্ট ছাড়াই ডেটা চলে।',
            ],
            'excerpt' => [
                'en' => 'REST APIs for your apps and partners, and integrations with payment gateways, couriers, SMS and marketing platforms.',
                'bn' => 'আপনার অ্যাপ ও পার্টনারের জন্য REST এপিআই, আর পেমেন্ট গেটওয়ে, কুরিয়ার, এসএমএস ও মার্কেটিং প্ল্যাটফর্মের সাথে ইন্টিগ্রেশন।',
            ],
            'body' => [
                'en' => '<p>We design clean, documented APIs that power mobile apps and partner integrations, and we connect your software to the services it depends on: payment gateways, courier companies, SMS and email providers, Meta and Google.</p>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'website-development',
            'parent' => null,
            'icon' => 'globe',
            'featured' => true,
            'title' => [
                'en' => 'Web Development',
                'bn' => 'ওয়েব ডেভেলপমেন্ট',
            ],
            'tagline' => [
                'en' => 'Fast, secure websites and web applications that are easy to manage.',
                'bn' => 'দ্রুত, নিরাপদ ও সহজে পরিচালনাযোগ্য ওয়েবসাইট ও ওয়েব অ্যাপ্লিকেশন।',
            ],
            'excerpt' => [
                'en' => 'Corporate websites, portals and web applications engineered for speed, search visibility and easy content management.',
                'bn' => 'গতি, সার্চ ভিজিবিলিটি ও সহজ কনটেন্ট ম্যানেজমেন্টের জন্য তৈরি কর্পোরেট ওয়েবসাইট, পোর্টাল ও ওয়েব অ্যাপ্লিকেশন।',
            ],
            'body' => [
                'en' => '<p>Your website is often the first conversation a customer has with your business. We build sites and web applications that load fast on any phone, rank well in search, and let your team update content without a developer.</p><h2>What we build</h2><ul><li>Corporate and company websites with service, project and contact pages.</li><li>Customer and partner portals with secure accounts.</li><li>Web applications such as booking, calculators, learning and content platforms.</li><li>Bilingual Bangla and English sites with search-friendly structure.</li></ul><h2>Built to last</h2><p>We use modern frameworks such as Laravel, Vue.js, Nuxt, React and Next.js, choosing what fits the project, with clean code, automated tests and a content management panel tailored to you.</p>',
            ],
            'features' => [
                [
                    'title' => [
                        'en' => 'Performance first',
                        'bn' => 'পারফরম্যান্স সবার আগে',
                    ],
                    'description' => [
                        'en' => 'Pages that load quickly on mobile networks.',
                        'bn' => 'মোবাইল নেটওয়ার্কেও দ্রুত লোড হওয়া পেজ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Search-friendly structure',
                        'bn' => 'সার্চ-বান্ধব কাঠামো',
                    ],
                    'description' => [
                        'en' => 'Clean URLs, metadata, structured data and sitemaps.',
                        'bn' => 'পরিচ্ছন্ন ইউআরএল, মেটাডেটা, স্ট্রাকচার্ড ডেটা ও সাইটম্যাপ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Content you control',
                        'bn' => 'আপনার নিয়ন্ত্রণে কনটেন্ট',
                    ],
                    'description' => [
                        'en' => 'An admin panel to edit pages, images and settings.',
                        'bn' => 'পেজ, ছবি ও সেটিংস এডিট করার অ্যাডমিন প্যানেল।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Secure by default',
                        'bn' => 'শুরু থেকেই নিরাপদ',
                    ],
                    'description' => [
                        'en' => 'HTTPS, security headers, spam protection and backups.',
                        'bn' => 'HTTPS, সিকিউরিটি হেডার, স্প্যাম সুরক্ষা ও ব্যাকআপ।',
                    ],
                ],
            ],
            'process_steps' => [
                [
                    'title' => [
                        'en' => 'Discovery',
                        'bn' => 'প্রয়োজন বোঝা',
                    ],
                    'description' => [
                        'en' => 'Workshops to understand goals, users and constraints, ending in an agreed scope.',
                        'bn' => 'লক্ষ্য, ব্যবহারকারী ও সীমাবদ্ধতা বুঝতে আলোচনা, শেষে একটি সম্মত স্কোপ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Design',
                        'bn' => 'ডিজাইন',
                    ],
                    'description' => [
                        'en' => 'User journeys, interface design and technical architecture, reviewed with you.',
                        'bn' => 'ইউজার জার্নি, ইন্টারফেস ডিজাইন ও টেকনিক্যাল আর্কিটেকচার, আপনার সাথে রিভিউ করে।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Build and test',
                        'bn' => 'ডেভেলপমেন্ট ও টেস্টিং',
                    ],
                    'description' => [
                        'en' => 'Short iterations with code review, testing and a staging site you can try.',
                        'bn' => 'কোড রিভিউ, টেস্টিং ও স্টেজিং সাইটসহ ছোট ছোট ধাপে কাজ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Launch and support',
                        'bn' => 'লঞ্চ ও সাপোর্ট',
                    ],
                    'description' => [
                        'en' => 'Deployment, training and ongoing maintenance after go-live.',
                        'bn' => 'ডিপ্লয়মেন্ট, ট্রেনিং এবং লাইভের পর নিয়মিত রক্ষণাবেক্ষণ।',
                    ],
                ],
            ],
        ],
        [
            'slug' => 'web-application-development',
            'parent' => 'website-development',
            'icon' => 'app-window',
            'featured' => false,
            'title' => [
                'en' => 'Web Application Development',
                'bn' => 'ওয়েব অ্যাপ্লিকেশন ডেভেলপমেন্ট',
            ],
            'tagline' => [
                'en' => 'Browser-based software your customers and team use every day.',
                'bn' => 'ব্রাউজারভিত্তিক সফটওয়্যার, যা আপনার গ্রাহক ও টিম প্রতিদিন ব্যবহার করে।',
            ],
            'excerpt' => [
                'en' => 'Learning platforms, portals, calculators and multi-role systems that run in any browser.',
                'bn' => 'যেকোনো ব্রাউজারে চলা লার্নিং প্ল্যাটফর্ম, পোর্টাল, ক্যালকুলেটর ও মাল্টি-রোল সিস্টেম।',
            ],
            'body' => [
                'en' => '<p>Web applications reach every user without an app store. We build interactive, secure applications with accounts, dashboards and workflows, such as learning platforms, tax calculators and civic service platforms.</p>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'corporate-websites',
            'parent' => 'website-development',
            'icon' => 'building-2',
            'featured' => false,
            'title' => [
                'en' => 'Corporate Websites',
                'bn' => 'কর্পোরেট ওয়েবসাইট ডেভেলপমেন্ট',
            ],
            'tagline' => [
                'en' => 'A credible, fast website that presents your company well.',
                'bn' => 'আপনার প্রতিষ্ঠানকে সুন্দরভাবে তুলে ধরা একটি বিশ্বাসযোগ্য ও দ্রুত ওয়েবসাইট।',
            ],
            'excerpt' => [
                'en' => 'Company websites that explain your services clearly, showcase your work and turn visitors into enquiries.',
                'bn' => 'আপনার সার্ভিস পরিষ্কারভাবে ব্যাখ্যা, কাজ প্রদর্শন এবং ভিজিটরকে অনুসন্ধানকারীতে রূপান্তর করে এমন কর্পোরেট ওয়েবসাইট।',
            ],
            'body' => [
                'en' => '<p>We design corporate websites that make complex offerings easy to understand, from industrial engineering to travel services, with clear calls to action and content your team can keep up to date.</p>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'ecommerce-development',
            'parent' => null,
            'icon' => 'shopping-cart',
            'featured' => true,
            'title' => [
                'en' => 'E-commerce Development',
                'bn' => 'ই-কমার্স ডেভেলপমেন্ট',
            ],
            'tagline' => [
                'en' => 'Online stores and commerce platforms that run the whole business.',
                'bn' => 'অনলাইন স্টোর ও কমার্স প্ল্যাটফর্ম, যা পুরো ব্যবসা পরিচালনা করে।',
            ],
            'excerpt' => [
                'en' => 'Stores built on Auxtech Ecommerce, with orders, inventory, point of sale, accounting, couriers and Meta tracking in one admin panel.',
                'bn' => 'Auxtech Ecommerce-এ তৈরি স্টোর, যেখানে অর্ডার, ইনভেন্টরি, পয়েন্ট অব সেল, হিসাব, কুরিয়ার ও মেটা ট্র্যাকিং এক অ্যাডমিন প্যানেলে।',
            ],
            'body' => [
                'en' => '<p>We have launched online stores for fashion, electronics, grocery and general retail brands on our own commerce platform. It is built for how customers in Bangladesh buy: mobile first, cash on delivery, courier booking and order confirmation.</p><h2>What is included</h2><ul><li>A fast, branded storefront with categories, variants, search and offers.</li><li>Order, inventory, customer and employee management.</li><li>Point of sale for showrooms, sharing the same stock as the website.</li><li>Courier integration, order tracking and incomplete-order recovery.</li><li>Meta Pixel, Google Tag Manager and server-side Conversions API.</li></ul><h2>Beyond a single store</h2><p>We also build B2B reseller and dropshipping platforms, and Auxify, our self-service store builder for smaller businesses.</p>',
            ],
            'features' => [
                [
                    'title' => [
                        'en' => '23 commerce modules',
                        'bn' => '২৩টি কমার্স মডিউল',
                    ],
                    'description' => [
                        'en' => 'Sell, fulfil, grow and control the business from one panel.',
                        'bn' => 'এক প্যানেল থেকে বিক্রি, ডেলিভারি, গ্রোথ ও নিয়ন্ত্রণ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Built for cash on delivery',
                        'bn' => 'ক্যাশ অন ডেলিভারির জন্য তৈরি',
                    ],
                    'description' => [
                        'en' => 'Order confirmation, courier booking and tracking.',
                        'bn' => 'অর্ডার কনফার্মেশন, কুরিয়ার বুকিং ও ট্র্যাকিং।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Ads that see every sale',
                        'bn' => 'প্রতিটি বিক্রি দেখে এমন অ্যাড',
                    ],
                    'description' => [
                        'en' => 'Browser and server-side tracking for Meta campaigns.',
                        'bn' => 'মেটা ক্যাম্পেইনের জন্য ব্রাউজার ও সার্ভার-সাইড ট্র্যাকিং।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Online and showroom together',
                        'bn' => 'অনলাইন ও শোরুম একসাথে',
                    ],
                    'description' => [
                        'en' => 'Point of sale and inventory share the same stock.',
                        'bn' => 'পয়েন্ট অব সেল ও ইনভেন্টরি একই স্টক থেকে চলে।',
                    ],
                ],
            ],
            'process_steps' => [
                [
                    'title' => [
                        'en' => 'Discovery',
                        'bn' => 'প্রয়োজন বোঝা',
                    ],
                    'description' => [
                        'en' => 'Workshops to understand goals, users and constraints, ending in an agreed scope.',
                        'bn' => 'লক্ষ্য, ব্যবহারকারী ও সীমাবদ্ধতা বুঝতে আলোচনা, শেষে একটি সম্মত স্কোপ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Design',
                        'bn' => 'ডিজাইন',
                    ],
                    'description' => [
                        'en' => 'User journeys, interface design and technical architecture, reviewed with you.',
                        'bn' => 'ইউজার জার্নি, ইন্টারফেস ডিজাইন ও টেকনিক্যাল আর্কিটেকচার, আপনার সাথে রিভিউ করে।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Build and test',
                        'bn' => 'ডেভেলপমেন্ট ও টেস্টিং',
                    ],
                    'description' => [
                        'en' => 'Short iterations with code review, testing and a staging site you can try.',
                        'bn' => 'কোড রিভিউ, টেস্টিং ও স্টেজিং সাইটসহ ছোট ছোট ধাপে কাজ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Launch and support',
                        'bn' => 'লঞ্চ ও সাপোর্ট',
                    ],
                    'description' => [
                        'en' => 'Deployment, training and ongoing maintenance after go-live.',
                        'bn' => 'ডিপ্লয়মেন্ট, ট্রেনিং এবং লাইভের পর নিয়মিত রক্ষণাবেক্ষণ।',
                    ],
                ],
            ],
        ],
        [
            'slug' => 'reseller-platform-development',
            'parent' => 'ecommerce-development',
            'icon' => 'network',
            'featured' => false,
            'title' => [
                'en' => 'B2B Reseller and Dropshipping Platforms',
                'bn' => 'B2B রিসেলার ও ড্রপশিপিং প্ল্যাটফর্ম',
            ],
            'tagline' => [
                'en' => 'Let resellers sell your catalogue while you control stock and delivery.',
                'bn' => 'রিসেলাররা আপনার ক্যাটালগ বিক্রি করবে, স্টক ও ডেলিভারি থাকবে আপনার নিয়ন্ত্রণে।',
            ],
            'excerpt' => [
                'en' => 'Reseller sign-up, packages, catalogue sharing, white-label parcels and payouts, on a platform we have launched for several networks.',
                'bn' => 'রিসেলার সাইন-আপ, প্যাকেজ, ক্যাটালগ শেয়ারিং, হোয়াইট-লেবেল পার্সেল ও পে-আউট, একাধিক নেটওয়ার্কে চালু করা আমাদের প্ল্যাটফর্মে।',
            ],
            'body' => [
                'en' => '<p>Reseller networks grow fast but are hard to manage in chat groups. Our platform gives resellers a catalogue, ordering at their own price and earnings tracking, while you handle sourcing, packaging and cash-on-delivery shipping from one admin.</p>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'app-development',
            'parent' => null,
            'icon' => 'smartphone',
            'featured' => true,
            'title' => [
                'en' => 'Mobile App Development',
                'bn' => 'মোবাইল অ্যাপ ডেভেলপমেন্ট',
            ],
            'tagline' => [
                'en' => 'Android and iOS apps connected to your website and back office.',
                'bn' => 'আপনার ওয়েবসাইট ও ব্যাক অফিসের সাথে যুক্ত অ্যান্ড্রয়েড ও আইওএস অ্যাপ।',
            ],
            'excerpt' => [
                'en' => 'Customer, staff and partner apps that share data with your website and admin panel through secure APIs.',
                'bn' => 'নিরাপদ এপিআইয়ের মাধ্যমে ওয়েবসাইট ও অ্যাডমিন প্যানেলের সাথে ডেটা শেয়ার করা গ্রাহক, স্টাফ ও পার্টনার অ্যাপ।',
            ],
            'body' => [
                'en' => '<p>A mobile app makes sense when customers or staff return often: to order again, track a delivery, study a course or log their work. We build apps that work alongside your website and back office rather than as a separate island.</p><ul><li>Customer apps for ordering, tracking and loyalty.</li><li>Staff and rider apps for field work and deliveries.</li><li>Push notifications, offline-friendly screens and secure login.</li><li>APIs shared with your website, so data stays in one place.</li></ul>',
            ],
            'features' => null,
            'process_steps' => [
                [
                    'title' => [
                        'en' => 'Discovery',
                        'bn' => 'প্রয়োজন বোঝা',
                    ],
                    'description' => [
                        'en' => 'Workshops to understand goals, users and constraints, ending in an agreed scope.',
                        'bn' => 'লক্ষ্য, ব্যবহারকারী ও সীমাবদ্ধতা বুঝতে আলোচনা, শেষে একটি সম্মত স্কোপ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Design',
                        'bn' => 'ডিজাইন',
                    ],
                    'description' => [
                        'en' => 'User journeys, interface design and technical architecture, reviewed with you.',
                        'bn' => 'ইউজার জার্নি, ইন্টারফেস ডিজাইন ও টেকনিক্যাল আর্কিটেকচার, আপনার সাথে রিভিউ করে।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Build and test',
                        'bn' => 'ডেভেলপমেন্ট ও টেস্টিং',
                    ],
                    'description' => [
                        'en' => 'Short iterations with code review, testing and a staging site you can try.',
                        'bn' => 'কোড রিভিউ, টেস্টিং ও স্টেজিং সাইটসহ ছোট ছোট ধাপে কাজ।',
                    ],
                ],
                [
                    'title' => [
                        'en' => 'Launch and support',
                        'bn' => 'লঞ্চ ও সাপোর্ট',
                    ],
                    'description' => [
                        'en' => 'Deployment, training and ongoing maintenance after go-live.',
                        'bn' => 'ডিপ্লয়মেন্ট, ট্রেনিং এবং লাইভের পর নিয়মিত রক্ষণাবেক্ষণ।',
                    ],
                ],
            ],
        ],
        [
            'slug' => 'ui-ux-design',
            'parent' => null,
            'icon' => 'pen-tool',
            'featured' => true,
            'title' => [
                'en' => 'UI/UX and Product Design',
                'bn' => 'UI/UX ও প্রোডাক্ট ডিজাইন',
            ],
            'tagline' => [
                'en' => 'Interfaces that feel simple, because the hard thinking is done first.',
                'bn' => 'সহজ লাগে এমন ইন্টারফেস, কারণ কঠিন ভাবনাটা আগেই সেরে নেওয়া হয়।',
            ],
            'excerpt' => [
                'en' => 'User research, journeys, wireframes and interface design for websites, web applications and apps.',
                'bn' => 'ওয়েবসাইট, ওয়েব অ্যাপ্লিকেশন ও অ্যাপের জন্য ইউজার রিসার্চ, জার্নি, ওয়্যারফ্রেম ও ইন্টারফেস ডিজাইন।',
            ],
            'body' => [
                'en' => '<p>Good software should feel simple. We design the flows and screens before development starts, test them with real users where possible, and hand over a design system the engineers build exactly.</p><ul><li>User journeys and information architecture.</li><li>Wireframes and clickable prototypes.</li><li>Interface design for web and mobile, in Bangla and English.</li><li>Design systems and accessibility checks.</li></ul>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'digital-marketing',
            'parent' => null,
            'icon' => 'megaphone',
            'featured' => true,
            'title' => [
                'en' => 'Digital Marketing and Growth',
                'bn' => 'ডিজিটাল মার্কেটিং ও গ্রোথ',
            ],
            'tagline' => [
                'en' => 'Bring the right customers to the software we build.',
                'bn' => 'আমাদের তৈরি সফটওয়্যারে সঠিক গ্রাহক নিয়ে আসুন।',
            ],
            'excerpt' => [
                'en' => 'Search engine optimisation, Facebook and Instagram ads, and accurate tracking with Meta Pixel and the Conversions API.',
                'bn' => 'সার্চ ইঞ্জিন অপটিমাইজেশন, ফেসবুক ও ইনস্টাগ্রাম বিজ্ঞাপন এবং মেটা পিক্সেল ও কনভার্সন এপিআই দিয়ে নির্ভুল ট্র্যাকিং।',
            ],
            'body' => [
                'en' => '<p>Because we build the platforms, we can measure marketing properly: every order, lead and sign-up is tracked from the browser and the server. That lets ad platforms optimise on real results and lets you see what each campaign returns.</p><ul><li>Technical and content SEO for websites and stores.</li><li>Meta ad campaigns planned, run and reported.</li><li>Meta Pixel, Google Tag Manager and Conversions API set-up.</li><li>Analytics dashboards and conversion improvements.</li></ul>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'seo',
            'parent' => 'digital-marketing',
            'icon' => 'search',
            'featured' => false,
            'title' => [
                'en' => 'Search Engine Optimisation',
                'bn' => 'সার্চ ইঞ্জিন অপটিমাইজেশন',
            ],
            'tagline' => [
                'en' => 'Be found on Google by the people looking for you.',
                'bn' => 'যারা আপনাকে খুঁজছে, তারা যেন গুগলে আপনাকে পায়।',
            ],
            'excerpt' => [
                'en' => 'Technical SEO, structured data, content and local search for Bangla and English audiences.',
                'bn' => 'বাংলা ও ইংরেজি দর্শকের জন্য টেকনিক্যাল এসইও, স্ট্রাকচার্ড ডেটা, কনটেন্ট ও লোকাল সার্চ।',
            ],
            'body' => [
                'en' => '<p>We fix the technical foundations first, speed, crawlability, structured data and sitemaps, then plan content around what your customers search for, in Bangla and English.</p>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'meta-ads',
            'parent' => 'digital-marketing',
            'icon' => 'target',
            'featured' => false,
            'title' => [
                'en' => 'Facebook and Instagram Ads',
                'bn' => 'ফেসবুক ও ইনস্টাগ্রাম বিজ্ঞাপন',
            ],
            'tagline' => [
                'en' => 'Campaigns measured on sales, not likes.',
                'bn' => 'লাইক নয়, বিক্রির ভিত্তিতে মাপা ক্যাম্পেইন।',
            ],
            'excerpt' => [
                'en' => 'Meta ad campaigns planned, run and reported against real orders and leads.',
                'bn' => 'আসল অর্ডার ও লিডের ভিত্তিতে পরিকল্পিত, পরিচালিত ও রিপোর্ট করা মেটা বিজ্ঞাপন ক্যাম্পেইন।',
            ],
            'body' => [
                'en' => '<p>We plan audiences and creative with you, run the campaigns, and report on orders and leads rather than reach, using the tracking we set up on your site.</p>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
        [
            'slug' => 'meta-pixel-capi',
            'parent' => 'digital-marketing',
            'icon' => 'activity',
            'featured' => false,
            'title' => [
                'en' => 'Meta Pixel and Conversions API Setup',
                'bn' => 'মেটা পিক্সেল ও কনভার্সন এপিআই সেটআপ',
            ],
            'tagline' => [
                'en' => 'Tracking that survives ad blockers and browser privacy limits.',
                'bn' => 'অ্যাড ব্লকার ও ব্রাউজারের প্রাইভেসি সীমার মধ্যেও কাজ করা ট্র্যাকিং।',
            ],
            'excerpt' => [
                'en' => 'Browser and server-side tracking with deduplicated events, so Meta sees every order.',
                'bn' => 'ডুপ্লিকেট-মুক্ত ইভেন্টসহ ব্রাউজার ও সার্ভার-সাইড ট্র্যাকিং, যাতে মেটা প্রতিটি অর্ডার দেখতে পায়।',
            ],
            'body' => [
                'en' => '<p>We install the Meta Pixel and Google Tag Manager with consent controls, and send the same events from your server through the Conversions API with shared event IDs, so nothing is counted twice and nothing is missed.</p>',
            ],
            'features' => null,
            'process_steps' => null,
        ],
    ],
    'retired_services' => ['custom-web-application'],
    'solutions' => [
        [
            'slug' => 'ecommerce',
            'icon' => 'shopping-bag',
            'flagship' => true,
            'demo_url' => 'https://kalki.auxdemo.com',
            'external_url' => null,
            'name' => [
                'en' => 'Auxtech Ecommerce',
                'bn' => 'Auxtech Ecommerce',
            ],
            'tagline' => [
                'en' => 'Our commerce platform: storefront and back office in one.',
                'bn' => 'আমাদের কমার্স প্ল্যাটফর্ম: স্টোরফ্রন্ট ও ব্যাক অফিস একসাথে।',
            ],
            'summary' => [
                'en' => 'A complete commerce system for brands in Bangladesh: storefront, orders, inventory, point of sale, accounting, couriers and Meta tracking in one admin panel, with 23 modules.',
                'bn' => 'বাংলাদেশের ব্র্যান্ডের জন্য সম্পূর্ণ কমার্স সিস্টেম: স্টোরফ্রন্ট, অর্ডার, ইনভেন্টরি, পয়েন্ট অব সেল, হিসাব, কুরিয়ার ও মেটা ট্র্যাকিং, ২৩টি মডিউলসহ এক অ্যাডমিন প্যানেলে।',
            ],
            'body' => [
                'en' => '<p>Auxtech Ecommerce powers the online stores in our portfolio, from fashion and electronics to grocery and general retail. Each store gets its own design and domain, while the platform underneath is proven, maintained and improved continuously.</p><h2>Why brands choose it</h2><ul><li>Built for cash on delivery, courier booking and order confirmation.</li><li>Online store and showroom point of sale share the same stock.</li><li>Incomplete checkouts are saved so your team can follow up.</li><li>Meta Pixel, Google Tag Manager and server-side Conversions API come ready.</li><li>Bangla and English, mobile first.</li></ul>',
            ],
        ],
        [
            'slug' => 'reseller-platform',
            'icon' => 'network',
            'flagship' => false,
            'demo_url' => 'https://resellerv2.auxtechsoftware.com',
            'external_url' => null,
            'name' => [
                'en' => 'Reseller and Dropshipping Platform',
                'bn' => 'রিসেলার ও ড্রপশিপিং প্ল্যাটফর্ম',
            ],
            'tagline' => [
                'en' => 'Grow through resellers without losing control.',
                'bn' => 'নিয়ন্ত্রণ না হারিয়ে রিসেলারদের মাধ্যমে বড় হোন।',
            ],
            'summary' => [
                'en' => 'A B2B platform where resellers sell from your catalogue at their own price while you handle stock, packaging and delivery, with packages, white-label parcels and payouts.',
                'bn' => 'একটি B2B প্ল্যাটফর্ম, যেখানে রিসেলাররা নিজের দামে আপনার ক্যাটালগ থেকে বিক্রি করে আর আপনি সামলান স্টক, প্যাকেজিং ও ডেলিভারি; সাথে প্যাকেজ, হোয়াইট-লেবেল পার্সেল ও পে-আউট।',
            ],
            'body' => [
                'en' => '<p>Rong Torongo, Astha Reseller and E Hub run their reseller networks on this platform. Resellers register, choose a package, share products and submit orders; the business handles sourcing, quality checks, packaging and cash on delivery across Bangladesh.</p><ul><li>Reseller registration, packages and dashboards.</li><li>Catalogue with reusable product media.</li><li>Orders at the reseller\'s own price, with white-label parcels and cash memos.</li><li>Earnings, payouts and support tickets.</li></ul>',
            ],
        ],
        [
            'slug' => 'lms',
            'icon' => 'graduation-cap',
            'flagship' => false,
            'demo_url' => null,
            'external_url' => null,
            'name' => [
                'en' => 'Learning Management System',
                'bn' => 'লার্নিং ম্যানেজমেন্ট সিস্টেম',
            ],
            'tagline' => [
                'en' => 'Sell and deliver courses, books and exams online.',
                'bn' => 'অনলাইনে কোর্স, বই ও পরীক্ষা বিক্রি ও পরিচালনা করুন।',
            ],
            'summary' => [
                'en' => 'A learning platform with course catalogues, student and trainer portals, exams, question banks and certificates, used by E7 Training and Paperless Education.',
                'bn' => 'কোর্স ক্যাটালগ, স্টুডেন্ট ও ট্রেইনার পোর্টাল, পরীক্ষা, প্রশ্নব্যাংক ও সার্টিফিকেটসহ একটি লার্নিং প্ল্যাটফর্ম, যা ব্যবহার করে E7 Training ও Paperless Education।',
            ],
            'body' => [
                'en' => '<p>Training companies and educators use our learning platform to publish courses, digital books and exams, enrol students and track their progress.</p><ul><li>Course catalogue, search and categories.</li><li>Student and trainer portals.</li><li>Exams, model tests and question banks.</li><li>Packages, subscriptions, free trials and certificates.</li></ul>',
            ],
        ],
        [
            'slug' => 'corporate-website',
            'icon' => 'building-2',
            'flagship' => false,
            'demo_url' => null,
            'external_url' => null,
            'name' => [
                'en' => 'Corporate Website',
                'bn' => 'কর্পোরেট ওয়েবসাইট',
            ],
            'tagline' => [
                'en' => 'A professional web presence, fast to launch and easy to run.',
                'bn' => 'একটি পেশাদার ওয়েব উপস্থিতি, দ্রুত চালু ও সহজে পরিচালনাযোগ্য।',
            ],
            'summary' => [
                'en' => 'A company website that presents your services, projects and contact details clearly, with an admin panel your team can update.',
                'bn' => 'একটি কর্পোরেট ওয়েবসাইট, যা আপনার সার্ভিস, প্রজেক্ট ও যোগাযোগের তথ্য পরিষ্কারভাবে তুলে ধরে, আর টিম নিজেই আপডেট করতে পারে।',
            ],
            'body' => [
                'en' => '<p>Corporate websites by Auxtech are fast, search-friendly and built to generate enquiries, as for Water Technology BD Limited and SS Typing &amp; Travels.</p>',
            ],
        ],
        [
            'slug' => 'custom-software',
            'icon' => 'code-xml',
            'flagship' => false,
            'demo_url' => null,
            'external_url' => null,
            'name' => [
                'en' => 'Custom Software',
                'bn' => 'কাস্টম সফটওয়্যার',
            ],
            'tagline' => [
                'en' => 'When your process is unique, your software should be too.',
                'bn' => 'আপনার প্রক্রিয়া যদি অনন্য হয়, সফটওয়্যারও তেমন হওয়া উচিত।',
            ],
            'summary' => [
                'en' => 'Software designed around your process, from tax calculators and portals to multi-role platforms like CleanBDX.',
                'bn' => 'আপনার প্রক্রিয়া অনুযায়ী ডিজাইন করা সফটওয়্যার, ট্যাক্স ক্যালকুলেটর ও পোর্টাল থেকে CleanBDX-এর মতো মাল্টি-রোল প্ল্যাটফর্ম পর্যন্ত।',
            ],
            'body' => [
                'en' => '<p>We build custom software when existing products do not fit: CleanBDX\'s digital waste management platform, Greenland\'s income tax calculator and more.</p>',
            ],
        ],
        [
            'slug' => 'auxify',
            'icon' => 'cloud',
            'flagship' => false,
            'demo_url' => null,
            'external_url' => 'https://auxify.store',
            'name' => [
                'en' => 'Auxify',
                'bn' => 'Auxify',
            ],
            'tagline' => [
                'en' => 'Our self-service store builder.',
                'bn' => 'আমাদের সেলফ-সার্ভিস স্টোর বিল্ডার।',
            ],
            'summary' => [
                'en' => 'Open an online shop in minutes with a free address, SSL and ready-made designs: no coding and no server, in Bangla or English.',
                'bn' => 'ফ্রি ঠিকানা, SSL ও রেডিমেড ডিজাইনসহ কয়েক মিনিটে অনলাইন শপ খুলুন: কোনো কোডিং বা সার্ভার ছাড়াই, বাংলা বা ইংরেজিতে।',
            ],
            'body' => [
                'en' => '<p>Auxify is Auxtech\'s software-as-a-service store builder for smaller businesses, with products, orders, stock, discounts, customers, reports and storefront designs in one dashboard.</p>',
            ],
        ],
    ],
    'industries' => [
        [
            'slug' => 'fashion',
            'name' => [
                'en' => 'Fashion & Apparel',
                'bn' => 'ফ্যাশন ও পোশাক',
            ],
            'icon' => 'shirt',
            'description' => [
                'en' => 'Online stores for clothing, footwear and accessories brands, with collections, variants, pre-orders and campaigns.',
                'bn' => 'পোশাক, জুতা ও অ্যাকসেসরিজ ব্র্যান্ডের অনলাইন স্টোর, কালেকশন, ভ্যারিয়েন্ট, প্রি-অর্ডার ও ক্যাম্পেইনসহ।',
            ],
        ],
        [
            'slug' => 'gadgets',
            'name' => [
                'en' => 'Gadgets & Electronics',
                'bn' => 'গ্যাজেট ও ইলেকট্রনিক্স',
            ],
            'icon' => 'smartphone',
            'description' => [
                'en' => 'Stores for phones, laptops, accessories and smart devices, with comparison, wishlists, flash deals and pre-orders.',
                'bn' => 'ফোন, ল্যাপটপ, অ্যাকসেসরিজ ও স্মার্ট ডিভাইসের স্টোর, কম্পেয়ার, উইশলিস্ট, ফ্ল্যাশ ডিল ও প্রি-অর্ডারসহ।',
            ],
        ],
        [
            'slug' => 'food-grocery',
            'name' => [
                'en' => 'Food & Grocery',
                'bn' => 'খাদ্য ও গ্রোসারি',
            ],
            'icon' => 'shopping-basket',
            'description' => [
                'en' => 'Grocery, dairy and natural food stores with fast reordering and delivery-area messaging.',
                'bn' => 'দ্রুত রি-অর্ডার ও ডেলিভারি এলাকার তথ্যসহ গ্রোসারি, ডেইরি ও প্রাকৃতিক খাবারের স্টোর।',
            ],
        ],
        [
            'slug' => 'general-retail',
            'name' => [
                'en' => 'General Retail',
                'bn' => 'জেনারেল রিটেইল',
            ],
            'icon' => 'store',
            'description' => [
                'en' => 'Multi-category shops and corporate suppliers with deep catalogues, brands and offers.',
                'bn' => 'গভীর ক্যাটালগ, ব্র্যান্ড ও অফারসহ মাল্টি-ক্যাটাগরি শপ ও কর্পোরেট সরবরাহকারী।',
            ],
        ],
        [
            'slug' => 'reselling',
            'name' => [
                'en' => 'Reselling & Dropshipping',
                'bn' => 'রিসেলিং ও ড্রপশিপিং',
            ],
            'icon' => 'network',
            'description' => [
                'en' => 'B2B platforms where resellers sell a shared catalogue while the business handles stock and delivery.',
                'bn' => 'B2B প্ল্যাটফর্ম, যেখানে রিসেলাররা একটি ক্যাটালগ বিক্রি করে আর স্টক ও ডেলিভারি সামলায় প্রতিষ্ঠান।',
            ],
        ],
        [
            'slug' => 'education',
            'name' => [
                'en' => 'Education',
                'bn' => 'শিক্ষা',
            ],
            'icon' => 'graduation-cap',
            'description' => [
                'en' => 'Learning platforms for courses, digital books, exams and certificates.',
                'bn' => 'কোর্স, ডিজিটাল বই, পরীক্ষা ও সার্টিফিকেটের জন্য লার্নিং প্ল্যাটফর্ম।',
            ],
        ],
        [
            'slug' => 'travel',
            'name' => [
                'en' => 'Travel',
                'bn' => 'ট্রাভেল',
            ],
            'icon' => 'plane',
            'description' => [
                'en' => 'Websites for travel, pilgrimage and visa service companies.',
                'bn' => 'ট্রাভেল, তীর্থযাত্রা ও ভিসা সার্ভিস প্রতিষ্ঠানের ওয়েবসাইট।',
            ],
        ],
        [
            'slug' => 'corporate',
            'name' => [
                'en' => 'Corporate & Industrial',
                'bn' => 'কর্পোরেট ও ইন্ডাস্ট্রিয়াল',
            ],
            'icon' => 'building-2',
            'description' => [
                'en' => 'Corporate websites that present engineering and industrial capabilities to business buyers.',
                'bn' => 'ব্যবসায়িক ক্রেতাদের কাছে ইঞ্জিনিয়ারিং ও শিল্প সক্ষমতা তুলে ধরা কর্পোরেট ওয়েবসাইট।',
            ],
        ],
        [
            'slug' => 'finance',
            'name' => [
                'en' => 'Finance & Compliance',
                'bn' => 'ফাইন্যান্স ও কমপ্লায়েন্স',
            ],
            'icon' => 'calculator',
            'description' => [
                'en' => 'Calculators and compliance tools that turn manual paperwork into a guided online flow.',
                'bn' => 'ক্যালকুলেটর ও কমপ্লায়েন্স টুল, যা কাগুজে কাজকে একটি সহজ অনলাইন প্রক্রিয়ায় রূপ দেয়।',
            ],
        ],
        [
            'slug' => 'environment',
            'name' => [
                'en' => 'Environment & Civic',
                'bn' => 'পরিবেশ ও নাগরিক সেবা',
            ],
            'icon' => 'recycle',
            'description' => [
                'en' => 'Platforms that connect citizens, service providers and field teams for public good.',
                'bn' => 'জনকল্যাণে নাগরিক, সেবাদাতা ও মাঠকর্মীদের যুক্ত করা প্ল্যাটফর্ম।',
            ],
        ],
        [
            'slug' => 'saas',
            'name' => [
                'en' => 'SaaS',
                'bn' => 'সাস',
            ],
            'icon' => 'cloud',
            'description' => [
                'en' => 'Subscription software products with self-service sign-up and plans.',
                'bn' => 'সেলফ-সার্ভিস সাইন-আপ ও প্ল্যানসহ সাবস্ক্রিপশন সফটওয়্যার প্রোডাক্ট।',
            ],
        ],
        [
            'slug' => 'automotive',
            'name' => ['en' => 'Automotive & Car Care', 'bn' => 'অটোমোটিভ ও কার কেয়ার'],
            'icon' => 'car',
            'description' => [
                'en' => 'Booking systems and websites for car wash, detailing and vehicle service businesses, with online slots, payments and live job tracking.',
                'bn' => 'কার ওয়াশ, ডিটেইলিং ও গাড়ি সার্ভিস ব্যবসার জন্য বুকিং সিস্টেম ও ওয়েবসাইট, অনলাইন স্লট, পেমেন্ট ও লাইভ জব ট্র্যাকিংসহ।',
            ],
        ],
    ],

    'technologies' => [
        [
            'slug' => 'php',
            'name' => 'PHP',
            'category' => 'Backend',
        ],
        [
            'slug' => 'laravel',
            'name' => 'Laravel',
            'category' => 'Backend',
        ],
        [
            'slug' => 'vuejs',
            'name' => 'Vue.js',
            'category' => 'Frontend',
        ],
        [
            'slug' => 'inertiajs',
            'name' => 'Inertia.js',
            'category' => 'Frontend',
        ],
        [
            'slug' => 'nuxt',
            'name' => 'Nuxt',
            'category' => 'Frontend',
        ],
        [
            'slug' => 'react',
            'name' => 'React',
            'category' => 'Frontend',
        ],
        [
            'slug' => 'nextjs',
            'name' => 'Next.js',
            'category' => 'Frontend',
        ],
    ],
    'faqs' => [
        [
            'question' => [
                'en' => 'What does Auxtech do?',
                'bn' => 'Auxtech কী করে?',
            ],
            'answer' => [
                'en' => '<p>Auxtech is a software development company in Dhaka. We design, build and support custom software, web applications, e-commerce platforms and mobile apps, and offer digital marketing to help clients grow what we build.</p>',
                'bn' => '<p>Auxtech ঢাকার একটি সফটওয়্যার ডেভেলপমেন্ট কোম্পানি। আমরা কাস্টম সফটওয়্যার, ওয়েব অ্যাপ্লিকেশন, ই-কমার্স প্ল্যাটফর্ম ও মোবাইল অ্যাপ ডিজাইন, তৈরি ও সাপোর্ট করি, আর তৈরি করা সফটওয়্যার বড় করতে ডিজিটাল মার্কেটিং সেবা দিই।</p>',
            ],
        ],
        [
            'question' => [
                'en' => 'How do we start a project?',
                'bn' => 'প্রজেক্ট কীভাবে শুরু করব?',
            ],
            'answer' => [
                'en' => '<p>Message us on WhatsApp or use the estimate form. We discuss your goals, agree on the scope in a short discovery phase, and send a clear plan with timeline and cost before any development starts.</p>',
                'bn' => '<p>হোয়াটসঅ্যাপে মেসেজ দিন অথবা এস্টিমেট ফর্ম ব্যবহার করুন। আমরা আপনার লক্ষ্য নিয়ে আলোচনা করি, সংক্ষিপ্ত আলোচনা পর্বে স্কোপ ঠিক করি এবং ডেভেলপমেন্ট শুরুর আগেই সময় ও খরচসহ একটি পরিষ্কার পরিকল্পনা পাঠাই।</p>',
            ],
        ],
        [
            'question' => [
                'en' => 'How much does software or a website cost?',
                'bn' => 'সফটওয়্যার বা ওয়েবসাইটের খরচ কত?',
            ],
            'answer' => [
                'en' => '<p>It depends on the scope. Projects built on our existing platforms, such as Auxtech Ecommerce or the reseller platform, cost less and launch faster than software built from scratch. Answer four questions in the estimator or message us for a tailored quote.</p>',
                'bn' => '<p>এটি স্কোপের ওপর নির্ভর করে। Auxtech Ecommerce বা রিসেলার প্ল্যাটফর্মের মতো আমাদের বিদ্যমান প্ল্যাটফর্মে তৈরি প্রজেক্টের খরচ কম এবং শুরু থেকে তৈরি সফটওয়্যারের চেয়ে দ্রুত চালু হয়। এস্টিমেটরে চারটি প্রশ্নের উত্তর দিন অথবা মেসেজ দিয়ে কোটেশন নিন।</p>',
            ],
        ],
        [
            'question' => [
                'en' => 'How long does a project take?',
                'bn' => 'একটি প্রজেক্টে কত সময় লাগে?',
            ],
            'answer' => [
                'en' => '<p>A store or corporate website on one of our platforms can go live in a few weeks. Custom software is delivered in phases, with a working version early and improvements in short iterations. We agree the timeline with you during discovery.</p>',
                'bn' => '<p>আমাদের প্ল্যাটফর্মে একটি স্টোর বা কর্পোরেট ওয়েবসাইট কয়েক সপ্তাহে লাইভ হতে পারে। কাস্টম সফটওয়্যার ধাপে ধাপে ডেলিভারি হয়, শুরুতেই একটি চালু সংস্করণ, তারপর ছোট ছোট ধাপে উন্নয়ন। সময়সূচি আমরা আলোচনা পর্বে আপনার সাথে ঠিক করি।</p>',
            ],
        ],
        [
            'question' => [
                'en' => 'Which technologies do you use?',
                'bn' => 'আপনারা কোন প্রযুক্তি ব্যবহার করেন?',
            ],
            'answer' => [
                'en' => '<p>We choose what fits the project. Most of our platforms are built with PHP and Laravel with Vue.js, and we also build with Nuxt, React and Next.js for content-heavy and high-performance sites.</p>',
                'bn' => '<p>প্রজেক্টের উপযোগী প্রযুক্তি বেছে নিই। আমাদের বেশিরভাগ প্ল্যাটফর্ম পিএইচপি ও লারাভেলের সাথে ভিউ.জেএস দিয়ে তৈরি, আর কনটেন্ট-নির্ভর ও উচ্চ পারফরম্যান্সের সাইটের জন্য নাক্সট, রিঅ্যাক্ট ও নেক্সট.জেএস ব্যবহার করি।</p>',
            ],
        ],
        [
            'question' => [
                'en' => 'Do you provide support after launch?',
                'bn' => 'লঞ্চের পর কি সাপোর্ট দেন?',
            ],
            'answer' => [
                'en' => '<p>Yes. We offer maintenance, security updates, new features and growth marketing after launch, from the same team that built your software. Ask us about a support plan that fits your project.</p>',
                'bn' => '<p>হ্যাঁ। লঞ্চের পর রক্ষণাবেক্ষণ, সিকিউরিটি আপডেট, নতুন ফিচার ও গ্রোথ মার্কেটিং দিই, সেই টিমের কাছ থেকে যারা আপনার সফটওয়্যার তৈরি করেছে। আপনার প্রজেক্টের উপযোগী সাপোর্ট প্ল্যান সম্পর্কে জানতে চাইলে যোগাযোগ করুন।</p>',
            ],
        ],
        [
            'question' => [
                'en' => 'What is included in Auxtech Ecommerce?',
                'bn' => 'Auxtech Ecommerce-এ কী কী আছে?',
            ],
            'answer' => [
                'en' => '<p>Auxtech Ecommerce has 23 modules in four groups: selling (products, orders, point of sale, pre-orders, landing pages), fulfilment (inventory, courier integration, order tracking, incomplete orders), growth (campaigns, coupons, reviews, customers, Meta Pixel, GTM and Conversions API) and control (dashboard, accounting, employees, user access and settings).</p>',
                'bn' => '<p>Auxtech Ecommerce-এ চারটি গ্রুপে ২৩টি মডিউল আছে: বিক্রি (প্রোডাক্ট, অর্ডার, পয়েন্ট অব সেল, প্রি-অর্ডার, ল্যান্ডিং পেজ), ডেলিভারি (ইনভেন্টরি, কুরিয়ার ইন্টিগ্রেশন, অর্ডার ট্র্যাকিং, অসম্পূর্ণ অর্ডার), গ্রোথ (ক্যাম্পেইন, কুপন, রিভিউ, কাস্টমার, মেটা পিক্সেল, জিটিএম ও কনভার্সন এপিআই) এবং নিয়ন্ত্রণ (ড্যাশবোর্ড, হিসাব, এমপ্লয়ি, ইউজার অ্যাক্সেস ও সেটিংস)।</p>',
            ],
        ],
        [
            'question' => [
                'en' => 'Can I see live examples before deciding?',
                'bn' => 'সিদ্ধান্ত নেওয়ার আগে কি লাইভ উদাহরণ দেখতে পারি?',
            ],
            'answer' => [
                'en' => '<p>Yes. Every project in our portfolio links to the live site, and we have demo stores and a demo reseller platform you can try. Message us on WhatsApp and we will send the examples closest to your business.</p>',
                'bn' => '<p>হ্যাঁ। আমাদের পোর্টফোলিওর প্রতিটি প্রজেক্টে লাইভ সাইটের লিংক আছে, আর ডেমো স্টোর ও ডেমো রিসেলার প্ল্যাটফর্ম ঘুরে দেখতে পারেন। হোয়াটসঅ্যাপে মেসেজ দিন, আপনার ব্যবসার সবচেয়ে কাছাকাছি উদাহরণগুলো পাঠিয়ে দেব।</p>',
            ],
        ],
    ],

    // Engagement packages shown on the pricing page and the home page. No
    // prices are seeded: each shows "Custom quote" until a price is entered.
    'pricing_plans' => [
        [
            'name' => ['en' => 'Fixed-scope project', 'bn' => 'নির্দিষ্ট স্কোপের প্রজেক্ট'],
            'description' => ['en' => 'A website, store or application with a scope, timeline and price agreed before work starts.', 'bn' => 'ওয়েবসাইট, অনলাইন স্টোর বা অ্যাপ্লিকেশন, যার স্কোপ, সময়সূচি ও মূল্য কাজ শুরুর আগেই চূড়ান্ত হয়।'],
            'popular' => false,
            'features' => [
                ['en' => 'Discovery workshop and a written scope', 'bn' => 'ডিসকভারি ওয়ার্কশপ ও লিখিত স্কোপ'],
                ['en' => 'UI/UX design with review rounds', 'bn' => 'রিভিউসহ UI/UX ডিজাইন'],
                ['en' => 'Development on a staging site you can test', 'bn' => 'স্টেজিং সাইটে ডেভেলপমেন্ট, যা আপনি নিজে পরীক্ষা করতে পারবেন'],
                ['en' => 'Admin panel for your team', 'bn' => 'আপনার টিমের জন্য অ্যাডমিন প্যানেল'],
                ['en' => 'Launch, data migration and training', 'bn' => 'লঞ্চ, ডেটা মাইগ্রেশন ও ট্রেনিং'],
                ['en' => 'Post-launch fixes and full handover', 'bn' => 'লঞ্চ-পরবর্তী সমাধান ও সম্পূর্ণ হস্তান্তর'],
            ],
        ],
        [
            'name' => ['en' => 'Platform package', 'bn' => 'প্ল্যাটফর্ম প্যাকেজ'],
            'description' => ['en' => 'Auxtech Ecommerce, the reseller platform or the LMS, set up on your brand and ready to sell.', 'bn' => 'Auxtech Ecommerce, রিসেলার প্ল্যাটফর্ম বা LMS, আপনার ব্র্যান্ডে সাজানো এবং বিক্রির জন্য প্রস্তুত।'],
            'popular' => true,
            'features' => [
                ['en' => 'Your domain, branding and content', 'bn' => 'আপনার ডোমেইন, ব্র্যান্ডিং ও কনটেন্ট'],
                ['en' => 'Admin panel with the modules you need', 'bn' => 'প্রয়োজনীয় মডিউলসহ অ্যাডমিন প্যানেল'],
                ['en' => 'Courier, payment and SMS integrations', 'bn' => 'কুরিয়ার, পেমেন্ট ও SMS ইন্টিগ্রেশন'],
                ['en' => 'Meta Pixel and Conversions API set up', 'bn' => 'Meta Pixel ও Conversions API সেটআপ'],
                ['en' => 'Product upload guidance and staff training', 'bn' => 'প্রোডাক্ট আপলোড গাইডেন্স ও স্টাফ ট্রেনিং'],
                ['en' => 'Launch support and updates', 'bn' => 'লঞ্চ সাপোর্ট ও আপডেট'],
            ],
        ],
        [
            'name' => ['en' => 'Ongoing support and growth', 'bn' => 'চলমান সাপোর্ট ও গ্রোথ'],
            'description' => ['en' => 'A monthly engagement for new features, maintenance and marketing after launch.', 'bn' => 'লঞ্চের পর নতুন ফিচার, রক্ষণাবেক্ষণ ও মার্কেটিংয়ের জন্য মাসিক এনগেজমেন্ট।'],
            'popular' => false,
            'features' => [
                ['en' => 'New features and improvements every month', 'bn' => 'প্রতি মাসে নতুন ফিচার ও উন্নয়ন'],
                ['en' => 'Security updates and backups', 'bn' => 'সিকিউরিটি আপডেট ও ব্যাকআপ'],
                ['en' => 'Performance and uptime monitoring', 'bn' => 'পারফরম্যান্স ও আপটাইম মনিটরিং'],
                ['en' => 'SEO and Meta ads management', 'bn' => 'SEO ও Meta বিজ্ঞাপন ব্যবস্থাপনা'],
                ['en' => 'Monthly report on traffic and sales', 'bn' => 'ট্রাফিক ও বিক্রির মাসিক রিপোর্ট'],
                ['en' => 'Priority support on WhatsApp', 'bn' => 'হোয়াটসঅ্যাপে অগ্রাধিকার সাপোর্ট'],
            ],
        ],
    ],
];
