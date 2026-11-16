<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Testimonial for the current locale. Mirrored in resources/js/types/models.d.ts. */
class TestimonialResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_name' => $this->author_name,
            'author_role' => $this->author_role,
            'quote' => $this->quote,
            'rating' => $this->rating,
            'video_url' => $this->video_url,
            'avatar' => $this->image('avatar'),
            'client' => $this->whenLoaded('client', fn () => $this->client?->name),
            'project_url' => $this->whenLoaded('project', fn () => $this->project?->isPublished() ? lroute('work.show', $this->project->slug) : null),
        ];
    }
}
