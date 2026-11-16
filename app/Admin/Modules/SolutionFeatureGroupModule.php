<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Solution;
use App\Models\SolutionFeatureGroup;

class SolutionFeatureGroupModule extends Module
{
    public function key(): string
    {
        return 'solution-feature-groups';
    }

    public function model(): string
    {
        return SolutionFeatureGroup::class;
    }

    public function fields(): array
    {
        return [
            Field::belongsTo('solution_id', Solution::class, 'name', 'Solution')->required(),
            Field::text('name')->translatable()->required(),
            Field::text('key')->max(60)->required()->rules('alpha_dash'),
            Field::textarea('description')->translatable(),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Group', 'solution.name' => 'Solution'];
    }

    public function label(): string
    {
        return 'Feature Groups';
    }

    public function searchable(): array
    {
        return ['name'];
    }

    public function with(): array
    {
        return ['solution'];
    }
}
