<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class PricingPlan extends Model
{
    use HasFactory, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['name', 'description'];

    protected function casts(): array
    {
        return ['features' => 'json', 'is_popular' => 'boolean', 'is_active' => 'boolean', 'price' => 'decimal:2'];
    }

    public function planable(): MorphTo
    {
        return $this->morphTo();
    }
}
