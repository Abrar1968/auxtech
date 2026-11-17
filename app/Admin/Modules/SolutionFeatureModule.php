<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\SolutionFeature;
use App\Models\SolutionFeatureGroup;

class SolutionFeatureModule extends Module
{
    public function key(): string
    {
        return 'solution-features';
    }

    public function model(): string
    {
        return SolutionFeature::class;
    }

    public function fields(): array
    {
        return [
            Field::belongsTo('solution_feature_group_id', SolutionFeatureGroup::class, 'name', 'Group')->required(),
            Field::text('title')->translatable()->required(),
            Field::slug(),
            Field::textarea('description')->translatable(),
            Field::icon(),
        ];
    }

    public function columns(): array
    {
        return ['title' => 'Module', 'group.name' => 'Group'];
    }

    public function label(): string
    {
        return 'Features';
    }

    public function searchable(): array
    {
        return ['title'];
    }

    public function with(): array
    {
        return ['group'];
    }
}
