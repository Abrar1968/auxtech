<h1>{{ $seo['breadcrumbs'][1]['name'] ?? __('Pricing') }}</h1>
<p>{{ $seo['description'] }}</p>
@foreach ($groups as $group)
    @if ($group['name'])<h2>{{ $group['name'] }}</h2>@endif
    @include('fallback._plans', ['plans' => $group['plans']])
@endforeach
