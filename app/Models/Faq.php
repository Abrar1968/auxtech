<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['question', 'answer'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function faqable(): MorphTo
    {
        return $this->morphTo();
    }
}
