<?php

namespace App\Support;

use App\Models\FailedTask;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Runs slow work (email, Pusher, Meta) after the HTTP response has been
 * sent. Shared hosting has no queue worker, so this replaces a queue.
 * A failure is stored in failed_tasks and can be retried from the admin.
 */
final class AfterResponse
{
    /**
     * @param  class-string  $task  invokable class with handle(array $payload)
     * @param  array<string, mixed>  $payload
     */
    public static function run(string $task, array $payload = []): void
    {
        defer(fn () => self::execute($task, $payload))->always();
    }

    /** @param array<string, mixed> $payload */
    public static function execute(string $task, array $payload = [], ?FailedTask $retryOf = null): bool
    {
        try {
            app($task)->handle($payload);
            $retryOf?->delete();

            return true;
        } catch (Throwable $e) {
            Log::warning("After-response task failed: {$task}", ['error' => $e->getMessage()]);

            try {
                if ($retryOf) {
                    $retryOf->update(['error' => $e->getMessage(), 'attempts' => $retryOf->attempts + 1]);
                } else {
                    FailedTask::query()->create(['task' => $task, 'payload' => $payload, 'error' => $e->getMessage()]);
                }
            } catch (Throwable) {
                // Recording the failure must never break the request.
            }

            return false;
        }
    }
}
