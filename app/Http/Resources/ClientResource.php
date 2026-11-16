<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Client for the current locale. Mirrored in resources/js/types/models.d.ts. */
class ClientResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'website_url' => $this->website_url,
            'location' => $this->location,
            'logo' => $this->image('logo'),
        ];
    }
}
