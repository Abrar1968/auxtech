<h1>{{ $heading ?? ($seo['breadcrumbs'][1]['name'] ?? __('Blog')) }}</h1>
@foreach ($posts as $post)
    <article>
        <h2><a href="{{ $post['url'] }}">{{ $post['title'] }}</a></h2>
        @if ($post['excerpt'])<p>{{ $post['excerpt'] }}</p>@endif
    </article>
@endforeach
@if ($pagination['prev'])<a href="{{ $pagination['prev'] }}" rel="prev">{{ __('Newer') }}</a>@endif
@if ($pagination['next'])<a href="{{ $pagination['next'] }}" rel="next">{{ __('Older') }}</a>@endif
