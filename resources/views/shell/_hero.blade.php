{{--
    App shell: the top of the page, printed by the server with the same classes
    as the Vue hero, so the headline paints before JavaScript loads. Vue replaces
    it on mount. aria-hidden: the real content follows in Vue (and in the
    crawler fallback), so assistive technology and crawlers never see it twice.
--}}
@php
    $p = $props;
    $isHome = ($component ?? '') === 'Site/Home';
    $title = $p['hero']['title'] ?? $p['intro']['heading'] ?? $p['project']['title'] ?? $p['service']['title'] ?? $p['solution']['name']
        ?? $p['industry']['name'] ?? $p['post']['title'] ?? $p['page']['title'] ?? $p['landing']['title'] ?? null;
    $lead = $p['hero']['subtitle'] ?? $p['intro']['lead'] ?? $p['project']['tagline'] ?? ($p['service']['tagline'] ?? $p['service']['excerpt'] ?? null)
        ?? $p['solution']['summary'] ?? $p['industry']['description'] ?? $p['post']['excerpt'] ?? $p['landing']['intro'] ?? null;
    $crumbs = $p['seo']['breadcrumbs'] ?? [];
    // Same eyebrow the Vue page shows above the title.
    $eyebrow = $p['project']['industry']['name'] ?? $p['service']['parent']['title'] ?? $p['solution']['tagline'] ?? $p['landing']['location'] ?? null;
@endphp
@if ($title)
<div data-shell aria-hidden="true">
    @if ($isHome)
        <section class="relative isolate overflow-hidden bg-ink-950 pt-32 pb-20 text-white sm:pt-40 lg:min-h-[100svh] lg:pb-28">
            <div class="absolute inset-0 -z-10 bg-grid"></div>
            <div class="absolute -top-40 -left-40 -z-10 size-[40rem] rounded-full bg-[radial-gradient(closest-side,rgb(1_69_255/0.6),transparent)] blur-2xl"></div>
            <div class="container-page grid items-center gap-16 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)]">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full glass-dark py-1.5 pr-4 pl-1.5 text-sm text-white/80">
                        <span class="rounded-full bg-brand-600 px-2.5 py-0.5 text-xs font-semibold text-white">{{ $p['site']['name'] ?? '' }}</span>{{ $p['site']['tagline'] ?? '' }}
                    </p>
                    <div class="mt-7 font-display text-[2.6rem] leading-[1.05] font-semibold tracking-tight sm:text-6xl xl:text-7xl lang-bn:leading-[1.35]" style="letter-spacing:-0.02em;text-wrap:balance">{{ $title }}</div>
                    @if ($lead)<p class="mt-6 max-w-xl text-lg leading-relaxed text-white/70 sm:text-xl">{{ $lead }}</p>@endif
                </div>
            </div>
        </section>
    @else
        <section class="relative isolate min-h-[100svh] overflow-hidden bg-ink-950 pt-32 pb-16 text-white sm:pt-40 sm:pb-24">
            <div class="absolute inset-0 -z-10 bg-grid"></div>
            <div class="container-page">
                @if (count($crumbs) > 1)
                    <div class="mb-8 flex flex-wrap items-center gap-1.5 text-sm text-white/50">
                        @foreach ($crumbs as $crumb)<span>{{ $crumb['name'] }}</span>@if (! $loop->last)<span>›</span>@endif @endforeach
                    </div>
                @endif
                <div class="max-w-4xl">
                    @if ($eyebrow)
                        <p class="mb-5 inline-flex items-center gap-2 rounded-full glass-dark px-3 py-1 text-xs font-semibold tracking-[0.14em] text-brand-200 uppercase"><span class="size-1.5 rounded-full bg-current"></span>{{ $eyebrow }}</p>
                    @endif
                    <div class="font-display text-4xl leading-[1.08] font-semibold tracking-tight sm:text-5xl lg:text-6xl" style="letter-spacing:-0.02em;text-wrap:balance">{{ $title }}</div>
                    @if ($lead)<p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/70 sm:text-xl">{{ \Illuminate\Support\Str::limit(strip_tags($lead), 300) }}</p>@endif
                </div>
            </div>
        </section>
    @endif
</div>
@endif
