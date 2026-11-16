<?php

namespace Database\Seeders;

use App\Enums\ProjectType;
use App\Enums\PublishStatus;
use App\Models\Client;
use App\Models\Industry;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Database\Seeder;

/**
 * The 32 projects audited in docs/00-RESEARCH.md. Only facts observed on
 * the live sites are seeded; case-study text, results and testimonials
 * are left for Auxtech to write in the admin panel.
 */
class PortfolioSeeder extends Seeder
{
    /** slug, client, URL, industry, solution, type, featured, one-line description */
    private const PROJECTS = [
        ['fablixbd', 'Fablix', 'https://fablixbd.com', 'fashion', 'ecommerce', 'live', true, 'Online store for a men\'s wear brand: hoodies, shirts and baggy jeans.'],
        ['ventirebd', 'Ventire', 'https://ventirebd.com', 'fashion', 'ecommerce', 'live', false, 'Online store for men\'s casual wear, combo sets and offers.'],
        ['signaturemartbd', 'Signature Mart BD', 'https://signaturemartbd.com', 'fashion', 'ecommerce', 'live', false, 'Online store for men\'s winter collection, polo shirts and panjabi.'],
        ['queensclubbd', 'Queens Club BD', 'https://queensclubbd.com', 'fashion', 'ecommerce', 'live', false, 'Fashion store for women, teenagers and kids, with wholesale ordering.'],
        ['nokshacraft', 'Noksha Craft', 'https://nokshacraft.com', 'fashion', 'ecommerce', 'live', false, 'Store for women\'s dresses, bedsheets and traditional Nokshi Katha.'],
        ['weeshopbd', 'WeeShop BD', 'https://weeshopbd.com', 'fashion', 'ecommerce', 'live', false, 'Online store for leather shoes and watches.'],
        ['kalki', 'Kalki', 'https://kalki.auxdemo.com', 'fashion', 'ecommerce', 'demo', false, 'Demo store with a full family fashion catalogue: women, men, kids, newborn and footwear.'],
        ['thepatchee', 'Jamilooms', 'https://thepatchee.auxtechsoftware.com', 'fashion', 'ecommerce', 'demo', false, 'Pre-launch store for T-shirts, bags, caps and shoes.'],
        ['dhakaculture', 'Dhaka Culture', 'https://dhakaculture.com', 'fashion', 'ecommerce', 'live', true, 'Streetwear brand store with semi-drop T-shirts and pre-orders.'],
        ['lavieworld', 'La Vie World', 'https://lavieworld.auxtechsoftware.com', 'fashion', 'ecommerce', 'demo', false, 'Pre-launch store for handbags, sling bags and perfumes with a watch-and-shop section.'],
        ['technmorebd', 'Tech N More', 'https://technmorebd.com', 'gadgets', 'ecommerce', 'live', false, 'Phone accessories store organised by brand, with pre-orders.'],
        ['gearzen', 'Gearzen', 'https://gearzen.com.bd', 'gadgets', 'ecommerce', 'live', true, 'Gadget store for phones, MacBooks, cameras and drones with product compare and wishlist.'],
        ['unigear', 'UniGear', 'https://unigear.com.bd', 'gadgets', 'ecommerce', 'live', false, 'Store for speakers, tripods, microphones and smart watches.'],
        ['gadgetpanelbd', 'Gadget Panel BD', 'https://gadgetpanelbd.com', 'gadgets', 'ecommerce', 'live', false, 'Mobile accessories and wearables store with pre-order and free pickup.'],
        ['bottolabazar', 'Bottola Bazar', 'https://bottolabazar.com', 'general-retail', 'ecommerce', 'live', false, 'Multi-category online shop with order tracking and a complaint desk.'],
        ['greenlandmarketingcompany', 'Greenland Marketing Company', 'https://greenlandmarketingcompany.com', 'general-retail', 'ecommerce', 'live', false, 'Retail and corporate purchasing store with a blog and weekly discounts.'],
        ['bikolpoo', 'Bikolpoo', 'https://bikolpoo.com', 'general-retail', 'ecommerce', 'live', false, 'Online shop for home appliances, home decor and skin care.'],
        ['cartexbd', 'Cartex', 'https://cartexbd.com', 'general-retail', 'ecommerce', 'live', false, 'Online shop for electronics, wellness products and clothing, with pre-orders.'],
        ['gulshanbakingshop', 'Gulshan Baking Shop', 'https://gulshanbakingshop.com', 'food-grocery', 'ecommerce', 'live', false, 'Imported groceries and baking supplies with 24-hour delivery in Dhaka.'],
        ['bdfreshbazar', 'BD Fresh Bazar', 'https://bdfreshbazar.com', 'food-grocery', 'ecommerce', 'live', false, 'Dairy store selling yogurt, ghee and milk online.'],
        ['seasonalbd', 'Seasonal', 'https://seasonalbd.com', 'food-grocery', 'ecommerce', 'live', true, 'Natural food store: honey, cold-pressed oil, rice and spices sourced from farmers.'],
        ['seasonal-demo', 'Seasonal (demo)', 'https://seasonal.auxtechsoftware.com', 'food-grocery', 'ecommerce', 'demo', false, 'Demo of the organic food storefront.'],
        ['auxify', 'Auxify', 'https://auxify.store', 'saas', 'auxify', 'product', false, 'Auxtech\'s store builder: ready-made themes, plans by the month, Bangla and English.'],
        ['resellerv2', 'AuxTech Reseller', 'https://resellerv2.auxtechsoftware.com', 'reselling', 'reseller-platform', 'demo', false, 'Demo of the B2B dropshipping and reselling platform.'],
        ['rongtorongob2b', 'Rong Torongo B2B', 'https://rongtorongob2b.com', 'reselling', 'reseller-platform', 'live', true, 'Dropshipping and reselling platform with reseller packages.'],
        ['ehubdropshop', 'E Hub Dropshop', 'https://ehubdropshop.com', 'reselling', 'reseller-platform', 'live', false, 'Zero-capital dropshipping platform with support tickets and tutorials.'],
        ['asthareseller', 'Astha Reseller', 'https://asthareseller.com', 'reselling', 'reseller-platform', 'live', false, 'B2B catalogue network for resellers with cash on delivery to 64 districts.'],
        ['wtbl', 'Water Technology BD Limited', 'https://wtbl.com.bd', 'corporate', 'corporate-website', 'live', true, 'Corporate website for an industrial water and wastewater treatment company.'],
        ['greenland-tax', 'Greenland Tax', 'https://tax.greenlandmarketingcompany.com', 'finance', 'custom-software', 'live', false, 'Bangladesh income tax calculator with certificate generation.'],
        ['e7training', 'E7 Training', 'https://e7training.com', 'education', 'lms', 'live', true, 'Online learning platform with paid courses and student and trainer portals.'],
        ['sstyping', 'SS Typing & Travels', 'https://sstyping.com', 'travel', 'corporate-website', 'live', false, 'Website for a UAE-based Hajj, Umrah and typing services company.'],
        ['cleanbdx', 'CleanBDX', 'https://cleanbdx.com', 'environment', 'custom-software', 'live', true, 'Digital waste management system connecting citizens, sellers and riders.'],
    ];

