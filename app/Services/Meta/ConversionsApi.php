<?php

namespace App\Services\Meta;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;

/** Server-side Meta Conversions API. Active only when a pixel ID and token are saved in settings. */
class ConversionsApi
{
    public function enabled(): bool
    {
        return filled(setting('tracking.meta_pixel_id')) && filled(setting('tracking.meta_capi_token'));
    }

    public function lead(Lead $lead, ?string $ip = null): void
    {
        $hash = fn (?string $value): ?string => filled($value) ? hash('sha256', strtolower(trim((string) $value))) : null;

        Http::timeout(8)->asJson()->post(
            'https://graph.facebook.com/v21.0/'.setting('tracking.meta_pixel_id').'/events',
            [
                'access_token' => setting('tracking.meta_capi_token'),
                'data' => [[
                    'event_name' => 'Lead',
                    'event_time' => $lead->created_at?->timestamp ?? time(),
                    // Shared with the browser Pixel event so Meta can de-duplicate.
                    'event_id' => $lead->uuid,
                    'action_source' => 'website',
                    'event_source_url' => $lead->page_url,
                    'user_data' => array_filter([
                        'ph' => [$hash(ltrim((string) $lead->phone, '+'))],
                        'em' => $lead->email ? [$hash($lead->email)] : null,
                        'client_ip_address' => $ip,
                        'client_user_agent' => $lead->user_agent,
                    ]),
                ]],
            ],
        )->throw();
    }
}
