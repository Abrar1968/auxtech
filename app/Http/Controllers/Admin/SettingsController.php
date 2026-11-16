<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingsRepository;
use App\Support\SiteCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function __construct(private readonly SettingsRepository $settings) {}

    public function edit(Request $request): Response
    {
        abort_unless($request->user()->can('settings.view'), 403);

        $values = [];

        foreach ($this->settings->all() as $key => $value) {
            // Secrets are write-only: the browser only learns whether one is set.
            $values[$key] = $this->settings->isSecret($key) ? null : $value;
        }

        return Inertia::render('Admin/Settings', [
            'values' => $values,
            'secrets' => collect(config('settings.secret'))->mapWithKeys(fn (string $key) => [$key => filled($this->settings->get($key))]),
            'groups' => collect(array_keys(config('settings.defaults')))->groupBy(fn (string $key) => str($key)->before('.')->toString()),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('settings.update'), 403);

        $data = $request->validate([
            'values' => ['required', 'array'],
            'values.*' => ['nullable'],
            'values.company.name' => ['sometimes', 'required', 'string', 'max:120'],
        ]);

        $clean = [];

        foreach ($data['values'] as $key => $value) {
            if (! is_string($key) || ! array_key_exists($key, config('settings.defaults'))) {
                continue;
            }

            $clean[$key] = $this->sanitize($key, $value);
        }

        $this->settings->set($clean);
        SiteCache::flush();

        activity()->causedBy($request->user())->event('updated')
            ->withProperties(['keys' => array_values(array_diff(array_keys($clean), config('settings.secret')))])
            ->log('Updated Settings');

        return back()->with('success', 'Settings saved.');
    }

    private function sanitize(string $key, mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($item) => is_array($item) ? $this->sanitize($key, $item) : $this->scalar($item), $value);
        }

        if ($key === 'whatsapp.number') {
            return preg_replace('/\D+/', '', (string) $value);
        }

        return $this->scalar($value);
    }

    private function scalar(mixed $value): mixed
    {
        return is_string($value) ? trim(strip_tags($value)) : $value;
    }
}
