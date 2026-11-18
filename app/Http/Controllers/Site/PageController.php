<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\IndustryResource;
use App\Http\Resources\PricingPlanResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SolutionResource;
use App\Http\Resources\StatResource;
use App\Http\Resources\TeamMemberResource;
use App\Models\Client;
use App\Models\Faq;
use App\Models\Industry;
use App\Models\LandingPage;
use App\Models\Page;
use App\Models\PricingPlan;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use App\Models\Stat;
use App\Models\TeamMember;
use App\Services\WhatsApp\LinkBuilder;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function industry(string $slug): Response
    {
        $industry = Industry::query()->where('slug', $slug)->with('seo')->firstOrFail();
        $projects = $industry->projects()->published()->ordered()->with(['client.media', 'industry', 'solution', 'media'])->get();

        // Without a description of its own, each industry page still gets a distinct one.
        $description = $industry->description ?: __(':company builds software, websites and online stores for :name businesses in Bangladesh. See our :name projects and chat with us on WhatsApp.', [
            'company' => setting_t('company.name'), 'name' => $industry->name,
        ]);

        seo()->for($industry, __(':name Website Development in Bangladesh', ['name' => $industry->name]), $description)
            ->crumb(__('Home'), lroute('home'))->crumb(__('Work'), lroute('work.index'))->crumb((string) $industry->name, lroute('industries.show', $industry->slug));

        return Inertia::render('Site/Industries/Show', [
            'industry' => (new IndustryResource($industry))->resolve(),
            'projects' => ProjectResource::collection($projects)->resolve(),
        ]);
    }

    public function pricing(): Response
    {
        $plans = PricingPlan::query()->where('is_active', true)->ordered()->with('planable')->get();

        seo()->page('pricing')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Pricing'), lroute('pricing'));

        return Inertia::render('Site/Pricing', [
            'intro' => page_intro('pricing'),
            'groups' => $plans->groupBy(fn (PricingPlan $p) => $p->planable ? (string) ($p->planable->name ?? $p->planable->title) : '')
                ->map(fn ($group, $name) => ['name' => $name ?: null, 'plans' => PricingPlanResource::collection($group)->resolve()])->values()->all(),
        ]);
    }

    public function about(): Response
    {
        seo()->page('about')
            ->crumb(__('Home'), lroute('home'))->crumb(__('About'), lroute('about'));

        return Inertia::render('Site/About', [
            'intro' => page_intro('about'),
            'team' => TeamMemberResource::collection(TeamMember::query()->where('is_active', true)->ordered()->with('media')->get())->resolve(),
            'stats' => StatResource::collection(Stat::query()->where('is_active', true)->ordered()->get())->resolve(),
            'projectCount' => Project::query()->published()->count(),
            'industries' => IndustryResource::collection(Industry::query()->ordered()
                ->withCount(['projects' => fn ($q) => $q->published()])->get()->where('projects_count', '>', 0)->values())->resolve(),
            'story' => setting_t('about.story'),
            'reasons' => tr_rows(setting('home.reasons')),
            'clients' => ClientResource::collection(Client::query()->where('show_logo', true)->ordered()->with('media')->get())->resolve(),
        ]);
    }

    public function faq(): Response
    {
        $faqs = Faq::query()->where('is_active', true)->whereNull('faqable_id')->ordered()->get();

        seo()->page('faq')
            ->crumb(__('Home'), lroute('home'))->crumb(__('FAQ'), lroute('faq'))
            ->schema(seo()->builder()->faqPage($faqs->map(fn (Faq $f) => ['question' => $f->question, 'answer' => $f->answer])));

        return Inertia::render('Site/Faq', [
            'intro' => page_intro('faq'),
            'groups' => $faqs->groupBy(fn (Faq $f) => $f->group ?: 'general')
                ->map(fn ($group, $name) => ['group' => $name, 'faqs' => FaqResource::collection($group)->resolve()])->values()->all(),
        ]);
    }

    public function contact(LinkBuilder $whatsapp): Response
    {
        seo()->page('contact')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Contact'), lroute('contact'));

        return Inertia::render('Site/Contact', [
            'intro' => page_intro('contact'),
            'services' => Service::query()->published()->ordered()->get()->map(fn (Service $s) => ['id' => $s->id, 'title' => $s->title])->all(),
            'whatsappUrl' => $whatsapp->url(),
            'mapUrl' => setting('company.map_url') ?: 'https://www.google.com/maps/search/?api=1&query='.rawurlencode((string) tr(setting('company.address'), 'en')),
        ]);
    }

    public function estimate(): Response
    {
        seo()->page('estimate')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Estimate'), lroute('estimate'));

        return Inertia::render('Site/Estimate', [
            'intro' => page_intro('estimate'),
            'services' => Service::query()->published()->ordered()->get()->map(fn (Service $s) => ['id' => $s->id, 'title' => $s->title])->all(),
            'solutions' => Solution::query()->published()->ordered()->with('featureGroups.features')->get()
                ->map(fn (Solution $s) => (new SolutionResource($s))->resolve())->all(),
        ]);
    }

    /** CMS pages and SEO landing pages share the top-level URL space. */
    public function show(string $slug, LinkBuilder $whatsapp): Response
    {
        if ($page = Page::query()->published()->where('slug', $slug)->with('seo')->first()) {
            seo()->for($page, (string) $page->title, $page->body)
                ->crumb(__('Home'), lroute('home'))->crumb((string) $page->title, lroute('page.show', $page->slug));

            return Inertia::render('Site/Page', [
                'page' => ['title' => $page->title, 'body' => $page->body, 'template' => $page->template, 'updated_at' => $page->updated_at?->toIso8601String()],
            ]);
        }

        $landing = LandingPage::query()->published()->where('slug', $slug)->with(['seo', 'service', 'solution', 'industry'])->firstOrFail();
        $faqs = $landing->faqs()->where('is_active', true)->ordered()->get();

        $projects = Project::query()->published()->ordered()->with(['client.media', 'industry', 'solution', 'media'])
            ->when($landing->industry_id, fn ($q) => $q->where('industry_id', $landing->industry_id))
            ->when($landing->solution_id, fn ($q) => $q->where('solution_id', $landing->solution_id))
            ->limit(9)->get();

        seo()->for($landing, (string) $landing->title, $landing->intro ?: $landing->body)
            ->crumb(__('Home'), lroute('home'))->crumb((string) $landing->title, lroute('page.show', $landing->slug))
            ->schema(seo()->builder()->service((string) $landing->title, $landing->intro, lroute('page.show', $landing->slug)))
            ->schema(seo()->builder()->faqPage($faqs->map(fn (Faq $f) => ['question' => $f->question, 'answer' => $f->answer])));

        return Inertia::render('Site/Landing', [
            'landing' => ['title' => $landing->title, 'intro' => $landing->intro, 'body' => $landing->body, 'location' => $landing->location],
            'service' => $landing->service?->isPublished() ? (new ServiceResource($landing->service))->resolve() : null,
            'solution' => $landing->solution?->isPublished() ? (new SolutionResource($landing->solution))->resolve() : null,
            'projects' => ProjectResource::collection($projects)->resolve(),
            'faqs' => FaqResource::collection($faqs)->resolve(),
            'whatsappUrl' => $whatsapp->url('service', (string) $landing->title),
        ]);
    }
}
