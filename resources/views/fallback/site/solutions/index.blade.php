<h1>{{ $intro['heading'] }}</h1>
<p>{{ $intro['lead'] }}</p>
@foreach ($solutions as $solution)
    <h2><a href="{{ $solution['url'] }}">{{ $solution['name'] }}</a></h2>
    @if ($solution['summary'])<p>{{ $solution['summary'] }}</p>@endif
@endforeach
