<?php

namespace App\Tasks;

use App\Events\WhatsAppClicked;
use App\Models\ConversionEvent;
use App\Support\Realtime;

class BroadcastConversion
{
    /** @param array{event_id: int} $payload */
    public function handle(array $payload): void
    {
        if ($event = ConversionEvent::query()->find($payload['event_id'])) {
            Realtime::broadcast(new WhatsAppClicked($event));
        }
    }
}
