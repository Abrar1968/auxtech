<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Starter FAQs (facts only) and draft legal pages. Statistics,
 * testimonials and prices are deliberately not seeded.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['What does Auxtech do?', 'Auxtech builds websites and apps and runs digital marketing for businesses in Bangladesh. Most of our work is e-commerce: online stores with order, inventory, POS, courier and Meta tracking features.'],
            ['How do I contact Auxtech?', 'The fastest way is WhatsApp on 01805019415. You can also email auxtechbd@gmail.com or visit our office at 754/1, 5th Floor, West Shewrapara, Begum Rokeya Avenue, Dhaka. We are open every day except Friday, 10am to 6pm.'],
            ['What is included in Auxtech Ecommerce?', 'Auxtech Ecommerce has 23 modules, including product, order, inventory, customer, employee and accounting management, point of sale (POS), coupons and campaigns, landing pages, pre-orders, incomplete order recovery, courier integration, order tracking, reviews, Meta Pixel, Google Tag Manager and Conversions API.'],
            ['Can I see a live demo before deciding?', 'Yes. We have live demo stores you can browse, and you can visit the client stores in our portfolio. Message us on WhatsApp and we will send the demo links that match your business.'],
        ];

        foreach ($faqs as $order => [$question, $answer]) {
            Faq::query()->firstOrCreate(['sort_order' => $order + 1, 'group' => 'general'], [
                'question' => ['en' => $question],
                'answer' => ['en' => '<p>'.$answer.'</p>'],
                'is_active' => true,
            ]);
        }

        // Legal text must be reviewed by Auxtech before it is published.
        foreach (['privacy-policy' => 'Privacy Policy', 'terms' => 'Terms & Conditions'] as $slug => $title) {
            Page::query()->firstOrCreate(['slug' => $slug], [
                'title' => ['en' => $title],
                'body' => ['en' => '<p>This page is a draft. Write and review the text, then publish it.</p>'],
                'template' => 'legal',
                'status' => PublishStatus::Draft,
            ]);
        }
    }
}
