<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\Faq;
use App\Models\Page;
use Database\Seeders\Concerns\SeedsContent;
use Illuminate\Database\Seeder;

/**
 * General FAQs and draft legal pages. Statistics, testimonials and prices are
 * deliberately not seeded: Auxtech enters its own in the admin panel.
 */
class ContentSeeder extends Seeder
{
    use SeedsContent;

    public function run(): void
    {
        $data = require __DIR__.'/data/catalogue.php';

        foreach ($data['faqs'] as $order => $faq) {
            $this->seedRecord(Faq::class, ['sort_order' => $order + 1, 'group' => 'general', 'faqable_id' => null], [
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'is_active' => true,
            ]);
        }

        // Legal text must be written and reviewed by Auxtech before it is published.
        foreach (['privacy-policy' => 'Privacy Policy', 'terms' => 'Terms & Conditions'] as $slug => $title) {
            $this->seedRecord(Page::class, ['slug' => $slug], [
                'title' => ['en' => $title],
                'body' => ['en' => '<p>This page is a draft. Write and review the text, then publish it.</p>'],
                'template' => 'legal',
                'status' => PublishStatus::Draft,
            ]);
        }
    }
}
