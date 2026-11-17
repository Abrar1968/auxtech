<h1>{{ $service['title'] }}</h1>
@if ($service['tagline'])<p>{{ $service['tagline'] }}</p>@endif
@if ($service['excerpt'])<p>{{ $service['excerpt'] }}</p>@endif
@if ($service['body'])<div>{!! $service['body'] !!}</div>@endif
@if ($service['features'])
    <h2>{{ __('What is included') }}</h2>
    <ul>@foreach ($service['features'] as $feature)<li>{{ $feature['title'] }}@if ($feature['description']): {{ $feature['description'] }}@endif</li>@endforeach</ul>
@endif
@if ($service['process_steps'])
    <h2>{{ __('How we work') }}</h2>
    <ol>@foreach ($service['process_steps'] as $step)<li>{{ $step['title'] }}@if ($step['description']): {{ $step['description'] }}@endif</li>@endforeach</ol>
@endif
@if (! empty($service['children']))
    <ul>@foreach ($service['children'] as $child)<li><a href="{{ $child['url'] }}">{{ $child['title'] }}</a></li>@endforeach</ul>
@endif
@if ($projects)
    <h2>{{ __('Projects') }}</h2>
    @include('fallback._projects', ['projects' => $projects])
@endif
@include('fallback._plans', ['plans' => $plans])
@include('fallback._faqs', ['faqs' => $faqs])
