<?php

namespace App\Http\Resources\Concerns;

/**
 * List pages get a light "card" shape; detail pages call ->full() to
 * include long text and related records.
 */
trait Detailed
{
    public bool $full = false;

    public function full(): static
    {
        $this->full = true;

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $rows
     * @return list<array{title: ?string, description: ?string}>
     */
    protected function translatedRows(?array $rows): array
    {
        return collect($rows ?? [])->map(fn (array $row) => [
            'title' => tr($row['title'] ?? null),
            'description' => tr($row['description'] ?? null),
        ])->values()->all();
    }
}
