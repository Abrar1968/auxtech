<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class Post extends Model implements HasMedia
{
    use HasFactory, HasImages, HasSeo, HasSlug, HasTranslations, Publishable, SoftDeletes;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'excerpt', 'body'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'reading_minutes' => 'integer'];
    }

    protected static function booted(): void
    {
        static::saving(function (self $post): void {
            $words = str_word_count(strip_tags((string) $post->getTranslation('body', 'en', false)));
            $post->reading_minutes = max(1, (int) ceil($words / 200));
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function publicPath(): string
    {
        return '/blog/'.$this->slug;
    }
}
