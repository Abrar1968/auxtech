<?php

namespace Database\Seeders\Concerns;

use Database\Seeders\SeedOptions;
use Illuminate\Database\Eloquent\Model;

/**
 * Seeding that is safe to run again on a live site: missing records are
 * created; existing ones are left as edited in the admin panel unless
 * overwriting is switched on (php artisan auxtech:seed --overwrite).
 */
trait SeedsContent
{
    /**
     * @param  class-string<Model>  $model
     * @param  array<string, mixed>  $keys
     * @param  array<string, mixed>  $values
     * @return array{0: Model, 1: bool} the record and whether it was created now
     */
    protected function seedRecord(string $model, array $keys, array $values): array
    {
        $query = $model::query();
        if (method_exists($model, 'bootSoftDeletes')) {
            $query->withTrashed();
        }

        $existing = $query->where($keys)->first();

        if (! $existing) {
            return [$model::query()->create($keys + $values), true];
        }

        if (SeedOptions::$overwrite) {
            $existing->fill($values)->save();
        }

        return [$existing, false];
    }
}
