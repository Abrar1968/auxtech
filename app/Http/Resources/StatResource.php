<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Stat for the current locale. Mirrored in resources/js/types/models.d.ts. */
class StatResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->value,
            'suffix' => $this->suffix,
            'label' => $this->label,
        ];
    }
}
