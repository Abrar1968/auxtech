<?php

namespace App\Tasks;

use App\Events\LeadCaptured;
use App\Models\Lead;
use App\Models\User;
use App\Notifications\NewLeadNotification;
use App\Support\Realtime;

/** In-app notification for admin users plus the live Pusher event. */
class NotifyAdminsOfLead
{
    /** @param array{lead_id: int} $payload */
    public function handle(array $payload): void
    {
        $lead = Lead::query()->find($payload['lead_id']);

        if (! $lead) {
            return;
        }

        User::query()->where('is_active', true)->get()
            ->filter(fn (User $user) => $user->can('leads.view'))
            ->each(fn (User $user) => $user->notify(new NewLeadNotification($lead)));

        Realtime::broadcast(new LeadCaptured($lead));
    }
}
