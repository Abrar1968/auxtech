<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ config('auxtech.brand.color') }}">

    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    {{--
        SEO tags are printed here on the server from the `seo` page prop, so they are
        correct with or without the SSR process. Phase 1 adds description, canonical,
        hreflang, Open Graph and JSON-LD. Pages must not set <title> through <Head>.
    --}}
    <title>{{ $page['props']['seo']['title'] ?? config('app.name') }}</title>

    @if (app()->isProduction() && config('auxtech.tracking.clarity_id') && ! request()->is('admin', 'admin/*'))
        {{-- Microsoft Clarity: public pages in production only. --}}
        <script>
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", @json(config('auxtech.tracking.clarity_id')));
        </script>
    @endif

    @routes
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>
