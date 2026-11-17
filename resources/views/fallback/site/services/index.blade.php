<h1>{{ $intro['heading'] }}</h1>
<p>{{ $intro['lead'] }}</p>
@foreach ($services as $service)
    <h2><a href="{{ $service['url'] }}">{{ $service['title'] }}</a></h2>
    @if ($service['excerpt'])<p>{{ $service['excerpt'] }}</p>@endif
    @if (! empty($service['children']))
        <ul>@foreach ($service['children'] as $child)<li><a href="{{ $child['url'] }}">{{ $child['title'] }}</a>@if ($child['excerpt']): {{ $child['excerpt'] }}@endif</li>@endforeach</ul>
    @endif
@endforeach
