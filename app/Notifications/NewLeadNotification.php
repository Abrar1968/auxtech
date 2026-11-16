<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeadNotification extends Notification
{
    public function __construct(public readonly Lead $lead) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return $notifiable instanceof User ? ['database'] : ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $lead = $this->lead;
        $digits = ltrim((string) $lead->phone, '+');

        $mail = (new MailMessage)
            ->subject('New lead: '.($lead->name ?: $lead->phone).' ('.str($lead->source->value)->replace('_', ' ')->title().')')
            ->greeting('New lead from the website')
            ->line('Name: '.($lead->name ?: '-'))
            ->line('Phone: '.($lead->phone ?: '-'))
            ->line('Email: '.($lead->email ?: '-'));

        if ($lead->company) {
            $mail->line('Company: '.$lead->company);
        }

        if ($lead->message) {
            $mail->line('Message: '.$lead->message);
        }

        if ($lead->page_url) {
            $mail->line('Page: '.$lead->page_url);
        }

        if ($lead->utm_source) {
            $mail->line('Source: '.$lead->utm_source.' / '.($lead->utm_medium ?: '-').' / '.($lead->utm_campaign ?: '-'));
        }

        if ($lead->email) {
            $mail->replyTo($lead->email, $lead->name);
        }

        if ($digits !== '') {
            $mail->action('Reply on WhatsApp', 'https://wa.me/'.$digits);
        }

        return $mail->line('Open the admin panel to manage this lead: '.url('/admin/leads/'.$lead->id));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'name' => $this->lead->name,
            'phone' => $this->lead->phone,
            'source' => $this->lead->source->value,
        ];
    }
}
