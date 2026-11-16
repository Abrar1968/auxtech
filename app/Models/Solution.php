<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class Solution extends Model implements HasMedia
{
    use HasFactory, HasImages, HasSeo, HasSlug, HasTranslations, Publishable, SoftDeletes, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['name', 'tagline', 'summary', 'body'];

    protected string $slugFrom = 'name';

    protected function casts(): array
    {
        return ['is_flagship' => 'boolean'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function featureGroups(): HasMany
    {
        return $this->hasMany(SolutionFeatureGroup::class)->orderBy('sort_order');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
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
        return '/solutions/'.$this->slug;
    }
}
