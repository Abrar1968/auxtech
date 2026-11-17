<h1>{{ $seo['breadcrumbs'][1]['name'] ?? __('Work') }}</h1>
<p>{{ $seo['description'] }}</p>
<nav aria-label="{{ __('Industries') }}">
    @foreach ($industries as $industry)
        <a href="{{ $industry['url'] }}">{{ $industry['name'] }} ({{ $industry['projects_count'] }})</a>
    @endforeach
</nav>
@include('fallback._projects', ['projects' => $projects])
