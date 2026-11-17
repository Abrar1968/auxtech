{{--
    Content fallback: plain HTML of the page for crawlers that do not run JavaScript.
    Vue replaces everything inside #app when it mounts (docs/02 §3.1).
--}}
@php($site = $props['site'] ?? [])
<div data-fallback style="max-width:60rem;margin:0 auto;padding:1rem;font-family:system-ui,sans-serif;line-height:1.6">
    <header>
        <a href="{{ lroute('home') }}"><img src="{{ config('auxtech.brand.logo') }}" alt="{{ $site['name'] ?? 'Auxtech' }}" width="194" height="30"></a>
        <nav aria-label="Main">
            <a href="{{ lroute('work.index') }}">{{ __('Work') }}</a>
            <a href="{{ lroute('services.index') }}">{{ __('Services') }}</a>
            <a href="{{ lroute('solutions.index') }}">{{ __('Solutions') }}</a>
            <a href="{{ lroute('pricing') }}">{{ __('Pricing') }}</a>
            <a href="{{ lroute('about') }}">{{ __('About') }}</a>
            <a href="{{ lroute('blog.index') }}">{{ __('Blog') }}</a>
            <a href="{{ lroute('contact') }}">{{ __('Contact') }}</a>
            @foreach ($props['seo']['alternates'] ?? [] as $locale => $href)
                @if ($locale !== app()->getLocale())
                    <a href="{{ $href }}" hreflang="{{ $locale }}" lang="{{ $locale }}">{{ $locale === 'bn' ? 'বাংলা' : 'English' }}</a>
                @endif
            @endforeach
        </nav>
    </header>

    <main>
        @include($view, $props)
        @if (! empty($site['whatsapp']['url']))
            <p><a href="{{ $props['whatsappUrl'] ?? $props['project']['whatsapp_url'] ?? $site['whatsapp']['url'] }}" rel="nofollow noopener">{{ __('Chat on WhatsApp') }} {{ $site['phoneDisplay'] ?? '' }}</a>
                · <a href="mailto:{{ $site['email'] ?? '' }}">{{ $site['email'] ?? '' }}</a></p>
        @endif
    </main>

    <footer>
        <p><strong>{{ $site['name'] ?? '' }}</strong> — {{ $site['tagline'] ?? '' }}</p>
        <address>{{ $site['address'] ?? '' }}<br>{{ $site['phoneDisplay'] ?? '' }} · {{ $site['email'] ?? '' }}<br>{{ $site['hours'] ?? '' }}</address>
        <nav aria-label="Services">
            @foreach ($props['navigation']['services'] ?? [] as $link)
                <a href="{{ $link['url'] }}">{{ $link['title'] }}</a>
            @endforeach
            @foreach ($props['navigation']['solutions'] ?? [] as $link)
                <a href="{{ $link['url'] }}">{{ $link['title'] }}</a>
            @endforeach
        </nav>
    </footer>
</div>
