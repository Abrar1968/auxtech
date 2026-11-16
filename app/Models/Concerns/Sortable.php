<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Sortable
{
    public static function bootSortable(): void
    {
        static::creating(function (Model $model): void {
            if ($model->getAttribute('sort_order') === null) {
                $model->setAttribute('sort_order', (int) static::query()->max('sort_order') + 1);
            }
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy($this->qualifyColumn('sort_order'))->orderBy($this->qualifyColumn('id'));
    }

    /** @param list<int|string> $ids */
    public static function reorder(array $ids): void
    {
        foreach (array_values($ids) as $position => $id) {
            static::query()->whereKey($id)->update(['sort_order' => $position + 1]);
        }
    }
}
