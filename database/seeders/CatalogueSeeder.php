<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\Industry;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Database\Seeder;

/** Industries, services, solutions and the 23 Auxtech Ecommerce modules. */
class CatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $this->industries();
        $this->services();
        $this->solutions();
    }

    private function industries(): void
    {
        $industries = [
            'fashion' => ['Fashion & Apparel', 'ফ্যাশন ও পোশাক', 'shirt'],
            'gadgets' => ['Gadgets & Electronics', 'গ্যাজেট ও ইলেকট্রনিক্স', 'smartphone'],
            'food-grocery' => ['Food & Grocery', 'খাদ্য ও গ্রোসারি', 'shopping-basket'],
            'general-retail' => ['General Retail', 'জেনারেল রিটেইল', 'store'],
            'reselling' => ['Reselling & Dropshipping', 'রিসেলিং ও ড্রপশিপিং', 'network'],
            'education' => ['Education', 'শিক্ষা', 'graduation-cap'],
            'travel' => ['Travel', 'ট্রাভেল', 'plane'],
            'corporate' => ['Corporate & Industrial', 'কর্পোরেট ও ইন্ডাস্ট্রিয়াল', 'building-2'],
            'finance' => ['Finance & Compliance', 'ফাইন্যান্স ও কমপ্লায়েন্স', 'calculator'],
            'environment' => ['Environment & Civic', 'পরিবেশ ও নাগরিক সেবা', 'recycle'],
            'saas' => ['SaaS', 'সাস', 'cloud'],
        ];

        foreach ($industries as $slug => [$en, $bn, $icon]) {
            Industry::query()->updateOrCreate(['slug' => $slug], ['name' => ['en' => $en, 'bn' => $bn], 'icon' => $icon]);
        }
    }

    private function services(): void
    {
        $services = [
            ['website-development', null, 'Website Development', 'ওয়েবসাইট ডেভেলপমেন্ট', 'globe', true,
                'E-commerce stores, business websites and custom web applications built for Bangladeshi businesses.'],
            ['ecommerce-development', 'website-development', 'E-commerce Development', 'ই-কমার্স ডেভেলপমেন্ট', 'shopping-cart', false,
                'Online stores with order management, inventory, POS, courier integration and Meta tracking built in.'],
            ['custom-web-application', 'website-development', 'Custom Web Applications', 'কাস্টম ওয়েব অ্যাপ্লিকেশন', 'app-window', false,
                'Purpose-built platforms such as reseller systems, learning platforms and business tools.'],
            ['app-development', null, 'App Development', 'অ্যাপ ডেভেলপমেন্ট', 'smartphone', true,
                'Mobile apps for Android and iOS that work with your website and back office.'],
            ['digital-marketing', null, 'Digital Marketing', 'ডিজিটাল মার্কেটিং', 'megaphone', true,
                'Marketing that brings buyers to your store: Meta ads, search engine optimisation and conversion tracking.'],
            ['seo', 'digital-marketing', 'Search Engine Optimisation', 'সার্চ ইঞ্জিন অপটিমাইজেশন', 'search', false,
                'Technical and content optimisation so customers find your business on Google.'],
            ['meta-ads', 'digital-marketing', 'Facebook & Instagram Ads', 'ফেসবুক ও ইনস্টাগ্রাম অ্যাডস', 'target', false,
                'Meta ad campaigns planned, run and measured for online sales.'],
            ['meta-pixel-capi', 'digital-marketing', 'Meta Pixel & Conversions API Setup', 'মেটা পিক্সেল ও কনভার্সন এপিআই সেটআপ', 'activity', false,
                'Browser and server-side tracking so Meta sees every order and your ads optimise on real sales.'],
        ];

        foreach ($services as $order => [$slug, $parent, $en, $bn, $icon, $featured, $excerpt]) {
            Service::query()->updateOrCreate(['slug' => $slug], [
                'parent_id' => $parent ? Service::query()->where('slug', $parent)->value('id') : null,
                'title' => ['en' => $en, 'bn' => $bn],
                'excerpt' => ['en' => $excerpt],
                'icon' => $icon,
                'is_featured' => $featured,
                'status' => PublishStatus::Published,
                'sort_order' => $order + 1,
            ]);
        }
    }

    private function solutions(): void
    {
        $solutions = [
            ['ecommerce', 'Auxtech Ecommerce', 'Auxtech ই-কমার্স', 'shopping-bag', true, 'https://kalki.auxdemo.com', null,
                'A complete e-commerce system for Bangladeshi brands: storefront, orders, inventory, POS, accounting, courier and Meta tracking in one admin panel.'],
            ['reseller-platform', 'Reseller & Dropshipping Platform', 'রিসেলার ও ড্রপশিপিং প্ল্যাটফর্ম', 'network', false, 'https://resellerv2.auxtechsoftware.com', null,
                'A B2B platform where resellers sell from your catalogue while you handle stock, packaging and delivery.'],
            ['lms', 'Learning Management System', 'লার্নিং ম্যানেজমেন্ট সিস্টেম', 'graduation-cap', false, null, null,
                'Sell and deliver online courses with separate student and trainer portals.'],
            ['corporate-website', 'Corporate Website', 'কর্পোরেট ওয়েবসাইট', 'building-2', false, null, null,
                'A professional company website that presents your services, projects and contact details.'],
            ['custom-software', 'Custom Software', 'কাস্টম সফটওয়্যার', 'code-2', false, null, null,
                'Software designed around your process, from calculators and portals to multi-role platforms.'],
            ['auxify', 'Auxify', 'Auxify', 'cloud', false, null, 'https://auxify.store',
                'Auxtech\'s own store builder: open an online shop in minutes with ready-made themes, no coding and no server.'],
        ];

        foreach ($solutions as $order => [$slug, $en, $bn, $icon, $flagship, $demo, $external, $summary]) {
            Solution::query()->updateOrCreate(['slug' => $slug], [
                'name' => ['en' => $en, 'bn' => $bn],
                'summary' => ['en' => $summary],
                'icon' => $icon,
                'is_flagship' => $flagship,
                'demo_url' => $demo,
                'external_url' => $external,
                'status' => PublishStatus::Published,
                'sort_order' => $order + 1,
            ]);
        }

        $this->ecommerceModules(Solution::query()->where('slug', 'ecommerce')->firstOrFail());
    }

    /** The 23 modules of Auxtech Ecommerce, in four groups. */
    private function ecommerceModules(Solution $solution): void
    {
        $groups = [
            'sell' => ['Sell', 'বিক্রি', [
                ['product-management', 'Product Management', 'প্রোডাক্ট ম্যানেজমেন্ট', 'package'],
                ['order-management', 'Order Management', 'অর্ডার ম্যানেজমেন্ট', 'clipboard-list'],
                ['point-of-sale', 'Point of Sale (POS)', 'পয়েন্ট অব সেল (POS)', 'monitor'],
                ['pre-order-management', 'Pre-Order Management', 'প্রি-অর্ডার ম্যানেজমেন্ট', 'calendar-clock'],
                ['product-request-management', 'Product Request Management', 'প্রোডাক্ট রিকোয়েস্ট ম্যানেজমেন্ট', 'message-square-plus'],
                ['landing-page-management', 'Landing Page Management', 'ল্যান্ডিং পেজ ম্যানেজমেন্ট', 'layout-template'],
                ['website-page-management', 'Website Page Management', 'ওয়েবসাইট পেজ ম্যানেজমেন্ট', 'file-text'],
            ]],
            'fulfil' => ['Fulfil', 'ডেলিভারি', [
                ['inventory-management', 'Inventory Management', 'ইনভেন্টরি ম্যানেজমেন্ট', 'boxes'],
                ['courier-integration', 'Courier Integration', 'কুরিয়ার ইন্টিগ্রেশন', 'truck'],
                ['order-tracking', 'Order Tracking', 'অর্ডার ট্র্যাকিং', 'map-pin'],
                ['incomplete-order-management', 'Incomplete Order Management', 'অসম্পূর্ণ অর্ডার ম্যানেজমেন্ট', 'shopping-cart'],
            ]],
            'grow' => ['Grow', 'গ্রোথ', [
                ['campaign-management', 'Campaign Management', 'ক্যাম্পেইন ম্যানেজমেন্ট', 'megaphone'],
                ['coupon-management', 'Coupon Management', 'কুপন ম্যানেজমেন্ট', 'ticket-percent'],
                ['reviews-ratings', 'Reviews & Ratings', 'রিভিউ ও রেটিং', 'star'],
                ['customer-management', 'Customer Management', 'কাস্টমার ম্যানেজমেন্ট', 'users'],
                ['meta-pixel-gtm', 'Meta Pixel & Google Tag Manager (GTM)', 'মেটা পিক্সেল ও গুগল ট্যাগ ম্যানেজার (GTM)', 'activity'],
                ['conversions-api', 'Conversions API (CAPI)', 'কনভার্সন এপিআই (CAPI)', 'server'],
            ]],
            'control' => ['Control', 'নিয়ন্ত্রণ', [
                ['dashboard', 'Dashboard', 'ড্যাশবোর্ড', 'layout-dashboard'],
                ['accounting-management', 'Accounting Management', 'অ্যাকাউন্টিং ম্যানেজমেন্ট', 'wallet'],
                ['employee-management', 'Employee Management', 'এমপ্লয়ি ম্যানেজমেন্ট', 'id-card'],
                ['user-access-management', 'User & Access Management', 'ইউজার ও অ্যাক্সেস ম্যানেজমেন্ট', 'shield-check'],
                ['api-settings', 'API Settings', 'এপিআই সেটিংস', 'plug'],
                ['system-settings', 'System Settings', 'সিস্টেম সেটিংস', 'settings'],
            ]],
        ];

        $groupOrder = 0;

        foreach ($groups as $key => [$en, $bn, $features]) {
            $group = $solution->featureGroups()->updateOrCreate(['key' => $key], [
                'name' => ['en' => $en, 'bn' => $bn],
                'sort_order' => ++$groupOrder,
            ]);

            foreach ($features as $order => [$slug, $title, $titleBn, $icon]) {
                $group->features()->updateOrCreate(['slug' => $slug], [
                    'title' => ['en' => $title, 'bn' => $titleBn],
                    'icon' => $icon,
                    'sort_order' => $order + 1,
                ]);
            }
        }
    }
}
