<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasSeo, HasSlug, HasTranslations, Publishable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['title', 'body'];

    public function publicPath(): string
    {
        return '/'.$this->slug;
    }
}
