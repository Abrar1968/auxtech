<h1>{{ $intro['heading'] }}</h1>
<p>{{ $intro['lead'] }}</p>
@foreach ($groups as $group)
    @if ($group['name'])<h2>{{ $group['name'] }}</h2>@endif
    @include('fallback._plans', ['plans' => $group['plans']])
@endforeach
