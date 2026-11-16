<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model implements HasMedia
{
    use HasFactory, HasImages, HasTranslations, Sortable;

    protected $guarded = ['id'];

    /** @var list<string> */
    public array $translatable = ['role', 'bio'];

    protected function casts(): array
    {
        return ['socials' => 'json', 'is_active' => 'boolean'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }
}
