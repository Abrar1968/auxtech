<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\PricingPlan;

class PricingPlanModule extends Module
{
    public function key(): string
    {
        return 'pricing-plans';
    }

    public function model(): string
    {
        return PricingPlan::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->translatable()->required(),
            Field::textarea('description')->translatable()->max(500),
            Field::number('price')->rules('min:0')->help('Leave empty to show "Get a quote".'),
            Field::text('currency')->max(3)->default('BDT')->required(),
            Field::text('period')->max(30)->help('e.g. "one-time", "month".'),
            Field::repeater('features', [Field::text('title')->translatable()->required()], 'Included'),
            Field::select('planable_type', ['service' => 'Service', 'solution' => 'Solution'], 'Belongs to')->side(),
            Field::number('planable_id', 'Record ID')->rules('integer')->side(),
            Field::toggle('is_popular', 'Mark as most popular'),
            Field::toggle('is_active', 'Visible')->default(true),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Plan', 'price' => 'Price', 'period' => 'Period', 'is_active' => 'Visible'];
    }

    public function label(): string
    {
        return 'Pricing Plans';
    }

    public function searchable(): array
    {
        return ['name'];
    }
}
