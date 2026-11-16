<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, HasImages, HasTranslations, Publishable, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['author_role', 'quote'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'rating' => 'integer'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
