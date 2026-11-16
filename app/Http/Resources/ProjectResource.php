<?php

namespace App\Http\Resources;

use App\Enums\ProjectType;
use App\Http\Resources\Concerns\Detailed;
use App\Services\WhatsApp\LinkBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Project for the current locale. Mirrored in resources/js/types/models.d.ts. */
class ProjectResource extends JsonResource
{
    use Detailed;

    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'url' => lroute('work.show', $this->slug),
            'title' => $this->title,
            'tagline' => $this->tagline,
            'summary' => $this->summary,
            'type' => $this->type->value,
            'is_demo' => $this->type === ProjectType::Demo,
            'is_featured' => $this->is_featured,
            'live_url' => $this->live_url,
            'cover' => $this->image('cover'),
            'client' => $this->whenLoaded('client', fn () => $this->client ? ['name' => $this->client->name, 'logo' => $this->client->image('logo')] : null),
            'industry' => $this->whenLoaded('industry', fn () => $this->industry ? ['name' => $this->industry->name, 'slug' => $this->industry->slug, 'url' => lroute('industries.show', $this->industry->slug)] : null),
            'solution' => $this->whenLoaded('solution', fn () => $this->solution ? ['name' => $this->solution->name, 'slug' => $this->solution->slug, 'url' => lroute('solutions.show', $this->solution->slug)] : null),
            'service_slugs' => $this->whenLoaded('services', fn () => $this->services->pluck('slug')->all()),
            'challenge' => $this->when($this->full, fn () => $this->challenge),
            'approach' => $this->when($this->full, fn () => $this->approach),
            'outcome' => $this->when($this->full, fn () => $this->outcome),
            'launched_at' => $this->when($this->full, fn () => $this->launched_at?->toDateString()),
            'metrics' => $this->when($this->full, fn () => collect($this->metrics ?? [])->map(fn ($m) => ['value' => $m['value'] ?? null, 'label' => tr($m['label'] ?? null)])->all()),
            'mobile' => $this->when($this->full, fn () => $this->image('mobile')),
            'gallery' => $this->when($this->full, fn () => $this->images('gallery')),
            'services' => $this->when($this->full, fn () => $this->services->map(fn ($s) => ['title' => $s->title, 'url' => lroute('services.show', $s->slug)])->all()),
            'technologies' => $this->when($this->full, fn () => TechnologyResource::collection($this->technologies)->resolve()),
            'features' => $this->when($this->full, fn () => $this->features->map(fn ($f) => ['slug' => $f->slug, 'title' => $f->title])->all()),
            'whatsapp_url' => $this->when($this->full, fn () => app(LinkBuilder::class)->url('project', $this->client?->name ?? $this->title)),
        ];
    }
}
