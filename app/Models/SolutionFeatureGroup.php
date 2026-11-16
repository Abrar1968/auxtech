<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SolutionFeatureGroup extends Model
{
    use HasFactory, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['name', 'description'];

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(SolutionFeature::class)->orderBy('sort_order');
    }
}
