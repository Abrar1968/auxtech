<?php

use App\Enums\LeadStatus;
use App\Events\LeadUpdated;
use App\Models\FailedTask;
use App\Models\Lead;
use App\Models\Setting;
use App\Services\Settings\SettingsRepository;
use App\Tasks\SendLeadEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

function lead(array $attributes = []): Lead
{
    return Lead::query()->create($attributes + ['name' => 'Mahin', 'phone' => '+8801805019415', 'source' => 'contact_form', 'status' => 'new']);
}

it('lists, searches and filters leads', function () {
    lead(['name' => 'Mahin Rahman']);
    lead(['name' => 'Nusrat', 'phone' => '+8801711111111', 'status' => 'won', 'source' => 'estimator']);

    $this->actingAs(admin('Sales'));

    $this->get('/admin/leads')->assertInertia(fn (Assert $page) => $page->has('leads.data', 2)->where('counts.won', 1));
    $this->get('/admin/leads?status=won')->assertInertia(fn (Assert $page) => $page->has('leads.data', 1)->where('leads.data.0.name', 'Nusrat'));
    $this->get('/admin/leads?search=017111')->assertInertia(fn (Assert $page) => $page->has('leads.data', 1));
    $this->get('/admin/leads?source=estimator')->assertInertia(fn (Assert $page) => $page->has('leads.data', 1));
});

it('moves a lead through the pipeline with timestamps, notes and live updates', function () {
    Event::fake([LeadUpdated::class]);
    $user = admin('Sales');
    $lead = lead();

    $this->actingAs($user)->patch("/admin/leads/{$lead->id}", ['status' => 'contacted', 'assigned_to' => $user->id])->assertSessionHasNoErrors();
    expect($lead->fresh()->contacted_at)->not->toBeNull()->and($lead->fresh()->assigned_to)->toBe($user->id)->and($lead->fresh()->closed_at)->toBeNull();

    $this->patch("/admin/leads/{$lead->id}", ['status' => 'won']);
    expect($lead->fresh()->status)->toBe(LeadStatus::Won)->and($lead->fresh()->closed_at)->not->toBeNull();

    $this->patch("/admin/leads/{$lead->id}", ['status' => 'not-a-status'])->assertSessionHasErrors('status');

    $this->post("/admin/leads/{$lead->id}/notes", ['body' => 'Called, wants a <b>fashion</b> store.']);
    $this->get("/admin/leads/{$lead->id}")->assertInertia(fn (Assert $page) => $page
        ->where('lead.notes.0.body', 'Called, wants a fashion store.')
        ->where('lead.notes.0.user', $user->name)
        ->where('lead.whatsapp_url', 'https://wa.me/8801805019415'));

    Event::assertDispatched(LeadUpdated::class, 2);
});

it('exports leads as CSV that opens correctly in Excel', function () {
    lead(['name' => 'মাহিন রহমান', 'message' => '=HYPERLINK("http://evil","click")']);

    $csv = $this->actingAs(admin('Sales'))->get('/admin/leads/export')->assertOk()->streamedContent();

    expect($csv)->toStartWith("\xEF\xBB\xBF")
        ->toContain('মাহিন রহমান')
        ->toContain('+8801805019415')
        ->toContain("'=HYPERLINK");
});

it('lets only permitted roles delete leads', function () {
    $lead = lead();

    $this->actingAs(admin('Editor'))->delete("/admin/leads/{$lead->id}")->assertForbidden();
    $this->actingAs(admin('Admin'))->delete("/admin/leads/{$lead->id}")->assertRedirect('/admin/leads');

    expect(Lead::query()->count())->toBe(0)->and(Lead::withTrashed()->count())->toBe(1);
});

