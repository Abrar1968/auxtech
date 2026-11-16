<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Technology;

class TechnologyModule extends Module
{
    public function key(): string
    {
        return 'technologies';
    }

    public function model(): string
    {
        return Technology::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->required(),
            Field::slug(),
            Field::text('category')->max(60),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Name', 'category' => 'Category'];
    }

    public function group(): string
    {
        return 'Catalogue';
    }

    public function searchable(): array
    {
        return ['name'];
    }
}
