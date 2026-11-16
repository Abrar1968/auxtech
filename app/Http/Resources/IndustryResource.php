<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Industry for the current locale. Mirrored in resources/js/types/models.d.ts. */
class IndustryResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'url' => lroute('industries.show', $this->slug),
            'projects_count' => $this->whenCounted('projects'),
        ];
    }
}
