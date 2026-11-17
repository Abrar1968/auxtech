<h1>{{ $solution['name'] }}</h1>
@if ($solution['tagline'])<p>{{ $solution['tagline'] }}</p>@endif
@if ($solution['summary'])<p>{{ $solution['summary'] }}</p>@endif
@if ($solution['body'])<div>{!! $solution['body'] !!}</div>@endif
@if ($solution['demo_url'])<p><a href="{{ $solution['demo_url'] }}" rel="noopener">{{ __('See a live demo') }}</a></p>@endif
@if ($solution['external_url'])<p><a href="{{ $solution['external_url'] }}" rel="noopener">{{ $solution['external_url'] }}</a></p>@endif
@if (! empty($solution['feature_groups']))
    <h2>{{ __('Features') }}</h2>
    @include('fallback._features', ['solution' => $solution])
@endif
@if ($projects)
    <h2>{{ __('Built with :name', ['name' => $solution['name']]) }}</h2>
    @include('fallback._projects', ['projects' => $projects])
@endif
@include('fallback._plans', ['plans' => $plans])
@include('fallback._faqs', ['faqs' => $faqs])
