<h1>{{ $seo['breadcrumbs'][1]['name'] ?? __('Solutions') }}</h1>
<p>{{ $seo['description'] }}</p>
@foreach ($solutions as $solution)
    <h2><a href="{{ $solution['url'] }}">{{ $solution['name'] }}</a></h2>
    @if ($solution['summary'])<p>{{ $solution['summary'] }}</p>@endif
@endforeach
