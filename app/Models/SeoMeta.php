<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class SeoMeta extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'description'];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
