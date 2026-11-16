<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
| Private channel for signed-in admin users: new leads, WhatsApp clicks and
| other live dashboard updates. Phase 1 narrows this to users who hold the
| relevant permission.
*/
Broadcast::channel('admin', fn (User $user): bool => true);
