<h1>{{ $seo['breadcrumbs'][1]['name'] ?? __('Services') }}</h1>
<p>{{ $seo['description'] }}</p>
@foreach ($services as $service)
    <h2><a href="{{ $service['url'] }}">{{ $service['title'] }}</a></h2>
    @if ($service['excerpt'])<p>{{ $service['excerpt'] }}</p>@endif
    @if (! empty($service['children']))
        <ul>@foreach ($service['children'] as $child)<li><a href="{{ $child['url'] }}">{{ $child['title'] }}</a>@if ($child['excerpt']): {{ $child['excerpt'] }}@endif</li>@endforeach</ul>
    @endif
@endforeach
