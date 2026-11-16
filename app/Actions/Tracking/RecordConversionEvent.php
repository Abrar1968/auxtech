<?php

namespace App\Actions\Tracking;

use App\Enums\EventType;
use App\Http\Middleware\CaptureAttribution;
use App\Models\ConversionEvent;
use App\Support\AfterResponse;
use App\Tasks\BroadcastConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecordConversionEvent
{
    /** @param array{type: string, placement?: string|null, page_url?: string|null, locale?: string|null} $data */
    public function handle(array $data, Request $request): ConversionEvent
    {
        $attribution = CaptureAttribution::read($request);
        $agent = strtolower((string) $request->userAgent());

        $event = ConversionEvent::query()->create([
            'type' => $data['type'],
            'placement' => Str::limit((string) ($data['placement'] ?? ''), 60, '') ?: null,
            'page_url' => Str::limit((string) ($data['page_url'] ?? $request->headers->get('referer', '')), 480, '') ?: null,
            'visitor_id' => is_string($request->cookie(CaptureAttribution::VISITOR_COOKIE)) ? Str::limit($request->cookie(CaptureAttribution::VISITOR_COOKIE), 64, '') : null,
            'locale' => in_array($data['locale'] ?? null, config('auxtech.locales.supported'), true) ? $data['locale'] : 'en',
            'device' => match (true) {
                str_contains($agent, 'ipad') || str_contains($agent, 'tablet') => 'tablet',
                str_contains($agent, 'mobi') || str_contains($agent, 'android') => 'mobile',
                default => 'desktop',
            },
            'utm_source' => $attribution['utm_source'] ?? null,
            'utm_medium' => $attribution['utm_medium'] ?? null,
            'utm_campaign' => $attribution['utm_campaign'] ?? null,
            'created_at' => now(),
        ]);

        if (in_array($event->type, [EventType::WhatsAppClick, EventType::EmailClick], true)) {
            AfterResponse::run(BroadcastConversion::class, ['event_id' => $event->id]);
        }

        return $event;
    }
}
