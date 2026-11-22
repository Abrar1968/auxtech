<?php

namespace App\Http\Controllers\Site;

use App\Enums\ProjectType;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PricingPlanResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SolutionResource;
use App\Http\Resources\StatResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Client;
use App\Models\Faq;
use App\Models\Industry;
use App\Models\Post;
use App\Models\PricingPlan;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Services\WhatsApp\LinkBuilder;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(LinkBuilder $whatsapp): Response
    {
        $flagship = Solution::query()->published()->where('is_flagship', true)
            ->with('featureGroups.features.media')->withCount('projects')->first();

        seo()->title(setting_t('seo.default_title'))->description(setting_t('seo.default_description'))
            ->schema($flagship ? seo()->builder()->softwareApplication(
                (string) $flagship->name, $flagship->summary, lroute('solutions.show', $flagship->slug),
                $flagship->featureGroups->flatMap->features->map(fn ($f) => (string) $f->title)->all(),
            ) : null);

        // The still image behind the headline is the largest thing on the first screen.
        $poster = setting('home.hero_poster') ?: null;
        seo()->preloadUrl($poster);

        $faqs = Faq::query()->where('is_active', true)->whereNull('faqable_id')->ordered()->limit(8)->get();
        seo()->schema(seo()->builder()->faqPage($faqs->map(fn (Faq $f) => ['question' => $f->question, 'answer' => $f->answer])));

        return Inertia::render('Site/Home', [
            'hero' => [
                'eyebrow' => setting_t('home.hero_eyebrow'),
                'title' => setting_t('home.hero_title'),
                'subtitle' => setting_t('home.hero_subtitle'),
                'video' => setting('home.hero_video') ?: null,
                'videoWebm' => setting('home.hero_video_webm') ?: null,
                'poster' => $poster,
            ],
            'copy' => collect(setting('home.sections', []))->pluck('key')->mapWithKeys(fn (string $key) => [$key => section_copy($key)])->all(),
            'process' => tr_rows(setting('home.process_steps')),
            'reasons' => tr_rows(setting('home.reasons')),
            'industryCount' => Industry::query()->whereHas('projects', fn ($q) => $q->published())->count(),
            'sections' => collect(setting('home.sections', []))->where('enabled', true)->pluck('key')->values()->all(),
            'clients' => ClientResource::collection(Client::query()->where('show_logo', true)->ordered()->with('media')->get())->resolve(),
            'stats' => StatResource::collection(Stat::query()->where('is_active', true)->ordered()->get())->resolve(),
            'services' => ServiceResource::collection(Service::query()->published()->whereNull('parent_id')->ordered()
                ->with(['media', 'children' => fn ($q) => $q->published()->ordered()->with('media')])->get())->resolve(),
            'flagship' => $flagship ? (new SolutionResource($flagship))->resolve() : null,
            'projects' => ProjectResource::collection(Project::query()->published()->where('is_featured', true)->ordered()
                ->with(['client.media', 'industry', 'solution', 'media'])->limit(8)->get())->resolve(),
            'projectCount' => Project::query()->published()->where('type', '<>', 'demo')->count(),
            'liveCount' => Project::query()->published()->where('type', ProjectType::Live->value)->count(),
            'solutions' => SolutionResource::collection(Solution::query()->published()->ordered()->with('media')->withCount('projects')->get())->resolve(),
            'testimonials' => TestimonialResource::collection(Testimonial::query()->published()->where('is_featured', true)->ordered()
                ->with(['client', 'project', 'media'])->limit(8)->get())->resolve(),
            'plans' => PricingPlanResource::collection(PricingPlan::query()->where('is_active', true)->ordered()->limit(4)->get())->resolve(),
            'posts' => PostResource::collection(Post::query()->published()->latest('published_at')->with(['category', 'media'])->limit(3)->get())->resolve(),
            'faqs' => FaqResource::collection($faqs)->resolve(),
            'whatsappUrl' => $whatsapp->url(),
        ]);
    }
}
