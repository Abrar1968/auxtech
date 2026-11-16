<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionEvent extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['type' => EventType::class, 'created_at' => 'datetime'];
    }
}
