<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Tag;

class TagModule extends Module
{
    public function key(): string
    {
        return 'tags';
    }

    public function model(): string
    {
        return Tag::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->translatable()->required(),
            Field::slug(),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Name', 'slug' => 'Slug'];
    }

    public function group(): string
    {
        return 'Blog';
    }

    public function searchable(): array
    {
        return ['name'];
    }
}
