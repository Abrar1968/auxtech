<?php

namespace App\Http\Controllers;

use App\Support\Installation;
use App\Support\Installer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;

/**
 * One-page browser installer for shared hosting. It disappears (404)
 * as soon as the site is installed.
 */
class InstallController extends Controller
{
    public function __construct(private readonly Installer $installer) {}

    public function show(Request $request): Response
    {
        abort_if(Installation::done(), 404);

        return $this->page($request);
    }

    public function store(Request $request): Response
    {
        abort_if(Installation::done(), 404);

        if (! $this->installer->requirementsMet()) {
            return $this->page($request, ['Fix the items marked in red above, then try again.']);
        }

        $validator = Validator::make($request->all(), [
            'app_url' => ['required', 'url:http,https', 'max:255'],
            'db_host' => ['required', 'string', 'max:255'],
            'db_port' => ['required', 'integer', 'between:1,65535'],
            'db_name' => ['required', 'string', 'max:64'],
            'db_user' => ['required', 'string', 'max:64'],
            'db_password' => ['nullable', 'string', 'max:255'],
            'admin_name' => ['required', 'string', 'max:120'],
            'admin_email' => ['required', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'confirmed', Password::min(10)],
            'mail_password' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->page($request, $validator->errors()->all(), 422);
        }

        $data = $validator->validated();

        $db = [
            'driver' => 'mysql',
            'host' => $data['db_host'],
            'port' => (int) $data['db_port'],
            'database' => $data['db_name'],
            'username' => $data['db_user'],
            'password' => $data['db_password'] ?? '',
        ];

        if ($error = $this->installer->databaseError($db)) {
            return $this->page($request, [$error], 422);
        }

        try {
            $this->installer->run(
                $db,
                ['name' => $data['admin_name'], 'email' => $data['admin_email'], 'password' => $data['admin_password']],
                $data['app_url'],
                // Google shows app passwords in groups of four separated by spaces.
                isset($data['mail_password']) ? str_replace(' ', '', $data['mail_password']) : null,
            );
        } catch (Throwable $e) {
            report($e);
            // Leave nothing half-installed, so the form can simply be submitted again.
            @unlink($this->installer->environmentPath());
            @unlink(Installation::lockFile());

            return $this->page($request, ['Installation stopped: '.$e->getMessage()], 500);
        }

        return response()->view('install', ['done' => true, 'loginUrl' => rtrim($data['app_url'], '/').'/admin', 'adminEmail' => $data['admin_email']]);
    }

    /** @param list<string> $errors */
    private function page(Request $request, array $errors = [], int $status = 200): Response
    {
        return response()->view('install', [
            'done' => false,
            'checks' => $this->installer->requirements(),
            'ready' => $this->installer->requirementsMet(),
            'errors' => $errors,
            'old' => $request->except(['db_password', 'admin_password', 'admin_password_confirmation', 'mail_password']) + [
                'app_url' => $request->getSchemeAndHttpHost(),
                'db_host' => 'localhost',
                'db_port' => 3306,
                'admin_email' => config('auxtech.company.email'),
            ],
        ], $status);
    }
}
