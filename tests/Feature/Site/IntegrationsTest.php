<?php

use App\Events\LeadUpdated;
use App\Models\FailedTask;
use App\Models\Lead;
use App\Services\Settings\SettingsRepository;
use App\Support\AfterResponse;
use App\Tasks\NotifyAdminsOfLead;
use App\Tasks\SendMetaLeadEvent;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Http;

/** Meta Conversions API, live updates and what happens when an outside service fails (TC-N10). */
it('sends a server-side Lead event to Meta with hashed contact details', function () {
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1])]);
    app(SettingsRepository::class)->set(['tracking.meta_pixel_id' => '123456', 'tracking.meta_capi_token' => 'token-abc']);

    $this->postJson('/api/leads', leadPayload(['email' => 'Buyer@Example.com']))->assertCreated();
    $lead = Lead::query()->sole();

    Http::assertSent(function ($request) use ($lead) {
        $event = $request['data'][0];

        return str_contains($request->url(), 'graph.facebook.com/v21.0/123456/events')
            && $request['access_token'] === 'token-abc'
            && $event['event_name'] === 'Lead'
            && $event['event_id'] === $lead->uuid
            && $event['user_data']['ph'] === [hash('sha256', '8801805019415')]
            && $event['user_data']['em'] === [hash('sha256', 'buyer@example.com')]
            && ! str_contains(json_encode($request->data()), '01805019415');
    });
});

it('does not call Meta when no pixel or token is configured', function () {
    Http::fake();

    $this->postJson('/api/leads', leadPayload())->assertCreated();

    Http::assertNothingSent();
});

it('keeps the lead and records a retryable failure when Meta is down', function () {
    Http::fake(['graph.facebook.com/*' => Http::sequence()->push('error', 500)->push(['events_received' => 1])]);
    app(SettingsRepository::class)->set(['tracking.meta_pixel_id' => '123456', 'tracking.meta_capi_token' => 'token-abc']);

    $this->postJson('/api/leads', leadPayload())->assertCreated();

    expect(Lead::query()->count())->toBe(1)
        ->and(FailedTask::query()->where('task', SendMetaLeadEvent::class)->count())->toBe(1);

    // Retrying after Meta recovers clears the failure.
    $task = FailedTask::query()->sole();
    expect(AfterResponse::execute($task->task, $task->payload, $task))->toBeTrue()
        ->and(FailedTask::query()->count())->toBe(0);
});

it('keeps the lead when the live-update service is unreachable', function () {
    config(['broadcasting.default' => 'pusher', 'broadcasting.connections.pusher.key' => 'k', 'broadcasting.connections.pusher.secret' => 's',
        'broadcasting.connections.pusher.app_id' => '1', 'broadcasting.connections.pusher.options.host' => '127.0.0.1', 'broadcasting.connections.pusher.options.port' => 1,
        'broadcasting.connections.pusher.options.scheme' => 'http']);
    Broadcast::forgetDrivers();

    $this->postJson('/api/leads', leadPayload())->assertCreated();

    expect(Lead::query()->count())->toBe(1)
        ->and(FailedTask::query()->where('task', NotifyAdminsOfLead::class)->count())->toBe(1);
});

it('broadcasts lead changes to the admin channel', function () {
    $lead = Lead::query()->create(['name' => 'X', 'phone' => '+8801805019415', 'source' => 'contact_form', 'status' => 'new']);
    $event = new LeadUpdated($lead);

    expect($event->broadcastOn()->name)->toBe('private-admin')
        ->and($event->broadcastAs())->toBe('lead.updated')
        ->and($event->broadcastWith())->toBe(['id' => $lead->id, 'status' => 'new', 'assigned_to' => null]);
});
