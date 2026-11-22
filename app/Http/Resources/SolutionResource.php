<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\Detailed;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Solution for the current locale. Mirrored in resources/js/types/models.d.ts. */
class SolutionResource extends JsonResource
{
    use Detailed;

    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'url' => lroute('solutions.show', $this->slug),
            'name' => $this->name,
            'tagline' => $this->tagline,
            'summary' => $this->summary,
            'icon' => $this->icon,
            'is_flagship' => $this->is_flagship,
            'demo_url' => $this->demo_url,
            'external_url' => $this->external_url,
            'cover' => $this->image('cover'),
            'body' => $this->when($this->full, fn () => $this->body),
            'projects_count' => $this->whenCounted('projects'),
            'feature_groups' => $this->whenLoaded('featureGroups', fn () => $this->featureGroups->map(fn ($group) => [
                'key' => $group->key,
                'name' => $group->name,
                'description' => $group->description,
                'features' => $group->features->map(fn ($feature) => [
                    'slug' => $feature->slug,
                    'title' => $feature->title,
                    'description' => $feature->description,
                    'icon' => $feature->icon,
                    'image' => $feature->image('screenshot'),
                ])->all(),
            ])->all()),
        ];
    }
}
