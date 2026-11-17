<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class ServiceModule extends Module
{
    public function key(): string
    {
        return 'services';
    }

    public function model(): string
    {
        return Service::class;
    }

    public function fields(): array
    {
        return [
            Field::text('title')->translatable()->required(),
            Field::slug(),
            Field::text('tagline')->translatable(),
            Field::textarea('excerpt')->translatable()->max(400),
            Field::richtext('body')->translatable(),
            Field::repeater('features', [Field::text('title')->translatable()->required(), Field::textarea('description')->translatable()], 'What is included'),
            Field::repeater('process_steps', [Field::text('title')->translatable()->required(), Field::textarea('description')->translatable()], 'Process steps'),
            Field::belongsTo('parent_id', Service::class, 'title', 'Parent service'),
            Field::icon(),
            Field::image('cover'),
            Field::toggle('is_featured', 'Featured on home page'),
            ...Field::publishing(),
        ];
    }

    public function columns(): array
    {
        return ['title' => 'Title', 'parent.title' => 'Parent', 'status' => 'Status', 'is_featured' => 'Featured'];
    }

    public function searchable(): array
    {
        return ['title', 'slug'];
    }

    public function with(): array
    {
        return ['parent'];
    }

    public function rules(?Model $record): array
    {
        // A service cannot be its own parent.
        return $record ? ['parent_id' => ['nullable', 'integer', 'exists:services,id', 'not_in:'.$record->getKey()]] : [];
    }
}
