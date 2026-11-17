<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndustryResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Industry;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = [
            'industry' => $request->string('industry')->toString() ?: null,
            'solution' => $request->string('solution')->toString() ?: null,
            'service' => $request->string('service')->toString() ?: null,
        ];

        $projects = Project::query()->published()->ordered()
            ->with(['client.media', 'industry', 'solution', 'services', 'media'])
            ->when($filters['industry'], fn (Builder $q, string $slug) => $q->whereHas('industry', fn (Builder $i) => $i->where('slug', $slug)))
            ->when($filters['solution'], fn (Builder $q, string $slug) => $q->whereHas('solution', fn (Builder $s) => $s->where('slug', $slug)))
            ->when($filters['service'], fn (Builder $q, string $slug) => $q->whereHas('services', fn (Builder $s) => $s->where('slug', $slug)))
            ->get();

        $published = fn (Builder $q) => $q->published();

        seo()->page('work')
            ->canonical(lroute('work.index'))
            ->crumb(__('Home'), lroute('home'))->crumb(__('Work'), lroute('work.index'))
            ->schema(seo()->builder()->itemList('Auxtech projects', $projects->map(fn (Project $p) => ['name' => (string) $p->title, 'url' => lroute('work.show', $p->slug)])->all()));

        return Inertia::render('Site/Work/Index', [
            'intro' => page_intro('work'),
            'projects' => ProjectResource::collection($projects)->resolve(),
            'filters' => $filters,
            'industries' => IndustryResource::collection(Industry::query()->ordered()->withCount(['projects' => $published])->get()->where('projects_count', '>', 0)->values())->resolve(),
            'solutions' => Solution::query()->published()->ordered()->withCount(['projects' => $published])->get()->where('projects_count', '>', 0)
                ->map(fn (Solution $s) => ['slug' => $s->slug, 'name' => $s->name, 'projects_count' => $s->projects_count])->values()->all(),
            'services' => Service::query()->published()->ordered()->withCount(['projects' => $published])->get()->where('projects_count', '>', 0)
                ->map(fn (Service $s) => ['slug' => $s->slug, 'title' => $s->title, 'projects_count' => $s->projects_count])->values()->all(),
        ]);
    }

    public function show(string $slug): Response
    {
        $project = Project::query()->published()->where('slug', $slug)
            ->with(['client.media', 'industry', 'solution', 'services', 'technologies', 'features', 'media', 'seo'])
            ->firstOrFail();

        $related = Project::query()->published()->whereKeyNot($project->id)
            ->when($project->industry_id, fn (Builder $q) => $q->where('industry_id', $project->industry_id))
            ->with(['client.media', 'industry', 'solution', 'media'])->ordered()->limit(3)->get();

        $cover = $project->image('cover');

        seo()->for($project, $project->title.($project->industry ? ' — '.$project->industry->name.' '.__('Project') : ''), $project->summary ?: $project->tagline, $cover['large'] ?? null)
            ->type('article')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Work'), lroute('work.index'))->crumb((string) $project->title, lroute('work.show', $project->slug))
            ->schema(seo()->builder()->creativeWork((string) $project->title, $project->summary ?: $project->tagline, lroute('work.show', $project->slug),
                $cover['large'] ?? null, $project->industry?->name, $project->launched_at?->toDateString()));

        return Inertia::render('Site/Work/Show', [
            'project' => (new ProjectResource($project))->full()->resolve(),
            'testimonials' => TestimonialResource::collection($project->testimonials()->published()->with('media')->get())->resolve(),
            'related' => ProjectResource::collection($related)->resolve(),
        ]);
    }
}
