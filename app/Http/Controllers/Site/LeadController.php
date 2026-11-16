<?php

namespace App\Http\Controllers\Site;

use App\Actions\Leads\CaptureLead;
use App\Actions\Tracking\RecordConversionEvent;
use App\Enums\EventType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Services\WhatsApp\LinkBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request, CaptureLead $capture, LinkBuilder $whatsapp): JsonResponse
    {
        $locale = (string) $request->input('locale', 'en');

        // Spam gets the same friendly answer, but nothing is stored.
        if ($request->looksLikeSpam()) {
            return response()->json(['ok' => true, 'whatsapp_url' => $whatsapp->url('default', null, $locale)], 201);
        }

        $lead = $capture->handle($request->validated(), $request);

        return response()->json([
            'ok' => true,
            'lead' => $lead->uuid,
            'whatsapp_url' => $whatsapp->url('default', null, $locale),
        ], 201);
    }

    /** Beacon endpoint for CTA clicks and form steps. */
    public function event(Request $request, RecordConversionEvent $record): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(EventType::values())],
            'placement' => ['nullable', 'string', 'max:60'],
            'page_url' => ['nullable', 'string', 'max:500'],
            'locale' => ['nullable', 'string', 'max:5'],
        ]);

        $record->handle($data, $request);

        return response()->json(['ok' => true], 201);
    }

    /** No-JavaScript route to WhatsApp that still records the click. */
    public function whatsapp(Request $request, RecordConversionEvent $record, LinkBuilder $whatsapp): RedirectResponse
    {
        $template = in_array($request->query('t'), ['default', 'service', 'solution', 'project', 'plan', 'estimator'], true)
            ? (string) $request->query('t') : 'default';

        $record->handle([
            'type' => EventType::WhatsAppClick->value,
            'placement' => (string) $request->query('p', 'link'),
            'locale' => (string) $request->query('l', 'en'),
        ], $request);

        return redirect()->away($whatsapp->url($template, str((string) $request->query('n'))->limit(80, '')->toString() ?: null, (string) $request->query('l', 'en')));
    }
}
