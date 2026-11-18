<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\Industry;
use App\Models\Service;
use App\Models\Solution;
use App\Models\Technology;
use Database\Seeders\Concerns\SeedsContent;
use Illuminate\Database\Seeder;

/**
 * Industries, services, platforms (solutions with the 23 Auxtech Ecommerce
 * modules) and technologies. Text lives in database/seeders/data/catalogue.php.
 */
class CatalogueSeeder extends Seeder
{
    use SeedsContent;

    public function run(): void
    {
        $data = require __DIR__.'/data/catalogue.php';

        foreach ($data['industries'] as $industry) {
            $this->seedRecord(Industry::class, ['slug' => $industry['slug']], [
                'name' => $industry['name'], 'description' => $industry['description'], 'icon' => $industry['icon'],
            ]);
        }

        foreach ($data['services'] as $order => $service) {
            $this->seedRecord(Service::class, ['slug' => $service['slug']], [
                'parent_id' => $service['parent'] ? Service::query()->where('slug', $service['parent'])->value('id') : null,
                'title' => $service['title'],
                'tagline' => $service['tagline'],
                'excerpt' => $service['excerpt'],
                'body' => $service['body'],
                'features' => $service['features'],
                'process_steps' => $service['process_steps'],
                'icon' => $service['icon'],
                'is_featured' => $service['featured'],
                'status' => PublishStatus::Published,
                'sort_order' => $order + 1,
            ]);
        }

        // Services from an earlier version of the seed data that were renamed.
        Service::query()->whereIn('slug', $data['retired_services'])->get()->each->forceDelete();

        foreach ($data['solutions'] as $order => $solution) {
            $this->seedRecord(Solution::class, ['slug' => $solution['slug']], [
                'name' => $solution['name'],
                'tagline' => $solution['tagline'],
                'summary' => $solution['summary'],
                'body' => $solution['body'],
                'icon' => $solution['icon'],
                'is_flagship' => $solution['flagship'],
                'demo_url' => $solution['demo_url'],
                'external_url' => $solution['external_url'],
                'status' => PublishStatus::Published,
                'sort_order' => $order + 1,
            ]);
        }

        $this->ecommerceModules(Solution::query()->where('slug', 'ecommerce')->firstOrFail());

        foreach ($data['technologies'] as $order => $technology) {
            $this->seedRecord(Technology::class, ['slug' => $technology['slug']], [
                'name' => $technology['name'], 'category' => $technology['category'], 'sort_order' => $order + 1,
            ]);
        }
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
