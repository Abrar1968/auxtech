<h1>{{ $intro['heading'] }}</h1>
<p>{{ $intro['lead'] }}</p>
<nav aria-label="{{ __('Industries') }}">
    @foreach ($industries as $industry)
        <a href="{{ $industry['url'] }}">{{ $industry['name'] }} ({{ $industry['projects_count'] }})</a>
    @endforeach
</nav>
@include('fallback._projects', ['projects' => $projects])
