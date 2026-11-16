<?php

namespace App\Models\Concerns;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Generates a unique slug from the model's slug source and, when a
 * published URL changes, leaves a 301 redirect behind.
 *
 * @mixin Model
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (Model $model): void {
            /** @var Model&HasSlug $model */
            $slug = Str::slug((string) ($model->getAttribute('slug') ?: $model->slugSource()));
            $model->setAttribute('slug', $model->uniqueSlug($slug !== '' ? $slug : Str::lower(Str::random(8))));
        });

        static::updated(function (Model $model): void {
            /** @var Model&HasSlug $model */
            if (! $model->wasChanged('slug') || ! method_exists($model, 'publicPath')) {
                return;
            }

            $old = (clone $model)->setAttribute('slug', $model->getOriginal('slug'))->publicPath();
            $new = $model->publicPath();

            if ($old !== $new) {
                Redirect::remember($old, $new);
            }
        });
    }

    protected function slugSource(): string
    {
        $source = property_exists($this, 'slugFrom') ? $this->slugFrom : 'title';
        $value = $this->getAttributes()[$source] ?? '';

        // Translatable columns hold JSON; the English text is the slug source.
        if (is_string($value) && str_starts_with($value, '{')) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? ($decoded['en'] ?? reset($decoded) ?: '') : $value;
        }

        return (string) $value;
    }

    protected function uniqueSlug(string $slug): string
    {
        $candidate = $slug;
        $suffix = 2;

        while (static::query()
            ->where('slug', $candidate)
            ->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))
            ->when(in_array(SoftDeletes::class, class_uses_recursive(static::class), true), fn ($q) => $q->withTrashed())
            ->exists()) {
            $candidate = $slug.'-'.$suffix++;
        }

        return $candidate;
    }
}
