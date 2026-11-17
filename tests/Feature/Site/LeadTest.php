<?php

use App\Enums\LeadStatus;
use App\Events\LeadCaptured;
use App\Events\WhatsAppClicked;
use App\Models\ConversionEvent;
use App\Models\FailedTask;
use App\Models\Lead;
use App\Notifications\NewLeadNotification;
use App\Services\Settings\SettingsRepository;
use App\Support\AfterResponse;
use App\Tasks\SendLeadEmail;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

it('stores a lead, notifies the team and offers WhatsApp', function () {
    Notification::fake();
    Event::fake([LeadCaptured::class]);
    $sales = admin('Sales');

    $this->postJson('/api/leads', leadPayload(['email' => 'mahin@example.com', 'company' => 'Mahin Fashion']))
        ->assertCreated()
        ->assertJsonPath('ok', true)
        ->assertJsonPath('whatsapp_url', fn (string $url) => str_starts_with($url, 'https://wa.me/8801805019415?text='));

    $lead = Lead::query()->sole();

    expect($lead->phone)->toBe('+8801805019415')
        ->and($lead->status)->toBe(LeadStatus::New)
        ->and($lead->source->value)->toBe('contact_form')
        ->and($lead->company)->toBe('Mahin Fashion');

    // Email goes to the single Gmail inbox; admins with lead access get an in-app notification.
    Notification::assertSentTo(new AnonymousNotifiable, NewLeadNotification::class,
        fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'auxtechbd@gmail.com');
    Notification::assertSentTo($sales, NewLeadNotification::class);
    Event::assertDispatched(LeadCaptured::class, fn (LeadCaptured $event) => $event->broadcastAs() === 'lead.captured'
        && $event->broadcastOn()->name === 'private-admin');
});

it('rejects invalid submissions with field errors and stores nothing', function (array $overrides, string $field) {
    $this->postJson('/api/leads', leadPayload($overrides))->assertStatus(422)->assertJsonValidationErrors($field);

    expect(Lead::query()->count())->toBe(0);
})->with([
    'missing name' => [['name' => ''], 'name'],
    'missing phone' => [['phone' => ''], 'phone'],
    'short phone' => [['phone' => '0180501941'], 'phone'],
    'landline' => [['phone' => '029876543'], 'phone'],
    'bad email' => [['email' => 'not-an-email'], 'email'],
    'unknown source' => [['source' => 'whatever'], 'source'],
    'unknown service' => [['service_id' => 9999], 'service_id'],
]);

it('accepts every common way of writing a Bangladeshi number', function (string $phone) {
    $this->postJson('/api/leads', leadPayload(['phone' => $phone]))->assertCreated();

    expect(Lead::query()->sole()->phone)->toBe('+8801805019415');
})->with(['01805019415', '+8801805019415', '8801805019415', '01805-019415', '+880 1805 019415']);

it('silently drops submissions caught by the honeypot or the time trap', function (array $overrides) {
    if (($overrides['started_at'] ?? null) === 'now') {
        $overrides['started_at'] = now()->getTimestampMs();
    }

    $this->postJson('/api/leads', leadPayload($overrides))->assertCreated()->assertJsonPath('ok', true);

    expect(Lead::query()->count())->toBe(0);
})->with([
    'honeypot filled' => [['website' => 'https://spam.example']],
    'submitted in under two seconds' => [['started_at' => 'now']],
]);

it('throttles repeated submissions from one address', function () {
    foreach (range(1, 5) as $i) {
        $this->postJson('/api/leads', leadPayload(['phone' => '0180501941'.$i]))->assertCreated();
    }

    $this->postJson('/api/leads', leadPayload(['phone' => '01705019410']))->assertStatus(429);
});

it('stores first-touch attribution with the lead', function () {
    project(['slug' => 'gearzen']);

    $landing = $this->get('/work/gearzen?utm_source=facebook&utm_medium=cpc&utm_campaign=eid&fbclid=abc123', ['referer' => 'https://www.facebook.com/']);
    $cookies = collect($landing->headers->getCookies())->mapWithKeys(fn ($cookie) => [$cookie->getName() => $cookie->getValue()]);

    // A later visit without campaign parameters must not overwrite the first touch.
    $this->withUnencryptedCookies($cookies->all())->get('/about');

    // Browsers send cookies with fetch/XHR; the test client needs to be told to.
    $this->withCredentials()->withUnencryptedCookies($cookies->all())->postJson('/api/leads', leadPayload())->assertCreated();

    $lead = Lead::query()->sole();

    expect($lead->utm_source)->toBe('facebook')
        ->and($lead->utm_campaign)->toBe('eid')
        ->and($lead->fbclid)->toBe('abc123')
        ->and($lead->referrer)->toBe('https://www.facebook.com/')
        ->and($lead->landing_url)->toContain('/work/gearzen');
});

