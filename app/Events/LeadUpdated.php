<?php

namespace App\Events;

use App\Models\Lead;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/** Live update for the admin panel, sent through Pusher. */
class LeadUpdated implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(public readonly Lead $lead) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('admin');
    }

    public function broadcastAs(): string
    {
        return 'lead.updated';
    }

    /** @return array<string, mixed> */
    public function broadcastWith(): array
    {
        return ['id' => $this->lead->id, 'status' => $this->lead->status->value, 'assigned_to' => $this->lead->assigned_to];
    }
}
