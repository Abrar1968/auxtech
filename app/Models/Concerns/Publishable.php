<?php

namespace App\Models\Concerns;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * Draft / published state with optional scheduling. Scheduling needs no
 * cron: a record is public once its published_at time has passed.
 */
trait Publishable
{
    public function initializePublishable(): void
    {
        $this->mergeCasts(['status' => PublishStatus::class, 'published_at' => 'datetime']);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where($this->qualifyColumn('status'), PublishStatus::Published->value)
            ->where(fn (Builder $q) => $q
                ->whereNull($this->qualifyColumn('published_at'))
                ->orWhere($this->qualifyColumn('published_at'), '<=', now()));
    }

    public function isPublished(): bool
    {
        return $this->status === PublishStatus::Published
            && ($this->published_at === null || $this->published_at->lte(now()));
    }
}
