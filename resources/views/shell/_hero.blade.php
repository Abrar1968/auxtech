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
        @php($parts = $p['hero']['titleParts'] ?? [['text' => $title, 'accent' => false]])
        <section class="relative flex min-h-[100svh] flex-col overflow-hidden bg-ink-950 text-white" style="isolation:isolate">
            <div class="absolute inset-0 -z-30 bg-[radial-gradient(120%_80%_at_50%_42%,#3a3c42_0%,#2b2d31_38%,#1c1d20_100%)]"></div>
            @if (! empty($p['hero']['video']) && ! empty($p['hero']['poster']))
                <img src="{{ $p['hero']['poster'] }}" alt="" class="absolute inset-0 -z-20 size-full object-cover object-right" fetchpriority="high">
            @endif
            <div class="absolute inset-x-0 top-0 -z-10 h-40 bg-gradient-to-b from-ink-950/80 to-transparent"></div>
            <div class="absolute inset-0 -z-10 bg-ink-950/35 lg:hidden"></div>
            <div class="container-page flex flex-1 flex-col pt-28 pb-24 sm:pt-32 sm:pb-8">
                <div class="grid lg:ml-auto lg:w-[60%] lg:text-right">
                    <div>
                        @if (! empty($p['hero']['eyebrow']))
                            <p class="inline-flex items-center gap-2.5 font-mono text-xs tracking-[0.18em] text-brand-200 uppercase sm:text-[0.8rem] lg:justify-end"><span class="size-1.5 rounded-full bg-brand-600"></span>[ {{ $p['hero']['eyebrow'] }} ]</p>
                        @endif
                        <div class="mt-5 font-display text-[2.6rem] leading-[1.02] font-light tracking-[-0.02em] text-balance sm:text-6xl lg:text-[5.25rem] xl:text-[6.25rem] lang-bn:leading-[1.3]">@foreach ($parts as $part)<span @class(['text-brand-600 italic' => $part['accent']])>{{ $part['text'] }}</span>{{ $loop->last ? '' : ' ' }}@endforeach</div>
                    </div>
                </div>
                <div class="flex-1"></div>
                @if ($lead)
                    <div class="relative max-w-md rounded-2xl border border-white/10 bg-ink-950/60 p-5 backdrop-blur-md sm:p-6">
                        <p class="line-clamp-3 text-[0.95rem] leading-relaxed text-white/80 sm:line-clamp-none sm:text-base">{{ $lead }}</p>
                    </div>
                @endif
            </div>
        </section>
    @else
        <section class="relative isolate min-h-[100svh] overflow-hidden bg-ink-950 pt-32 pb-16 text-white sm:pt-40 sm:pb-24">
            <div class="absolute inset-0 -z-10 bg-grid"></div>
            <div class="container-page">
                @if (count($crumbs) > 1)
                    <div class="mb-8 flex flex-wrap items-center gap-1.5 text-sm text-white/65">
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