it('shows dashboard numbers that match the database', function () {
    lead();
    lead(['phone' => '+8801711111111', 'status' => 'won']);
    lead(['phone' => '+8801722222222', 'status' => 'spam']);
    lead(['phone' => '+8801733333333', 'created_at' => now()->subDays(45)]);
    $this->postJson('/api/events', ['type' => 'whatsapp_click', 'placement' => 'hero']);
    $this->postJson('/api/events', ['type' => 'whatsapp_click', 'placement' => 'hero']);
    $this->postJson('/api/events', ['type' => 'whatsapp_click', 'placement' => 'fab']);

    $this->actingAs(admin())->get('/admin')->assertInertia(fn (Assert $page) => $page
        ->where('kpis.leads.value', 2)
        ->where('kpis.leads.previous', 1)
        ->where('kpis.won.value', 1)
        ->where('kpis.whatsappClicks.value', 3)
        ->where('clicksByPlacement.hero', 2)
        ->where('health.mailConfigured', false)
        ->has('recentLeads', 4));
});

it('hides recent leads from roles without lead access', function () {
    lead();

    $this->actingAs(admin('Editor'))->get('/admin')->assertInertia(fn (Assert $page) => $page->has('recentLeads', 0));
});

it('saves settings, applies them to the site and ignores unknown keys', function () {
    $this->actingAs(admin('Admin'))->put('/admin/settings', ['values' => [
        'company.name' => 'Auxtech',
        'whatsapp.number' => '+880 1700-000000',
        'home.hero_title' => ['en' => 'New headline <script>x</script>', 'bn' => 'নতুন শিরোনাম'],
        'app.debug' => true,
    ]])->assertSessionHasNoErrors();

    expect(setting('whatsapp.number'))->toBe('8801700000000')
        ->and(Setting::query()->where('key', 'app.debug')->exists())->toBeFalse();

    $this->get('/')->assertInertia(fn (Assert $page) => $page->where('hero.title', 'New headline x')->where('site.whatsapp.number', '8801700000000'));
    $this->get('/bn')->assertInertia(fn (Assert $page) => $page->where('hero.title', 'নতুন শিরোনাম'));
});

it('stores secrets encrypted, never returns them, and keeps them when left blank', function () {
    $this->actingAs(admin('Admin'))->put('/admin/settings', ['values' => ['mail.password' => 'abcd efgh ijkl mnop']]);

    $stored = Setting::query()->where('key', 'mail.password')->value('value');

    expect($stored)->not->toContain('abcd')->and(setting('mail.password'))->toBe('abcd efgh ijkl mnop');

    $this->get('/admin/settings')->assertInertia(fn (Assert $page) => $page
        ->where('values', fn ($values) => $values['mail.password'] === null)
        ->where('secrets', fn ($secrets) => $secrets['mail.password'] === true))
        ->assertDontSee('abcd efgh', false);

    $this->put('/admin/settings', ['values' => ['mail.password' => '', 'company.name' => 'Auxtech']]);
    expect(app(SettingsRepository::class)->get('mail.password'))->toBe('abcd efgh ijkl mnop');
});

it('offers system tools to Super Admins: database update, cache clear, test email and task retry', function () {
    Mail::fake();
    $lead = lead();
    $task = FailedTask::query()->create(['task' => SendLeadEmail::class, 'payload' => ['lead_id' => $lead->id], 'error' => 'SMTP down']);

    $this->actingAs(admin())->get('/admin/system')->assertInertia(fn (Assert $page) => $page
        ->where('pendingMigrations', 0)->has('failedTasks', 1)->where('failedTasks.0.task', 'SendLeadEmail'));

    $this->post('/admin/system/migrate')->assertSessionHas('success');
    $this->post('/admin/system/clear-cache')->assertSessionHas('success');
    $this->post('/admin/system/test-mail')->assertSessionHas('success');
    $this->post("/admin/system/tasks/{$task->id}/retry")->assertSessionHas('success');

    expect(FailedTask::query()->count())->toBe(0);
});

it('shows the activity log to permitted roles', function () {
    $user = admin('Admin');
    $this->actingAs($user)->post('/admin/stats', ['value' => '5', 'label' => ['en' => 'Years'], 'is_active' => true]);

    $this->get('/admin/activity')->assertInertia(fn (Assert $page) => $page
        ->where('entries.data.0.description', 'Created Home Stat')->where('entries.data.0.user', $user->name));
});
