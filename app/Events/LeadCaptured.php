<?php

namespace App\Events;

use App\Models\Lead;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/** Live update for the admin panel, sent through Pusher. */
class LeadCaptured implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(public readonly Lead $lead) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('admin');
    }

    public function broadcastAs(): string
    {
        return 'lead.captured';
    }

    /** @return array<string, mixed> */
    public function broadcastWith(): array
    {
        return ['id' => $this->lead->id, 'uuid' => $this->lead->uuid, 'name' => $this->lead->name, 'phone' => $this->lead->phone, 'source' => $this->lead->source->value, 'status' => $this->lead->status->value, 'created_at' => $this->lead->created_at?->toIso8601String()];
    }
}
