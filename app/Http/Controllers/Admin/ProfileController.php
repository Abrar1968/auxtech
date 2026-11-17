<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * "My account": password and two-factor sign-in. The actions themselves
 * are Fortify endpoints (/user/password, /user/two-factor-*).
 */
class ProfileController extends Controller
{
    public function show(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Admin/Profile', [
            'twoFactor' => [
                'enabled' => filled($user->getAttributes()['two_factor_secret'] ?? null),
                'confirmed' => $user->hasTwoFactor(),
                'required' => (bool) setting('security.require_two_factor') && $user->hasAnyRole(['Super Admin', 'Admin']),
            ],
            'status' => $request->session()->get('status'),
        ]);
    }

    public function readNotifications(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}
