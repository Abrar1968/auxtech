<?php

use App\Admin\ModuleRegistry;
use App\Models\FailedTask;
use App\Support\Installation;
use App\Support\Installer;
use Illuminate\Support\Facades\Mail;

/** Failure paths of the admin tools and the installer (docs/04-TESTING.md §5 and §7.9). */
it('searches every admin list without a database error, including wildcards and Bangla', function (string $term) {
    $this->actingAs(admin());

    foreach (app(ModuleRegistry::class)->keys() as $key) {
        $this->get("/admin/{$key}?search=".urlencode($term))->assertOk();
    }
})->with(['gear', '%', 'ঢাকা', "O'Brien"]);

it('reports a test email that cannot be sent instead of failing', function () {
    Mail::shouldReceive('raw')->andThrow(new RuntimeException('Connection refused by smtp.gmail.com'));

    $this->actingAs(admin())->post('/admin/system/test-mail')
        ->assertSessionHas('error', 'Email could not be sent: Connection refused by smtp.gmail.com');
});

it('keeps a task that fails again and lets it be removed', function () {
    $missing = FailedTask::query()->create(['task' => 'App\\Tasks\\NoLongerExists', 'payload' => [], 'error' => 'x']);

    $this->actingAs(admin())->post("/admin/system/tasks/{$missing->id}/retry")->assertSessionHas('error', 'Task failed again.');
    expect($missing->fresh())->not->toBeNull();

    $this->delete("/admin/system/tasks/{$missing->id}")->assertSessionHas('success');
    expect(FailedTask::query()->count())->toBe(0);

    $this->actingAs(admin('Admin'))->delete("/admin/system/tasks/{$missing->id}")->assertNotFound();
});

describe('installer failures', function () {
    beforeEach(function () {
        config(['auxtech.installed' => false]);
        @unlink(Installation::lockFile());
    });

    afterEach(fn () => @unlink(Installation::lockFile()));

    $form = fn () => ['app_url' => 'https://auxtechbd.com', 'db_host' => '127.0.0.1', 'db_port' => 3306, 'db_name' => 'auxtech', 'db_user' => 'root', 'db_password' => '',
        'admin_name' => 'Owner', 'admin_email' => 'owner@example.com', 'admin_password' => 'a-long-password', 'admin_password_confirmation' => 'a-long-password'];

    it('refuses to start when the server is missing something', function () use ($form) {
        $this->mock(Installer::class, function ($mock) {
            $mock->shouldReceive('requirementsMet')->andReturnFalse();
            $mock->shouldReceive('requirements')->andReturn([['label' => 'PHP extension: gd', 'ok' => false, 'hint' => 'Turn on gd in cPanel']]);
            $mock->shouldNotReceive('run');
        });

        $this->post('/install', $form())->assertOk()->assertSee('Fix the items marked in red above');
    });

    it('cleans up and explains when the install stops half way', function () use ($form) {
        $env = storage_path('framework/testing-install.env');

        $this->mock(Installer::class, function ($mock) use ($env) {
            $mock->shouldReceive('requirementsMet')->andReturnTrue();
            $mock->shouldReceive('requirements')->andReturn([]);
            $mock->shouldReceive('databaseError')->andReturnNull();
            $mock->shouldReceive('environmentPath')->andReturn($env);
            $mock->shouldReceive('run')->andReturnUsing(function () use ($env) {
                file_put_contents($env, 'APP_KEY=partial');
                touch(Installation::lockFile());

                throw new RuntimeException('Table creation failed');
            });
        });

        $this->post('/install', $form())->assertStatus(500)->assertSee('Installation stopped: Table creation failed');

        expect(file_exists($env))->toBeFalse()->and(file_exists(Installation::lockFile()))->toBeFalse();
    });
});
