<?php

namespace App\Http\Controllers\Feeds;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Industry;
use App\Models\LandingPage;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * sitemap.xml, robots.txt, llms.txt, llms-full.txt and the blog feed.
 * Built on request and cached; SiteCache::flush() clears them on change.
 */
class FeedController extends Controller
{
    private const AI_CRAWLERS = ['GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'Claude-SearchBot', 'PerplexityBot', 'Google-Extended', 'Bingbot', 'Applebot-Extended'];

    public function sitemap(): Response
    {
        $xml = Cache::rememberForever('feeds.sitemap', function (): string {
            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
                .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

            foreach ($this->entries() as $entry) {
                foreach (config('auxtech.locales.supported') as $locale) {
                    $out .= "  <url>\n    <loc>".e(lroute($entry['route'], $entry['params'], $locale))."</loc>\n";

                    foreach (config('auxtech.locales.supported') as $alternate) {
                        $out .= '    <xhtml:link rel="alternate" hreflang="'.$alternate.'" href="'.e(lroute($entry['route'], $entry['params'], $alternate)).'"/>'."\n";
                    }

                    $out .= '    <xhtml:link rel="alternate" hreflang="x-default" href="'.e(lroute($entry['route'], $entry['params'], 'en')).'"/>'."\n";

                    if ($entry['lastmod']) {
                        $out .= '    <lastmod>'.$entry['lastmod']->toAtomString()."</lastmod>\n";
                    }

                    if ($entry['image']) {
                        $out .= '    <image:image><image:loc>'.e($entry['image'])."</image:loc></image:image>\n";
                    }

                    $out .= "  </url>\n";
                }
            }

            return $out.'</urlset>'."\n";
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = ['User-agent: *', 'Allow: /', 'Disallow: /admin', 'Disallow: /install', 'Disallow: /api/', 'Disallow: /go/', ''];

        // AI assistants and their search crawlers are welcome.
        foreach (self::AI_CRAWLERS as $bot) {
            array_push($lines, 'User-agent: '.$bot, 'Allow: /', 'Disallow: /admin', '');
        }

        if ($extra = trim((string) setting('seo.robots_extra'))) {
            array_push($lines, $extra, '');
        }

        $lines[] = 'Sitemap: '.url('/sitemap.xml');

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function llms(): Response
    {
        $text = Cache::rememberForever('feeds.llms', function (): string {
            $name = setting('company.name');
            $lines = [
                "# {$name}",
                '',
                '> '.setting_t('company.description'),
                '',
                '## Key facts',
                '',
                "- Name: {$name}",
                '- Services: '.setting_t('company.tagline'),
                '- Location: '.tr(setting('company.address'), 'en'),
                '- WhatsApp / phone: '.setting('company.phone'),
                '- Email: '.setting('company.email'),
                '- Office hours: '.tr(setting('hours.text'), 'en'),
                '- Website: '.url('/'),
                '- Published projects: '.Project::query()->published()->count(),
                '',
                '## Services',
                '',
            ];

            foreach (Service::query()->published()->ordered()->get() as $service) {
                $lines[] = '- ['.$service->getTranslation('title', 'en').']('.lroute('services.show', $service->slug, 'en').')'.$this->suffix($service->getTranslation('excerpt', 'en', false));
            }

            array_push($lines, '', '## Solutions and products', '');

            foreach (Solution::query()->published()->ordered()->get() as $solution) {
                $lines[] = '- ['.$solution->getTranslation('name', 'en').']('.lroute('solutions.show', $solution->slug, 'en').')'.$this->suffix($solution->getTranslation('summary', 'en', false));
            }

            array_push($lines, '', '## Main pages', '',
                '- [Work / portfolio]('.lroute('work.index', [], 'en').')',
                '- [Pricing]('.lroute('pricing', [], 'en').')',
                '- [About]('.lroute('about', [], 'en').')',
                '- [FAQ]('.lroute('faq', [], 'en').')',
                '- [Contact]('.lroute('contact', [], 'en').')',
                '- [Blog]('.lroute('blog.index', [], 'en').')',
                '- [Full text version]('.url('/llms-full.txt').')',
            );

            return implode("\n", $lines)."\n";
        });

        return response($text, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function llmsFull(): Response
    {
        $text = Cache::rememberForever('feeds.llms-full', function (): string {
            $plain = fn (?string $html): string => trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $html))));
            $out = '# '.setting('company.name')." — full site content\n\n".setting_t('company.description')."\n\n";
            $out .= 'Address: '.tr(setting('company.address'), 'en').'. WhatsApp: '.setting('company.phone').'. Email: '.setting('company.email').".\n\n";

            $out .= "## Services\n\n";
            foreach (Service::query()->published()->ordered()->get() as $service) {
                $out .= '### '.$service->getTranslation('title', 'en')."\n".lroute('services.show', $service->slug, 'en')."\n"
                    .$plain($service->getTranslation('excerpt', 'en', false))."\n".$plain($service->getTranslation('body', 'en', false))."\n\n";
            }

            $out .= "## Solutions\n\n";
            foreach (Solution::query()->published()->ordered()->with('featureGroups.features')->get() as $solution) {
                $out .= '### '.$solution->getTranslation('name', 'en')."\n".lroute('solutions.show', $solution->slug, 'en')."\n"
                    .$plain($solution->getTranslation('summary', 'en', false))."\n";

                foreach ($solution->featureGroups as $group) {
                    $out .= '- '.$group->getTranslation('name', 'en').': '.$group->features->map(fn ($f) => $f->getTranslation('title', 'en'))->implode(', ')."\n";
                }

                $out .= "\n";
            }

            $out .= "## Projects\n\n";
            foreach (Project::query()->published()->ordered()->with(['industry', 'solution'])->get() as $project) {
                $out .= '- '.$project->getTranslation('title', 'en')
                    .($project->industry ? ' ('.$project->industry->getTranslation('name', 'en').')' : '')
                    .($project->type->value === 'demo' ? ' [demo]' : '')
                    .': '.$plain($project->getTranslation('summary', 'en', false) ?: $project->getTranslation('tagline', 'en', false))
                    .' '.lroute('work.show', $project->slug, 'en')."\n";
            }

            $out .= "\n## Frequently asked questions\n\n";
            foreach (Faq::query()->where('is_active', true)->ordered()->get() as $faq) {
                $out .= 'Q: '.$faq->getTranslation('question', 'en')."\nA: ".$plain($faq->getTranslation('answer', 'en', false))."\n\n";
            }

            return $out;
        });

        return response($text, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function rss(): Response
    {
        $xml = Cache::rememberForever('feeds.rss', function (): string {
            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"><channel>'."\n"
                .'<title>'.e(setting('company.name').' Blog').'</title><link>'.e(lroute('blog.index', [], 'en')).'</link>'
                .'<description>'.e((string) setting_t('pages.blog.description')).'</description>'
                .'<atom:link href="'.e(url('/feed.xml')).'" rel="self" type="application/rss+xml"/>'."\n";

            foreach (Post::query()->published()->latest('published_at')->limit(30)->get() as $post) {
                $out .= '<item><title>'.e($post->getTranslation('title', 'en')).'</title><link>'.e(lroute('blog.show', $post->slug, 'en')).'</link>'
                    .'<guid>'.e(lroute('blog.show', $post->slug, 'en')).'</guid>'
                    .'<pubDate>'.($post->published_at ?? $post->created_at)->toRssString().'</pubDate>'
                    .'<description>'.e((string) $post->getTranslation('excerpt', 'en', false)).'</description></item>'."\n";
            }

            return $out.'</channel></rss>'."\n";
        });

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }

    /** @return list<array{route: string, params: array<int|string, mixed>, lastmod: ?Carbon, image: ?string}> */
    private function entries(): array
    {
        $entry = fn (string $route, array $params = [], ?Carbon $lastmod = null, ?string $image = null): array => compact('route', 'params', 'lastmod', 'image');
        $entries = [];

        foreach (['home', 'work.index', 'services.index', 'solutions.index', 'pricing', 'about', 'faq', 'contact', 'estimate', 'blog.index'] as $route) {
            $entries[] = $entry($route);
        }

        foreach (Service::query()->published()->get() as $m) {
            $entries[] = $entry('services.show', [$m->slug], $m->updated_at);
        }
        foreach (Solution::query()->published()->get() as $m) {
            $entries[] = $entry('solutions.show', [$m->slug], $m->updated_at);
        }
        foreach (Industry::query()->whereHas('projects', fn ($q) => $q->published())->get() as $m) {
            $entries[] = $entry('industries.show', [$m->slug], $m->updated_at);
        }
        foreach (Project::query()->published()->with('media')->get() as $m) {
            $entries[] = $entry('work.show', [$m->slug], $m->updated_at, $m->image('cover')['large'] ?? null);
        }
        foreach (Post::query()->published()->with('media')->get() as $m) {
            $entries[] = $entry('blog.show', [$m->slug], $m->updated_at, $m->image('cover')['large'] ?? null);
        }
        foreach (PostCategory::query()->whereHas('posts', fn ($q) => $q->published())->get() as $m) {
            $entries[] = $entry('blog.category', [$m->slug], $m->updated_at);
        }
        foreach (Page::query()->published()->get() as $m) {
            $entries[] = $entry('page.show', [$m->slug], $m->updated_at);
        }
        foreach (LandingPage::query()->published()->get() as $m) {
            $entries[] = $entry('page.show', [$m->slug], $m->updated_at);
        }

        return $entries;
    }

    private function suffix(?string $text): string
    {
        $text = trim(strip_tags((string) $text));

        return $text === '' ? '' : ': '.$text;
    }
}
