<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Industry;

class IndustryModule extends Module
{
    public function key(): string
    {
        return 'industries';
    }

    public function model(): string
    {
        return Industry::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->translatable()->required(),
            Field::slug(),
            Field::textarea('description')->translatable(),
            Field::text('icon')->max(60)->help('Lucide icon name, e.g. "shirt".')->side(),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Name', 'slug' => 'Slug'];
    }

    public function group(): string
    {
        return 'Catalogue';
    }

    public function searchable(): array
    {
        return ['name', 'slug'];
    }
}
