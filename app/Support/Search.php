<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

final class Search
{
    /**
     * Case-insensitive "contains" across columns. MariaDB stores JSON
     * (translatable) columns with a binary collation, so a plain LIKE
     * would be case-sensitive there.
     *
     * @param  list<string>  $columns  trusted column names, never user input
     */
    public static function apply(Builder $query, array $columns, string $term): Builder
    {
        $like = '%'.addcslashes(mb_strtolower(trim($term)), '%_\\').'%';

        return $query->where(function (Builder $q) use ($columns, $like): void {
            foreach ($columns as $column) {
                $q->orWhereRaw('LOWER('.$q->getGrammar()->wrap($q->qualifyColumn($column)).') LIKE ?', [$like]);
            }
        });
    }
}
