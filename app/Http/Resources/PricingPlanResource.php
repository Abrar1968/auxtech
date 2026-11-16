<?php

namespace App\Http\Resources;

use App\Services\WhatsApp\LinkBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Public shape of a PricingPlan for the current locale. Mirrored in resources/js/types/models.d.ts. */
class PricingPlanResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price === null ? null : (float) $this->price,
            'currency' => $this->currency,
            'period' => $this->period,
            'features' => collect($this->features ?? [])->map(fn ($f) => tr($f['title'] ?? null))->filter()->values()->all(),
            'is_popular' => $this->is_popular,
            'whatsapp_url' => app(LinkBuilder::class)->url('plan', $this->name),
        ];
    }
}
