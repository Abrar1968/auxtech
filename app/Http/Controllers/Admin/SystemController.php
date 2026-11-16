<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedTask;
use App\Support\AfterResponse;
use App\Support\Installation;
use App\Support\MailConfigurator;
use App\Support\Realtime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

/** Maintenance tools that replace shell access on shared hosting. */
class SystemController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorizeSystem($request);

        return Inertia::render('Admin/System', [
            'version' => Installation::version(),
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'database' => config('database.default'),
            'pendingMigrations' => $this->pendingMigrations(),
            'mailConfigured' => filled(setting('mail.password')),
            'realtime' => Realtime::enabled(),
            'failedTasks' => FailedTask::query()->latest('id')->limit(50)->get()->map(fn (FailedTask $t) => [
                'id' => $t->id, 'task' => class_basename($t->task), 'error' => $t->error,
                'attempts' => $t->attempts, 'created_at' => $t->created_at?->toIso8601String(),
            ]),
        ]);
    }

    /** Applies database changes after a newer release zip has been extracted. */
    public function migrate(Request $request): RedirectResponse
    {
        $this->authorizeSystem($request);

        Artisan::call('migrate', ['--force' => true]);
        $this->clearCaches();

        return back()->with('success', 'Database is up to date.');
    }

    public function clearCache(Request $request): RedirectResponse
    {
        $this->authorizeSystem($request);
        $this->clearCaches();

        return back()->with('success', 'Caches cleared.');
    }

    public function testMail(Request $request): RedirectResponse
    {
        $this->authorizeSystem($request);

        try {
            MailConfigurator::apply();
            Mail::raw('This is a test email from the Auxtech website. Lead notifications will arrive at this address.', function ($message): void {
                $message->to((string) setting('leads.notify_email'))->subject('Auxtech website: test email');
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Email could not be sent: '.$e->getMessage());
        }

        return back()->with('success', 'Test email sent to '.setting('leads.notify_email').'.');
    }

    public function retryTask(Request $request, FailedTask $task): RedirectResponse
    {
        $this->authorizeSystem($request);

        $ok = class_exists($task->task) && AfterResponse::execute($task->task, (array) $task->payload, $task);

        return back()->with($ok ? 'success' : 'error', $ok ? 'Task completed.' : 'Task failed again.');
    }

    public function forgetTask(Request $request, FailedTask $task): RedirectResponse
    {
        $this->authorizeSystem($request);
        $task->delete();

        return back()->with('success', 'Task removed.');
    }

    private function authorizeSystem(Request $request): void
    {
        abort_unless($request->user()->can('system.manage'), 403);
    }

    private function pendingMigrations(): int
    {
        try {
            $ran = app('migration.repository')->getRan();
            $files = app('migrator')->getMigrationFiles(database_path('migrations'));

            return count(array_diff(array_keys($files), $ran));
        } catch (Throwable) {
            return 0;
        }
    }

    private function clearCaches(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
    }
}
