<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EventType;
use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\ConversionEvent;
use App\Models\FailedTask;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Project;
use App\Support\Realtime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless($request->user()->can('dashboard.view'), 403);

        $days = in_array((int) $request->query('days'), [7, 30, 90], true) ? (int) $request->query('days') : 30;
        $since = now()->subDays($days)->startOfDay();
        $previous = now()->subDays($days * 2)->startOfDay();

        $leads = fn (Carbon $from, ?Carbon $to = null) => Lead::query()
            ->where('status', '!=', LeadStatus::Spam->value)
            ->where('created_at', '>=', $from)
            ->when($to, fn ($q) => $q->where('created_at', '<', $to));

        $clicks = fn (Carbon $from, ?Carbon $to = null) => ConversionEvent::query()
            ->where('type', EventType::WhatsAppClick->value)
            ->where('created_at', '>=', $from)
            ->when($to, fn ($q) => $q->where('created_at', '<', $to));

        $canSeeLeads = $request->user()->can('leads.view');

        return Inertia::render('Admin/Dashboard', [
            'days' => $days,
            'kpis' => [
                'leads' => ['value' => $leads($since)->count(), 'previous' => $leads($previous, $since)->count()],
                'leadsToday' => ['value' => $leads(now()->startOfDay())->count()],
                'whatsappClicks' => ['value' => $clicks($since)->count(), 'previous' => $clicks($previous, $since)->count()],
                'emailClicks' => ['value' => ConversionEvent::query()->where('type', EventType::EmailClick->value)->where('created_at', '>=', $since)->count()],
                'won' => ['value' => $leads($since)->where('status', LeadStatus::Won->value)->count()],
            ],
            'leadsBySource' => $leads($since)->selectRaw('source, count(*) as total')->groupBy('source')->pluck('total', 'source'),
            'leadsByStatus' => $leads($since)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'clicksByPlacement' => $clicks($since)->selectRaw('placement, count(*) as total')->groupBy('placement')->orderByDesc('total')->limit(12)->pluck('total', 'placement'),
            'leadsByDay' => $leads($since)->selectRaw('date(created_at) as day, count(*) as total')->groupBy('day')->orderBy('day')->pluck('total', 'day'),
            'clicksByDay' => $clicks($since)->selectRaw('date(created_at) as day, count(*) as total')->groupBy('day')->orderBy('day')->pluck('total', 'day'),
            'topPages' => $clicks($since)->whereNotNull('page_url')->selectRaw('page_url, count(*) as total')->groupBy('page_url')->orderByDesc('total')->limit(8)->pluck('total', 'page_url'),
            'recentLeads' => $canSeeLeads ? Lead::query()->latest('id')->limit(8)->get()->map(fn (Lead $l) => [
                'id' => $l->id, 'name' => $l->name, 'phone' => $l->phone, 'source' => $l->source->value,
                'status' => $l->status->value, 'created_at' => $l->created_at?->toIso8601String(),
            ]) : [],
            'content' => [
                'projects' => Project::query()->count(),
                'publishedProjects' => Project::query()->published()->count(),
                'posts' => Post::query()->published()->count(),
            ],
            'health' => [
                'mailConfigured' => filled(setting('mail.password')),
                'realtime' => Realtime::enabled(),
                'failedTasks' => FailedTask::query()->count(),
            ],
        ]);
    }
}
