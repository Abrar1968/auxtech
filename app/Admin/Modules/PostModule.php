<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PostModule extends Module
{
    public function key(): string
    {
        return 'posts';
    }

    public function model(): string
    {
        return Post::class;
    }

    public function fields(): array
    {
        return [
            Field::text('title')->translatable()->required(),
            Field::slug(),
            Field::textarea('excerpt')->translatable()->max(400),
            Field::richtext('body')->translatable()->required(),
            Field::belongsToMany('tags', Tag::class),
            Field::belongsTo('post_category_id', PostCategory::class, 'name', 'Category'),
            Field::belongsTo('author_id', User::class, 'name', 'Author'),
            Field::image('cover'),
            Field::toggle('is_featured', 'Featured'),
            ...Field::publishing(),
        ];
    }

    public function columns(): array
    {
        return ['title' => 'Title', 'category.name' => 'Category', 'status' => 'Status', 'published_at' => 'Published'];
    }

    public function group(): string
    {
        return 'Blog';
    }

    public function searchable(): array
    {
        return ['title', 'slug'];
    }

    public function with(): array
    {
        return ['category'];
    }

    public function beforeSave(array $data, ?Model $record): array
    {
        // New posts belong to whoever wrote them unless an author is chosen.
        if ($record === null && empty($data['author_id'])) {
            $data['author_id'] = auth()->id();
        }

        return $data;
    }

    public function filters(): array
    {
        return ['status' => ['draft' => 'Draft', 'published' => 'Published']];
    }
}
