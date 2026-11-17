<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Http\Resources\PricingPlanResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Models\Faq;
use App\Models\Service;
use App\Services\WhatsApp\LinkBuilder;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Service::query()->published()->whereNull('parent_id')->ordered()
            ->with(['media', 'children' => fn ($q) => $q->published()->ordered()->with('media')])->get();

        seo()->page('services')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Services'), lroute('services.index'));

        return Inertia::render('Site/Services/Index', [
            'intro' => page_intro('services'),
            'services' => ServiceResource::collection($services)->resolve(),
        ]);
    }

    public function show(string $slug, LinkBuilder $whatsapp): Response
    {
        $service = Service::query()->published()->where('slug', $slug)
            ->with(['media', 'parent', 'seo', 'children' => fn ($q) => $q->published()->ordered()->with('media')])->firstOrFail();

        $faqs = $service->faqs()->where('is_active', true)->ordered()->get();

        seo()->for($service, (string) $service->title, $service->excerpt ?: $service->tagline, $service->image('cover')['large'] ?? null)
            ->crumb(__('Home'), lroute('home'))->crumb(__('Services'), lroute('services.index'))->crumb((string) $service->title, lroute('services.show', $service->slug))
            ->schema(seo()->builder()->service((string) $service->title, $service->excerpt ?: $service->tagline, lroute('services.show', $service->slug)))
            ->schema(seo()->builder()->faqPage($faqs->map(fn (Faq $f) => ['question' => $f->question, 'answer' => $f->answer])));

        return Inertia::render('Site/Services/Show', [
            'service' => (new ServiceResource($service))->full()->resolve(),
            'projects' => ProjectResource::collection($service->projects()->published()->ordered()
                ->with(['client.media', 'industry', 'solution', 'media'])->limit(9)->get())->resolve(),
            'faqs' => FaqResource::collection($faqs)->resolve(),
            'plans' => PricingPlanResource::collection($service->plans()->where('is_active', true)->ordered()->get())->resolve(),
            'whatsappUrl' => $whatsapp->url('service', (string) $service->title),
        ]);
    }
}
