<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a TeamMember for the current locale. Mirrored in resources/js/types/models.d.ts. */
class TeamMemberResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'role' => $this->role,
            'bio' => $this->bio,
            'socials' => $this->socials ?? [],
            'photo' => $this->image('photo'),
        ];
    }
}
