<?php

use App\Models\User;
use App\Services\Settings\SettingsRepository;
use Illuminate\Auth\Notifications\ResetPassword;
use Inertia\Testing\AssertableInertia as Assert;
use PragmaRX\Google2FA\Google2FA;

/** TC-A02 and the account page: password change, two-factor set-up, challenge and enforcement. */
function passwordConfirmed(): array
{
    return ['auth.password_confirmed_at' => time()];
}

it('shows the account page with the two-factor state', function () {
    $this->actingAs(admin())->get('/admin/profile')->assertInertia(fn (Assert $page) => $page
        ->component('Admin/Profile')
        ->where('twoFactor.enabled', false)
        ->where('twoFactor.confirmed', false)
        ->where('twoFactor.required', false));
});

it('changes the password only with the current password', function () {
    $user = admin();
    $user->forceFill(['password' => 'old-password-123'])->save();

    $this->actingAs($user)->put('/user/password', ['current_password' => 'wrong', 'password' => 'new-password-456', 'password_confirmation' => 'new-password-456'])
        ->assertSessionHasErrorsIn('updatePassword', 'current_password');

    $this->put('/user/password', ['current_password' => 'old-password-123', 'password' => 'new-password-456', 'password_confirmation' => 'new-password-456'])
        ->assertSessionHasNoErrors();

    expect(Hash::check('new-password-456', $user->fresh()->password))->toBeTrue();
});

it('asks for the password again before turning on two-factor sign-in', function () {
    $this->actingAs(admin())->post('/user/two-factor-authentication')->assertRedirect('/user/confirm-password');
    $this->get('/user/confirm-password')->assertInertia(fn (Assert $page) => $page->component('Auth/ConfirmPassword'));
});

it('sets up, confirms and then requires a code at sign-in', function () {
    $user = admin();
    $user->forceFill(['password' => 'a-long-password'])->save();
    $user = $user->fresh(); // as loaded on a real request, with every column

    $this->actingAs($user)->withSession(passwordConfirmed())->post('/user/two-factor-authentication')->assertRedirect();
    $user->refresh();
    expect($user->getAttributes()['two_factor_secret'])->not->toBeNull()->and($user->hasTwoFactor())->toBeFalse();

    $this->getJson('/user/two-factor-qr-code')->assertOk()->assertJsonStructure(['svg', 'url']);
    $codes = $this->getJson('/user/two-factor-recovery-codes')->assertOk()->json();
    expect($codes)->toHaveCount(8);

    $secret = decrypt($user->getAttributes()['two_factor_secret']);
    $this->post('/user/confirmed-two-factor-authentication', ['code' => '000000'])->assertSessionHasErrorsIn('confirmTwoFactorAuthentication', 'code');
    $this->post('/user/confirmed-two-factor-authentication', ['code' => (new Google2FA)->getCurrentOtp($secret)])->assertSessionHasNoErrors();
    expect($user->fresh()->hasTwoFactor())->toBeTrue();

    // Sign out, sign in: the password alone is not enough.
    auth()->logout();
    $this->flushSession();
    $this->post('/login', ['email' => $user->email, 'password' => 'a-long-password'])->assertRedirect('/two-factor-challenge');
    $this->assertGuest();
    $this->get('/two-factor-challenge')->assertInertia(fn (Assert $page) => $page->component('Auth/TwoFactorChallenge'));

    $this->post('/two-factor-challenge', ['code' => '123456'])->assertRedirect();
    $this->assertGuest();

    // A recovery code works once.
    $this->post('/login', ['email' => $user->email, 'password' => 'a-long-password']);
    $this->post('/two-factor-challenge', ['recovery_code' => $codes[0]])->assertRedirect('/admin');
    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->recoveryCodes())->not->toContain($codes[0]);
});

it('makes Super Admins and Admins set up two-factor when the setting requires it', function () {
    app(SettingsRepository::class)->set(['security.require_two_factor' => true]);
    $admin = admin('Admin');
    $sales = admin('Sales');

    $this->actingAs($admin)->get('/admin/leads')->assertRedirect('/admin/profile');
    $this->actingAs($admin)->get('/admin/profile')->assertOk()->assertInertia(fn (Assert $page) => $page->where('twoFactor.required', true));
    $this->actingAs($sales)->get('/admin/leads')->assertOk();

    $admin->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();
    $this->actingAs($admin->fresh())->get('/admin/leads')->assertOk();
});

it('marks notifications as read', function () {
    $user = admin('Sales');
    $this->postJson('/api/leads', leadPayload())->assertCreated();
    expect($user->unreadNotifications()->count())->toBe(1);

    $this->actingAs($user)->get('/admin')->assertInertia(fn (Assert $page) => $page->where('notifications.unread', 1)->has('notifications.items', 1));
    $this->post('/admin/notifications/read')->assertRedirect();

    expect($user->unreadNotifications()->count())->toBe(0);
});

it('lets a signed-out user request a reset link and set a new password', function () {
    Notification::fake();
    $user = User::factory()->create(['email' => 'staff@example.com']);

    $this->get('/forgot-password')->assertInertia(fn (Assert $page) => $page->component('Auth/ForgotPassword'));
    $this->post('/forgot-password', ['email' => 'staff@example.com'])->assertSessionHas('status');

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $this->get('/reset-password/'.$notification->token.'?email='.$user->email)
            ->assertInertia(fn (Assert $page) => $page->component('Auth/ResetPassword')->where('token', $notification->token));

        $this->post('/reset-password', ['token' => $notification->token, 'email' => $user->email, 'password' => 'brand-new-password', 'password_confirmation' => 'brand-new-password'])
            ->assertSessionHasNoErrors();

        return Hash::check('brand-new-password', $user->fresh()->password);
    });
});
