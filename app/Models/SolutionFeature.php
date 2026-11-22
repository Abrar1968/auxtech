<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class SolutionFeature extends Model implements HasMedia
{
    use HasFactory, HasImages, HasSlug, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'description'];

    /** A screenshot of the module, shown in the module explorer on the platform pages. */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('screenshot')->singleFile();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(SolutionFeatureGroup::class, 'solution_feature_group_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
