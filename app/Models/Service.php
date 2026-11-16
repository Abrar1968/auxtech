<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia
{
    use HasFactory, HasImages, HasSeo, HasSlug, HasTranslations, Publishable, SoftDeletes, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'tagline', 'excerpt', 'body'];

    protected function casts(): array
    {
        return ['features' => 'json', 'process_steps' => 'json', 'is_featured' => 'boolean'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable');
    }

    public function plans(): MorphMany
    {
        return $this->morphMany(PricingPlan::class, 'planable');
    }

    public function publicPath(): string
    {
        return '/services/'.$this->slug;
    }
}
