<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\Detailed;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Post for the current locale. Mirrored in resources/js/types/models.d.ts. */
class PostResource extends JsonResource
{
    use Detailed;

    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'url' => lroute('blog.show', $this->slug),
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'reading_minutes' => $this->reading_minutes,
            'published_at' => $this->published_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'cover' => $this->image('cover'),
            'author' => $this->whenLoaded('author', fn () => $this->author?->name),
            'category' => $this->whenLoaded('category', fn () => $this->category ? ['name' => $this->category->name, 'url' => lroute('blog.category', $this->category->slug)] : null),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($t) => ['name' => $t->name, 'url' => lroute('blog.tag', $t->slug)])->all()),
            'body' => $this->when($this->full, fn () => $this->body),
        ];
    }
}
