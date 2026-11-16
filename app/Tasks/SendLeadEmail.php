<?php

namespace App\Tasks;

use App\Models\Lead;
use App\Notifications\NewLeadNotification;
use App\Support\MailConfigurator;
use Illuminate\Support\Facades\Notification;

/** Emails the new lead to the sales inbox (auxtechbd@gmail.com by default). */
class SendLeadEmail
{
    /** @param array{lead_id: int} $payload */
    public function handle(array $payload): void
    {
        $lead = Lead::query()->find($payload['lead_id']);
        $to = setting('leads.notify_email');

        // Without mail credentials the lead is still stored and shown live in admin.
        if (! $lead || ! $to || ! MailConfigurator::configured()) {
            return;
        }

        MailConfigurator::apply();
        Notification::route('mail', $to)->notify(new NewLeadNotification($lead));
    }
}