    public function run(): void
    {
        $industries = Industry::query()->pluck('id', 'slug');
        $solutions = Solution::query()->pluck('id', 'slug');
        $services = Service::query()->pluck('id', 'slug');

        foreach (self::PROJECTS as $order => [$slug, $name, $url, $industry, $solution, $type, $featured, $tagline]) {
            if (Project::query()->withTrashed()->where('slug', $slug)->exists()) {
                continue;
            }

            $client = Client::query()->firstOrCreate(['slug' => $slug], [
                'name' => $name,
                'website_url' => $url,
                'industry_id' => $industries[$industry] ?? null,
                // Auxtech's own products and demos are not shown as clients.
                'show_logo' => $type === 'live',
                'sort_order' => $order + 1,
            ]);

            $project = Project::query()->create([
                'slug' => $slug,
                'client_id' => $client->id,
                'industry_id' => $industries[$industry] ?? null,
                'solution_id' => $solutions[$solution] ?? null,
                'type' => ProjectType::from($type),
                'title' => ['en' => $name],
                'tagline' => ['en' => $tagline],
                'live_url' => $url,
                'is_featured' => $featured,
                'status' => PublishStatus::Published,
                'sort_order' => $order + 1,
            ]);

            $project->services()->sync(array_filter([
                $services[in_array($solution, ['ecommerce', 'auxify'], true) ? 'ecommerce-development' : ($solution === 'corporate-website' ? 'website-development' : 'custom-web-application')] ?? null,
            ]));

            $screenshot = resource_path("seed/portfolio/{$slug}.webp");

            if (is_file($screenshot)) {
                $project->addMedia($screenshot)->preservingOriginal()
                    ->withCustomProperties(['width' => 1280, 'height' => 800, 'alt' => ['en' => "{$name} website home page"]])
                    ->toMediaCollection('cover');
            }
        }
    }
}
