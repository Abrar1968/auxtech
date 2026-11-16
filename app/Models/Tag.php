<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory, HasSlug, HasTranslations;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['name'];

    protected string $slugFrom = 'name';

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function publicPath(): string
    {
        return '/blog/tag/'.$this->slug;
    }
}
