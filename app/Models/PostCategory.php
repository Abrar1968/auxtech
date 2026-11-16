<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class PostCategory extends Model
{
    use HasFactory, HasSlug, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['name'];

    protected string $slugFrom = 'name';

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function publicPath(): string
    {
        return '/blog/category/'.$this->slug;
    }
}
