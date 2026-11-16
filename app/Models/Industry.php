<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Industry extends Model
{
    use HasFactory, HasSeo, HasSlug, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['name', 'description'];

    protected string $slugFrom = 'name';

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function publicPath(): string
    {
        return '/industries/'.$this->slug;
    }
}
