<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Http\Resources\PricingPlanResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SolutionResource;
use App\Models\Faq;
use App\Models\Solution;
use App\Services\WhatsApp\LinkBuilder;
use Inertia\Inertia;
use Inertia\Response;

class SolutionController extends Controller
{
    public function index(): Response
    {
        seo()->page('solutions')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Solutions'), lroute('solutions.index'));

        return Inertia::render('Site/Solutions/Index', [
            'intro' => page_intro('solutions'),
            'solutions' => SolutionResource::collection(Solution::query()->published()->ordered()->with('media')
                ->withCount(['projects' => fn ($q) => $q->published()])->get())->resolve(),
        ]);
    }

    public function show(string $slug, LinkBuilder $whatsapp): Response
    {
        $solution = Solution::query()->published()->where('slug', $slug)
            ->with(['media', 'seo', 'featureGroups.features'])->firstOrFail();

        $faqs = $solution->faqs()->where('is_active', true)->ordered()->get();

        seo()->for($solution, (string) $solution->name, $solution->summary ?: $solution->tagline, $solution->image('cover')['large'] ?? null)
            ->crumb(__('Home'), lroute('home'))->crumb(__('Solutions'), lroute('solutions.index'))->crumb((string) $solution->name, lroute('solutions.show', $solution->slug))
            ->schema(seo()->builder()->softwareApplication((string) $solution->name, $solution->summary ?: $solution->tagline, lroute('solutions.show', $solution->slug),
                $solution->featureGroups->flatMap->features->map(fn ($f) => (string) $f->title)->all()))
            ->schema(seo()->builder()->faqPage($faqs->map(fn (Faq $f) => ['question' => $f->question, 'answer' => $f->answer])));

        return Inertia::render('Site/Solutions/Show', [
            'solution' => (new SolutionResource($solution))->full()->resolve(),
            'projects' => ProjectResource::collection($solution->projects()->published()->ordered()
                ->with(['client.media', 'industry', 'solution', 'media'])->get())->resolve(),
            'faqs' => FaqResource::collection($faqs)->resolve(),
            'plans' => PricingPlanResource::collection($solution->plans()->where('is_active', true)->ordered()->get())->resolve(),
            'whatsappUrl' => $whatsapp->url('solution', (string) $solution->name),
        ]);
    }
}
