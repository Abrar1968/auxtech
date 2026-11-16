<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class SolutionFeature extends Model
{
    use HasFactory, HasSlug, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'description'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(SolutionFeatureGroup::class, 'solution_feature_group_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }
}
