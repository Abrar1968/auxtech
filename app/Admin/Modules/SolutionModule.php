<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Solution;

class SolutionModule extends Module
{
    public function key(): string
    {
        return 'solutions';
    }

    public function model(): string
    {
        return Solution::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->translatable()->required(),
            Field::slug(),
            Field::text('tagline')->translatable(),
            Field::textarea('summary')->translatable()->max(600),
            Field::richtext('body')->translatable(),
            Field::url('demo_url', 'Live demo URL'),
            Field::url('external_url', 'Product website')->help('For products with their own site, such as Auxify.'),
            Field::text('icon')->max(60)->side(),
            Field::image('cover'),
            Field::toggle('is_flagship', 'Flagship product'),
            ...Field::publishing(),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Name', 'status' => 'Status', 'is_flagship' => 'Flagship'];
    }

    public function searchable(): array
    {
        return ['name', 'slug'];
    }
}
