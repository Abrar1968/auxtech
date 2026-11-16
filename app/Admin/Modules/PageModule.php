<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Page;
use App\Support\ReservedSlugs;
use Illuminate\Database\Eloquent\Model;

class PageModule extends Module
{
    public function key(): string
    {
        return 'pages';
    }

    public function model(): string
    {
        return Page::class;
    }

    public function fields(): array
    {
        return [
            Field::text('title')->translatable()->required(),
            Field::slug(),
            Field::richtext('body')->translatable(),
            Field::select('template', ['default' => 'Default', 'legal' => 'Legal'])->required()->default('default')->side(),
            ...Field::publishing(),
        ];
    }

    public function columns(): array
    {
        return ['title' => 'Title', 'slug' => 'Slug', 'status' => 'Status'];
    }

    public function searchable(): array
    {
        return ['title', 'slug'];
    }

    public function rules(?Model $record): array
    {
        // Page slugs share the top-level URL space with the site's own routes.
        return ['slug' => ['nullable', 'string', 'max:191', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'not_in:'.implode(',', ReservedSlugs::all())]];
    }
}
