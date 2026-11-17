<h1>{{ $seo['breadcrumbs'][1]['name'] ?? __('Estimate') }}</h1>
<p>{{ $seo['description'] }}</p>
<ul>@foreach ($services as $service)<li>{{ $service['title'] }}</li>@endforeach</ul>
