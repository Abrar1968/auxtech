<?php

namespace App\Tasks;

use App\Models\Lead;
use App\Services\Meta\ConversionsApi;

class SendMetaLeadEvent
{
    public function __construct(private readonly ConversionsApi $api) {}

    /** @param array{lead_id: int, ip?: string|null} $payload */
    public function handle(array $payload): void
    {
        $lead = Lead::query()->find($payload['lead_id']);

        if ($lead && $this->api->enabled()) {
            $this->api->lead($lead, $payload['ip'] ?? null);
        }
    }
}
