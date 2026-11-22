<h1>{{ $hero['title'] }}</h1>
<p>{{ $hero['subtitle'] }}</p>
<p>{{ $site['description'] ?? '' }}</p>

@if ($stats)
    <ul>@foreach ($stats as $stat)<li>{{ $stat['value'] }}{{ $stat['suffix'] }} {{ $stat['label'] }}</li>@endforeach</ul>
@endif

@if ($services)
    <section>
        <h2>{{ str_replace('*', '', $copy['services']['title'] ?? __('Services')) }}</h2>
        <ul>@foreach ($services as $service)<li><a href="{{ $service['url'] }}">{{ $service['title'] }}</a>@if ($service['excerpt']): {{ $service['excerpt'] }}@endif</li>@endforeach</ul>
    </section>
@endif

@if ($flagship)
    <section>
        <h2><a href="{{ $flagship['url'] }}">{{ $flagship['name'] }}</a></h2>
        <p>{{ $flagship['summary'] }}</p>
        @include('fallback._features', ['solution' => $flagship])
    </section>
@endif

@if ($projects)
    <section>
        <h2>{{ str_replace('*', '', $copy['work']['title'] ?? __('Featured work')) }}</h2>
        @include('fallback._projects', ['projects' => $projects])
        <p><a href="{{ lroute('work.index') }}">{{ __('View all :count projects', ['count' => $projectCount]) }}</a></p>
    </section>
@endif

@if ($solutions)
    <section>
        <h2>{{ __('Platforms') }}</h2>
        <ul>@foreach ($solutions as $solution)<li><a href="{{ $solution['url'] }}">{{ $solution['name'] }}</a>@if ($solution['summary']): {{ $solution['summary'] }}@endif</li>@endforeach</ul>
    </section>
@endif

@if ($clients)
    <section>
        <h2>{{ __('Clients') }}</h2>
        <p>{{ collect($clients)->pluck('name')->implode(', ') }}</p>
    </section>
@endif

@if ($testimonials)
    <section>
        <h2>{{ __('What clients say') }}</h2>
        @foreach ($testimonials as $testimonial)
            <blockquote><p>{{ $testimonial['quote'] }}</p><footer>{{ $testimonial['author_name'] }}@if ($testimonial['author_role']), {{ $testimonial['author_role'] }}@endif</footer></blockquote>
        @endforeach
    </section>
@endif

@if ($process)
    <section>
        <h2>{{ str_replace('*', '', $copy['process']['title'] ?? __('How we work')) }}</h2>
        <ol>@foreach ($process as $step)<li>{{ $step['title'] }}: {{ $step['description'] }}</li>@endforeach</ol>
    </section>
@endif

@if ($reasons)
    <section>
        <h2>{{ str_replace('*', '', $copy['reasons']['title'] ?? '') }}</h2>
        <ul>@foreach ($reasons as $reason)<li>{{ $reason['title'] }}: {{ $reason['description'] }}</li>@endforeach</ul>
    </section>
@endif

@include('fallback._plans', ['plans' => $plans])

@if ($posts)
    <section>
        <h2>{{ __('From the blog') }}</h2>
        <ul>@foreach ($posts as $post)<li><a href="{{ $post['url'] }}">{{ $post['title'] }}</a></li>@endforeach</ul>
    </section>
@endif

@include('fallback._faqs', ['faqs' => $faqs])
