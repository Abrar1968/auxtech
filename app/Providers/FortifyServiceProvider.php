<?php

namespace App\Providers;

use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Only active users who hold a role can sign in.
        Fortify::authenticateUsing(function (Request $request): ?User {
            $user = User::query()->where('email', $request->input('email'))->first();

            if (! $user || ! $user->is_active || $user->roles->isEmpty() || ! Hash::check((string) $request->input('password'), $user->password)) {
                return null;
            }

            $user->forceFill(['last_login_at' => now()])->save();

            return $user;
        });

        $page = fn (string $component, array $props = []) => function (Request $request) use ($component, $props) {
            seo()->title('Admin')->noindex();

            return Inertia::render($component, $props + ['status' => $request->session()->get('status')]);
        };

        Fortify::loginView($page('Auth/Login'));
        Fortify::requestPasswordResetLinkView($page('Auth/ForgotPassword'));
        Fortify::twoFactorChallengeView($page('Auth/TwoFactorChallenge'));
        Fortify::confirmPasswordView($page('Auth/ConfirmPassword'));
        Fortify::resetPasswordView(function (Request $request) {
            seo()->title('Admin')->noindex();

            return Inertia::render('Auth/ResetPassword', ['token' => $request->route('token'), 'email' => $request->query('email')]);
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(Str::transliterate(Str::lower((string) $request->input(Fortify::username())).'|'.$request->ip()));
        });

        RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));
    }
}
