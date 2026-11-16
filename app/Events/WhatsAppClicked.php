<?php

namespace App\Events;

use App\Models\ConversionEvent;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/** Live update for the admin panel, sent through Pusher. */
class WhatsAppClicked implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(public readonly ConversionEvent $event) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('admin');
    }

    public function broadcastAs(): string
    {
        return 'whatsapp.clicked';
    }

    /** @return array<string, mixed> */
    public function broadcastWith(): array
    {
        return ['id' => $this->event->id, 'type' => $this->event->type->value, 'placement' => $this->event->placement, 'page_url' => $this->event->page_url, 'created_at' => $this->event->created_at?->toIso8601String()];
    }
}
