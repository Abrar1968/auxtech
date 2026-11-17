<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Events\LeadUpdated;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use App\Support\Realtime;
use App\Support\Search;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()->can('leads.view'), 403);

        return Inertia::render('Admin/Leads/Index', [
            'leads' => $this->filtered($request)->with('assignee:id,name')->latest('id')->paginate(30)->withQueryString()
                ->through(fn (Lead $lead) => $this->summary($lead)),
            'query' => $request->only(['search', 'status', 'source', 'assigned_to']),
            'statuses' => LeadStatus::values(),
            'sources' => LeadSource::values(),
            'counts' => Lead::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'users' => $this->assignees(),
        ]);
    }

    public function show(Request $request, Lead $lead): Response
    {
        abort_unless($request->user()->can('leads.view'), 403);

        $lead->load(['notes.user:id,name', 'assignee:id,name', 'service', 'solution', 'project']);

        return Inertia::render('Admin/Leads/Show', [
            'lead' => $this->summary($lead) + [
                'email' => $lead->email,
                'company' => $lead->company,
                'message' => $lead->message,
                'budget_range' => $lead->budget_range,
                'meta' => $lead->meta,
                'locale' => $lead->locale,
                'page_url' => $lead->page_url,
                'landing_url' => $lead->landing_url,
                'referrer' => $lead->referrer,
                'utm' => $lead->only(['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid']),
                'service' => $lead->service?->title,
                'solution' => $lead->solution?->name,
                'project' => $lead->project?->title,
                'whatsapp_url' => $lead->phone ? 'https://wa.me/'.ltrim($lead->phone, '+') : null,
                'contacted_at' => $lead->contacted_at?->toIso8601String(),
                'closed_at' => $lead->closed_at?->toIso8601String(),
                'notes' => $lead->notes->map(fn ($note) => [
                    'id' => $note->id, 'body' => $note->body, 'user' => $note->user?->name,
                    'created_at' => $note->created_at?->toIso8601String(),
                ]),
            ],
            'statuses' => LeadStatus::values(),
            'users' => $this->assignees(),
        ]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()->can('leads.update'), 403);

        $data = $request->validate([
            'status' => ['sometimes', Rule::in(LeadStatus::values())],
            'assigned_to' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
        ]);

        $lead->fill($data);

        if ($lead->isDirty('status')) {
            if ($lead->status !== LeadStatus::New && $lead->contacted_at === null) {
                $lead->contacted_at = now();
            }

            $lead->closed_at = in_array($lead->status, [LeadStatus::Won, LeadStatus::Lost], true) ? now() : null;
        }

        $changed = array_keys($lead->getDirty());
        $lead->save();

        if ($changed !== []) {
            activity()->causedBy($request->user())->performedOn($lead)->event('updated')
                ->withProperties(['changed' => $changed])->log('Updated Lead');
            Realtime::broadcast(new LeadUpdated($lead));
        }

        return back()->with('success', 'Lead updated.');
    }

    public function note(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()->can('leads.update'), 403);

        $data = $request->validate(['body' => ['required', 'string', 'max:3000']]);
        $lead->notes()->create(['body' => strip_tags($data['body']), 'user_id' => $request->user()->id]);

        return back()->with('success', 'Note added.');
    }

    public function destroy(Request $request, Lead $lead): RedirectResponse
    {
        abort_unless($request->user()->can('leads.delete'), 403);

        $lead->delete();
        activity()->causedBy($request->user())->performedOn($lead)->event('deleted')->log('Deleted Lead');

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        abort_unless($request->user()->can('leads.export'), 403);

        $columns = ['id', 'created_at', 'name', 'phone', 'email', 'company', 'source', 'status', 'message', 'budget_range',
            'utm_source', 'utm_medium', 'utm_campaign', 'landing_url', 'page_url', 'referrer'];

        return response()->streamDownload(function () use ($request, $columns): void {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM so Excel shows Bangla text correctly.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $columns);

            $this->filtered($request)->orderBy('id')->chunk(500, function ($leads) use ($out, $columns): void {
                foreach ($leads as $lead) {
                    fputcsv($out, array_map(function (string $column) use ($lead): string {
                        $value = $lead->{$column};
                        $text = (string) ($value instanceof \BackedEnum ? $value->value : $value);

                        // Neutralise spreadsheet formulas in user-supplied text.
                        return preg_match('/^[=+\-@\t\r]/', $text) === 1 && $column !== 'phone' ? "'".$text : $text;
                    }, $columns));
                }
            });

            fclose($out);
        }, 'auxtech-leads-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filtered(Request $request): Builder
    {
        return Lead::query()
            ->when(in_array($request->query('status'), LeadStatus::values(), true), fn (Builder $q) => $q->where('status', $request->query('status')))
            ->when(in_array($request->query('source'), LeadSource::values(), true), fn (Builder $q) => $q->where('source', $request->query('source')))
            ->when($request->filled('assigned_to'), fn (Builder $q) => $q->where('assigned_to', (int) $request->query('assigned_to')))
            ->when(trim((string) $request->query('search')) !== '', fn (Builder $q) => Search::apply($q, ['name', 'phone', 'email', 'company'], (string) $request->query('search')));
    }

    /** @return array<string, mixed> */
    private function summary(Lead $lead): array
    {
        return [
            'id' => $lead->id,
            'uuid' => $lead->uuid,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'source' => $lead->source->value,
            'status' => $lead->status->value,
            'assigned_to' => $lead->assigned_to,
            'assignee' => $lead->assignee?->name,
            'utm_source' => $lead->utm_source,
            'created_at' => $lead->created_at?->toIso8601String(),
        ];
    }

    /** @return list<array{id: int, name: string}> */
    private function assignees(): array
    {
        return User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name'])
            ->map(fn (User $u) => ['id' => $u->id, 'name' => $u->name])->all();
    }
}
