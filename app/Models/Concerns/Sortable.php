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

    /**
     * Reorder the given rows among the positions they already hold, so
     * dragging within one page of a list never disturbs the other pages.
     *
     * @param  list<int|string>  $ids
     */
    public static function reorder(array $ids): void
    {
        $slots = static::query()->whereKey($ids)->orderBy('sort_order')->orderBy('id')->pluck('sort_order')->all();

        // Rows sharing a position (e.g. never ordered) get distinct ones.
        if (count(array_unique($slots)) !== count($slots)) {
            $start = (int) ($slots[0] ?? 1);
            $slots = range($start, $start + count($ids) - 1);
        }

        foreach (array_values($ids) as $index => $id) {
            if (isset($slots[$index])) {
                static::query()->whereKey($id)->update(['sort_order' => $slots[$index]]);
            }
        }
    }
}
