<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a Faq for the current locale. Mirrored in resources/js/types/models.d.ts. */
class FaqResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }
}
