<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Stat extends Model
{
    use HasFactory, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['label'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
