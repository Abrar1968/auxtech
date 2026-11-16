<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\PostCategory;

class PostCategoryModule extends Module
{
    public function key(): string
    {
        return 'post-categories';
    }

    public function model(): string
    {
        return PostCategory::class;
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

    public function label(): string
    {
        return 'Blog Categories';
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
