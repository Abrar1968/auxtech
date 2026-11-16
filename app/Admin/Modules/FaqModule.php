<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Faq;

class FaqModule extends Module
{
    public function key(): string
    {
        return 'faqs';
    }

    public function model(): string
    {
        return Faq::class;
    }

    public function fields(): array
    {
        return [
            Field::text('question')->translatable()->required()->max(300),
            Field::richtext('answer')->translatable()->required(),
            Field::select('group', ['general' => 'General', 'pricing' => 'Pricing', 'ecommerce' => 'E-commerce', 'process' => 'Process', 'support' => 'Support'])->default('general')->side(),
            Field::select('faqable_type', ['service' => 'Service', 'solution' => 'Solution', 'landing_page' => 'Landing page'], 'Attach to')->side(),
            Field::number('faqable_id', 'Attached record ID')->rules('integer')->side(),
            Field::toggle('is_active', 'Visible')->default(true),
        ];
    }

    public function columns(): array
    {
        return ['question' => 'Question', 'group' => 'Group', 'is_active' => 'Visible'];
    }

    public function label(): string
    {
        return 'FAQs';
    }

    public function searchable(): array
    {
        return ['question'];
    }
}