it('keeps the lead when email delivery fails and records the failure for retry', function () {
    app(SettingsRepository::class)->set(['mail.password' => 'app-password']);
    config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => '127.0.0.1', 'mail.mailers.smtp.port' => 1]);

    $this->postJson('/api/leads', leadPayload())->assertCreated();

    expect(Lead::query()->count())->toBe(1)
        ->and(FailedTask::query()->where('task', SendLeadEmail::class)->count())->toBe(1);
});

it('skips the email quietly when no mail password is configured in production', function () {
    $this->postJson('/api/leads', leadPayload())->assertCreated();

    Notification::fake();
    app()->detectEnvironment(fn () => 'production');

    expect(AfterResponse::execute(SendLeadEmail::class, ['lead_id' => Lead::query()->sole()->id]))->toBeTrue();

    Notification::assertNothingSent();
    expect(FailedTask::query()->count())->toBe(0);
});

it('adds a repeat submission from the same phone to the existing lead', function () {
    $this->postJson('/api/leads', leadPayload())->assertCreated();
    $this->postJson('/api/leads', leadPayload(['message' => 'Also need an app.']))->assertCreated();

    $lead = Lead::query()->sole();

    expect($lead->notes()->count())->toBe(1)
        ->and($lead->notes()->first()->body)->toContain('Also need an app.');
});

it('never stores a raw IP address', function () {
    $this->postJson('/api/leads', leadPayload(), ['REMOTE_ADDR' => '203.0.113.9'])->assertCreated();

    $lead = Lead::query()->sole();

    expect($lead->ip_hash)->toMatch('/^[a-f0-9]{64}$/')
        ->and(json_encode($lead->getAttributes()))->not->toContain('203.0.113.9');
});

it('stores estimator answers with the lead', function () {
    $this->postJson('/api/leads', leadPayload([
        'source' => 'estimator', 'budget_range' => '50k-100k', 'meta' => ['type' => 'ecommerce', 'timeline' => '1 month'],
    ]))->assertCreated();

    expect(Lead::query()->sole()->meta)->toBe(['type' => 'ecommerce', 'timeline' => '1 month']);
});

it('records CTA clicks with placement and device and broadcasts them', function () {
    Event::fake([WhatsAppClicked::class]);

    $this->postJson('/api/events', ['type' => 'whatsapp_click', 'placement' => 'hero', 'page_url' => 'https://auxtechbd.com/', 'locale' => 'bn'],
        ['User-Agent' => 'Mozilla/5.0 (Linux; Android 14) Mobile'])->assertCreated();
    $this->postJson('/api/events', ['type' => 'email_click', 'placement' => 'footer'])->assertCreated();
    $this->postJson('/api/events', ['type' => 'nonsense'])->assertStatus(422);

    $event = ConversionEvent::query()->orderBy('id')->first();

    expect(ConversionEvent::query()->count())->toBe(2)
        ->and($event->placement)->toBe('hero')
        ->and($event->device)->toBe('mobile')
        ->and($event->locale)->toBe('bn');

    Event::assertDispatched(WhatsAppClicked::class, 2);
});

it('accepts click beacons without a CSRF token', function () {
    $this->post('/api/events', ['type' => 'whatsapp_click', 'placement' => 'fab'])->assertCreated();
});

it('records the click and redirects to WhatsApp when JavaScript is off', function () {
    $this->get('/go/whatsapp?p=header&t=project&n=Gearzen')
        ->assertRedirect('https://wa.me/8801805019415?text='.rawurlencode('Hi Auxtech, I saw your work for Gearzen and want a similar website.'));

    expect(ConversionEvent::query()->sole()->placement)->toBe('header');
});

it('uses the WhatsApp number saved in settings everywhere', function () {
    app(SettingsRepository::class)->set(['whatsapp.number' => '8801700000000']);

    $this->get('/contact')->assertSee('https://wa.me/8801700000000', false)->assertDontSee('wa.me/8801805019415', false);
});

it('stores the in-app notification for admins and logs no failures', function () {
    $sales = admin('Sales');
    $editor = admin('Editor');

    $this->postJson('/api/leads', leadPayload())->assertCreated();

    expect($sales->notifications()->count())->toBe(1)
        ->and($sales->notifications()->first()->data['phone'])->toBe('+8801805019415')
        ->and($editor->notifications()->count())->toBe(0)
        ->and(FailedTask::query()->count())->toBe(0);
});
