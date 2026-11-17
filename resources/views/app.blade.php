@php
    /** @var array<string, mixed> $page */
    $seo = $page['props']['seo'] ?? [];
    $isAdmin = request()->is('admin', 'admin/*');
    $fallback = 'fallback.'.str_replace('/', '.', \Illuminate\Support\Str::of($page['component'] ?? '')->lower());
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ config('auxtech.brand.color') }}">
    {{-- Before first paint: mark JavaScript as available (hides the crawler fallback) and apply the saved theme. --}}
    <script>
        (function (d) {
            d.classList.add('js');
            var t = null;
            try { t = localStorage.getItem('aux-theme'); } catch (e) {}
            if (t !== 'light' && t !== 'dark') t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            d.dataset.theme = {{ $isAdmin ? "'light'" : 't' }};
        })(document.documentElement);
    </script>

    {{--
        SEO tags are printed here on the server from the `seo` page prop, so search engines
        and AI crawlers get them without running JavaScript. Pages must not use <Head> for these;
        useSeoSync() keeps them current during client-side navigation (Phase 2).
    --}}
    <title>{{ $seo['title'] ?? config('app.name') }}</title>
    @if (! empty($seo['description']))
        <meta name="description" content="{{ $seo['description'] }}">
    @endif
    <meta name="robots" content="{{ $isAdmin ? 'noindex, nofollow' : ($seo['robots'] ?? 'index, follow') }}">
    @if (! $isAdmin)
        <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
        @foreach ($seo['alternates'] ?? [] as $locale => $href)
            <link rel="alternate" hreflang="{{ $locale }}" href="{{ $href }}">
        @endforeach
        @if (! empty($seo['alternates']['en']))
            <link rel="alternate" hreflang="x-default" href="{{ $seo['alternates']['en'] }}">
        @endif
        <meta property="og:type" content="{{ $seo['type'] ?? 'website' }}">
        <meta property="og:site_name" content="{{ $seo['siteName'] ?? config('app.name') }}">
        <meta property="og:title" content="{{ $seo['title'] ?? config('app.name') }}">
        <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
        <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
        <meta property="og:image" content="{{ $seo['image'] ?? url('/icon-512.png') }}">
        <meta property="og:locale" content="{{ app()->getLocale() === 'bn' ? 'bn_BD' : 'en_US' }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seo['title'] ?? config('app.name') }}">
        <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
        <meta name="twitter:image" content="{{ $seo['image'] ?? url('/icon-512.png') }}">
        @if ($verification = setting('seo.google_site_verification'))
            <meta name="google-site-verification" content="{{ $verification }}">
        @endif
        @if ($verification = setting('seo.bing_site_verification'))
            <meta name="msvalidate.01" content="{{ $verification }}">
        @endif
        <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} Blog" href="{{ url('/feed.xml') }}">
        @if (! empty($seo['jsonLd']))
            <script type="application/ld+json">{!! json_encode($seo['jsonLd'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @endif
    @endif

    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    @if (app()->isProduction() && ! $isAdmin && ($clarity = setting('tracking.clarity_id')))
        {{-- Microsoft Clarity: public pages in production only. --}}
        <script>
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", @json($clarity));
        </script>
    @endif

    @routes
    @vite('resources/js/app.ts')
</head>
<body class="antialiased" data-page="{{ $page['component'] ?? '' }}">
    <script data-page="app" type="application/json">{!! json_encode($page, JSON_HEX_TAG) !!}</script>
    <div id="app">@if (! $isAdmin && view()->exists($fallback))@include('fallback._layout', ['view' => $fallback, 'props' => $page['props']])@endif</div>
</body>
</html>
