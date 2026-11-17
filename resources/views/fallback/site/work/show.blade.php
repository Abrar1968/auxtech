<h1>{{ $project['title'] }}</h1>
@if ($project['is_demo'])<p><strong>{{ __('Demo project') }}</strong></p>@endif
@if ($project['tagline'])<p>{{ $project['tagline'] }}</p>@endif
<ul>
    @if ($project['client'])<li>{{ __('Client') }}: {{ $project['client']['name'] }}</li>@endif
    @if ($project['industry'])<li>{{ __('Industry') }}: <a href="{{ $project['industry']['url'] }}">{{ $project['industry']['name'] }}</a></li>@endif
    @if ($project['solution'])<li>{{ __('Solution') }}: <a href="{{ $project['solution']['url'] }}">{{ $project['solution']['name'] }}</a></li>@endif
    @if ($project['live_url'])<li>{{ __('Website') }}: <a href="{{ $project['live_url'] }}" rel="noopener">{{ $project['live_url'] }}</a></li>@endif
</ul>
@if ($project['cover'])
    <img src="{{ $project['cover']['medium'] }}" alt="{{ $project['cover']['alt'] }}" width="960" height="600" loading="lazy">
@endif
@if ($project['summary'])<p>{{ $project['summary'] }}</p>@endif
@foreach (['challenge' => __('Challenge'), 'approach' => __('What we built'), 'outcome' => __('Result')] as $key => $heading)
    @if ($project[$key])<h2>{{ $heading }}</h2><div>{!! $project[$key] !!}</div>@endif
@endforeach
@if ($project['metrics'])
    <ul>@foreach ($project['metrics'] as $metric)<li>{{ $metric['value'] }} {{ $metric['label'] }}</li>@endforeach</ul>
@endif
@if ($project['features'])
    <h2>{{ __('Modules used') }}</h2>
    <ul>@foreach ($project['features'] as $feature)<li>{{ $feature['title'] }}</li>@endforeach</ul>
@endif
@if ($project['services'])
    <p>{{ __('Services') }}: @foreach ($project['services'] as $service)<a href="{{ $service['url'] }}">{{ $service['title'] }}</a> @endforeach</p>
@endif
@foreach ($testimonials as $testimonial)
    <blockquote><p>{{ $testimonial['quote'] }}</p><footer>{{ $testimonial['author_name'] }}</footer></blockquote>
@endforeach
@if ($related)
    <h2>{{ __('Related projects') }}</h2>
    @include('fallback._projects', ['projects' => $related])
@endif
