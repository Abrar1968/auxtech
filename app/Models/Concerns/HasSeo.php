<?php

namespace App\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public static function bootHasSeo(): void
    {
        static::deleting(function ($model): void {
            if (! method_exists($model, 'isForceDeleting') || $model->isForceDeleting()) {
                $model->seo()->delete();
            }
        });
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }
}
