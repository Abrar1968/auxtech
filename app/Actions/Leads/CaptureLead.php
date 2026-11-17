<?php

namespace App\Actions\Leads;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Http\Middleware\CaptureAttribution;
use App\Models\Lead;
use App\Support\AfterResponse;
use App\Support\Phone;
use App\Tasks\NotifyAdminsOfLead;
use App\Tasks\SendLeadEmail;
use App\Tasks\SendMetaLeadEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Stores a lead first, then notifies after the response. Nothing that can
 * fail externally runs before the lead is safely in the database.
 */
class CaptureLead
{
    /** @param array<string, mixed> $data validated input */
    public function handle(array $data, Request $request): Lead
    {
        $phone = Phone::normalize($data['phone'] ?? null);

        $lead = DB::transaction(function () use ($data, $request, $phone): Lead {
            $recent = $phone ? Lead::query()
                ->where('phone', $phone)
                ->where('status', '!=', LeadStatus::Spam->value)
                ->where('created_at', '>=', now()->subDay())
                ->latest('id')
                // Identical submissions arriving together (a double tap) queue here instead of deadlocking.
                ->lockForUpdate()
                ->first() : null;

            // The same person writing again within a day adds to their lead.
            if ($recent) {
                $recent->notes()->create(['body' => trim('Submitted again: '.($data['message'] ?? '(no message)'))]);
                $recent->touch();

                return $recent;
            }

            return Lead::query()->create([
                'name' => $data['name'] ?? null,
                'phone' => $phone,
                'email' => $data['email'] ?? null,
                'company' => $data['company'] ?? null,
                'message' => $data['message'] ?? null,
                'service_id' => $data['service_id'] ?? null,
                'solution_id' => $data['solution_id'] ?? null,
                'project_id' => $data['project_id'] ?? null,
                'budget_range' => $data['budget_range'] ?? null,
                'meta' => $data['meta'] ?? null,
                'source' => $data['source'] ?? LeadSource::ContactForm->value,
                'status' => LeadStatus::New,
                'locale' => in_array($data['locale'] ?? null, config('auxtech.locales.supported'), true) ? $data['locale'] : 'en',
                'page_url' => Str::limit((string) ($data['page_url'] ?? $request->headers->get('referer', '')), 480, '') ?: null,
                'ip_hash' => self::hashIp($request->ip()),
                'user_agent' => Str::limit((string) $request->userAgent(), 480, ''),
                ...CaptureAttribution::read($request),
            ]);
        }, attempts: 3); // MariaDB can still pick a deadlock victim; a retry then finds the first lead.

        if ($lead->wasRecentlyCreated) {
            AfterResponse::run(NotifyAdminsOfLead::class, ['lead_id' => $lead->id]);
            AfterResponse::run(SendLeadEmail::class, ['lead_id' => $lead->id]);
            AfterResponse::run(SendMetaLeadEvent::class, ['lead_id' => $lead->id, 'ip' => $request->ip()]);
        }

        return $lead;
    }

    /** Raw IP addresses are never stored. */
    public static function hashIp(?string $ip): ?string
    {
        return $ip ? hash_hmac('sha256', $ip, (string) config('app.key')) : null;
    }
}
