<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $term = trim((string) $request->validate(['q' => ['required', 'string', 'min:2', 'max:60']])['q']);
        $like = '%'.addcslashes($term, '%_').'%';
        $locale = in_array($request->query('locale'), config('auxtech.locales.supported'), true) ? $request->query('locale') : 'en';
        app()->setLocale($locale);

        // Translatable columns are JSON text, so a LIKE on the column matches either language.
        $results = collect()
            ->concat(Project::query()->published()->where(fn ($q) => $q->where('title', 'like', $like)->orWhere('summary', 'like', $like))->limit(6)->get()
                ->map(fn (Project $m) => ['type' => 'project', 'title' => $m->title, 'url' => lroute('work.show', $m->slug)]))
            ->concat(Service::query()->published()->where(fn ($q) => $q->where('title', 'like', $like)->orWhere('excerpt', 'like', $like))->limit(4)->get()
                ->map(fn (Service $m) => ['type' => 'service', 'title' => $m->title, 'url' => lroute('services.show', $m->slug)]))
            ->concat(Solution::query()->published()->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('summary', 'like', $like))->limit(4)->get()
                ->map(fn (Solution $m) => ['type' => 'solution', 'title' => $m->name, 'url' => lroute('solutions.show', $m->slug)]))
            ->concat(Post::query()->published()->where(fn ($q) => $q->where('title', 'like', $like)->orWhere('excerpt', 'like', $like))->limit(6)->get()
                ->map(fn (Post $m) => ['type' => 'post', 'title' => $m->title, 'url' => lroute('blog.show', $m->slug)]));

        return response()->json(['results' => $results->values()]);
    }
}
