<?php

namespace App\Support;

/** Broadcasts only when Pusher is fully configured, so a missing secret never raises errors. */
final class Realtime
{
    public static function enabled(): bool
    {
        $connection = config('broadcasting.default');

        if ($connection !== 'pusher') {
            return in_array($connection, ['log', 'reverb', 'ably'], true);
        }

        return filled(config('broadcasting.connections.pusher.key'))
            && filled(config('broadcasting.connections.pusher.secret'))
            && filled(config('broadcasting.connections.pusher.app_id'));
    }

    public static function broadcast(object $event): void
    {
        if (self::enabled()) {
            broadcast($event);
        }
    }
}
