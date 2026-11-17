<?php

use App\Admin\ModuleRegistry;
use App\Models\User;
use Illuminate\Broadcasting\BroadcastManager;

it('sends guests to the sign-in page', function (string $path) {
    $this->get($path)->assertRedirect('/login');
})->with(['/admin', '/admin/projects', '/admin/leads', '/admin/settings', '/admin/system', '/admin/users']);

it('signs in an active admin and records the login time', function () {
    $user = admin();
    $user->forceFill(['password' => 'correct-horse-battery'])->save();
    auth()->logout();

    $this->post('/login', ['email' => $user->email, 'password' => 'correct-horse-battery'])->assertRedirect('/admin');

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->last_login_at)->not->toBeNull();
});

it('refuses wrong passwords, inactive users and users without a role', function () {
    $active = admin();
    $active->forceFill(['password' => 'correct-horse-battery'])->save();
    $inactive = User::factory()->create(['is_active' => false, 'password' => 'correct-horse-battery']);
    $inactive->assignRole('Admin');
    $roleless = User::factory()->create(['password' => 'correct-horse-battery']);

    $this->post('/login', ['email' => $active->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
    $this->post('/login', ['email' => $inactive->email, 'password' => 'correct-horse-battery'])->assertSessionHasErrors('email');
    $this->post('/login', ['email' => $roleless->email, 'password' => 'correct-horse-battery'])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('throttles repeated sign-in attempts', function () {
    $user = admin();

    foreach (range(1, 5) as $i) {
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
    }

    $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertStatus(429);
});

it('has no public registration', function () {
    $this->get('/register')->assertNotFound();
    $this->post('/register', ['name' => 'X', 'email' => 'x@example.com', 'password' => 'secret-secret', 'password_confirmation' => 'secret-secret'])->assertStatus(405);

    expect(User::query()->count())->toBe(0);
});

it('signs out a user who was deactivated while signed in', function () {
    $user = admin('Admin');
    $this->actingAs($user)->get('/admin')->assertOk();

    $user->update(['is_active' => false]);

    $this->get('/admin')->assertRedirect('/login');
});

it('enforces the role matrix', function (string $role, array $allowed, array $forbidden) {
    $user = admin($role);

    foreach ($allowed as $path) {
        $this->actingAs($user)->get($path)->assertOk();
    }

    foreach ($forbidden as $path) {
        $this->actingAs($user)->get($path)->assertForbidden();
    }
})->with([
    'Super Admin' => ['Super Admin', ['/admin', '/admin/projects', '/admin/leads', '/admin/settings', '/admin/users', '/admin/system', '/admin/activity', '/admin/redirects'], []],
    'Admin' => ['Admin', ['/admin', '/admin/projects', '/admin/leads', '/admin/settings', '/admin/activity', '/admin/redirects'], ['/admin/users', '/admin/system']],
    'Editor' => ['Editor', ['/admin', '/admin/projects', '/admin/posts', '/admin/redirects', '/admin/seo-report'], ['/admin/leads', '/admin/settings', '/admin/users', '/admin/system', '/admin/activity']],
    'Sales' => ['Sales', ['/admin', '/admin/leads'], ['/admin/projects', '/admin/settings', '/admin/users', '/admin/system', '/admin/redirects']],
]);

it('blocks writes that a role may not make', function () {
    $this->actingAs(admin('Sales'))
        ->post('/admin/projects', ['title' => ['en' => 'Nope'], 'type' => 'live', 'status' => 'draft'])
        ->assertForbidden();

    $this->actingAs(admin('Editor'))->put('/admin/settings', ['values' => ['company.name' => 'Hacked']])->assertForbidden();
    $this->actingAs(admin('Editor'))->get('/admin/leads/export')->assertForbidden();
});

it('renders the list and create screens of every module', function (string $module) {
    $user = admin();

    $this->actingAs($user)->get("/admin/{$module}")->assertOk();
    $this->actingAs($user)->get("/admin/{$module}/create")->assertOk();
})->with(fn () => array_map(fn (string $class) => (new $class)->key(), (require __DIR__.'/../../../config/admin.php')['modules']));

it('returns 404 for unknown modules', function () {
    $this->actingAs(admin())->get('/admin/not-a-module')->assertNotFound();
});

it('only lets signed-in admins join the private live-updates channel', function () {
    config(['broadcasting.default' => 'pusher', 'broadcasting.connections.pusher.key' => 'key', 'broadcasting.connections.pusher.secret' => 'secret', 'broadcasting.connections.pusher.app_id' => '1']);
    app()->forgetInstance(BroadcastManager::class);
    require base_path('routes/channels.php');

    $this->post('/broadcasting/auth', ['channel_name' => 'private-admin', 'socket_id' => '1.1'])->assertForbidden();

    $this->actingAs(admin('Sales'))->post('/broadcasting/auth', ['channel_name' => 'private-admin', 'socket_id' => '1.1'])
        ->assertOk()->assertJsonStructure(['auth']);
});

it('registers a module for every configured class', function () {
    expect(app(ModuleRegistry::class)->keys())->toHaveCount(count(config('admin.modules')))->toContain('projects', 'users', 'redirects');
});
