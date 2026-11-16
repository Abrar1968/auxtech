<?php

namespace App\Models;

use App\Enums\ProjectType;
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
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class Project extends Model implements HasMedia
{
    use HasFactory, HasImages, HasSeo, HasSlug, HasTranslations, Publishable, SoftDeletes, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'tagline', 'summary', 'challenge', 'approach', 'outcome'];

    protected function casts(): array
    {
        return [
            'type' => ProjectType::class,
            'metrics' => 'json',
            'launched_at' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('mobile')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(SolutionFeature::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function publicPath(): string
    {
        return '/work/'.$this->slug;
    }
}
