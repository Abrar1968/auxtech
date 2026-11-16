<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\Detailed;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Service for the current locale. Mirrored in resources/js/types/models.d.ts. */
class ServiceResource extends JsonResource
{
    use Detailed;

    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'url' => lroute('services.show', $this->slug),
            'title' => $this->title,
            'tagline' => $this->tagline,
            'excerpt' => $this->excerpt,
            'icon' => $this->icon,
            'is_featured' => $this->is_featured,
            'cover' => $this->image('cover'),
            'body' => $this->when($this->full, fn () => $this->body),
            'features' => $this->when($this->full, fn () => $this->translatedRows($this->features)),
            'process_steps' => $this->when($this->full, fn () => $this->translatedRows($this->process_steps)),
            'children' => self::collection($this->whenLoaded('children')),
            'parent' => $this->whenLoaded('parent', fn () => $this->parent ? ['title' => $this->parent->title, 'url' => lroute('services.show', $this->parent->slug)] : null),
        ];
    }
}
