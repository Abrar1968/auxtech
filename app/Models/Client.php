<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Client extends Model implements HasMedia
{
    use HasFactory, HasImages, HasSlug, Sortable;

    protected $guarded = ['id'];

    protected string $slugFrom = 'name';

    protected function casts(): array
    {
        return ['show_logo' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }
}
