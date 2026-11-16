<?php

namespace App\Models;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'source' => LeadSource::class,
            'status' => LeadStatus::class,
            'meta' => 'json',
            'contacted_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $lead): void {
            $lead->uuid ??= (string) Str::uuid();
        });
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest();
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
