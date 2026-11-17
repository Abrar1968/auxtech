<h1>{{ $intro['heading'] }}</h1>
@if ($intro['lead'])<p>{{ $intro['lead'] }}</p>@endif
@foreach ($posts as $post)
    <article>
        <h2><a href="{{ $post['url'] }}">{{ $post['title'] }}</a></h2>
        @if ($post['excerpt'])<p>{{ $post['excerpt'] }}</p>@endif
    </article>
@endforeach
@if ($pagination['prev'])<a href="{{ $pagination['prev'] }}" rel="prev">{{ __('Newer') }}</a>@endif
@if ($pagination['next'])<a href="{{ $pagination['next'] }}" rel="next">{{ __('Older') }}</a>@endif
