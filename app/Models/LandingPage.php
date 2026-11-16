<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class LandingPage extends Model
{
    use HasFactory, HasSeo, HasSlug, HasTranslations, Publishable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'intro', 'body'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable');
    }

    public function publicPath(): string
    {
        return '/'.$this->slug;
    }
}
