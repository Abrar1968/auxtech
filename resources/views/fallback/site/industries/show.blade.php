<h1>{{ $industry['name'] }}</h1>
@if ($industry['description'])<p>{{ $industry['description'] }}</p>@endif
@include('fallback._projects', ['projects' => $projects])
