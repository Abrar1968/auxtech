<article>
    <h1>{{ $post['title'] }}</h1>
    <p>@if ($post['author']){{ $post['author'] }} · @endif<time datetime="{{ $post['published_at'] }}">{{ \Illuminate\Support\Carbon::parse($post['published_at'])->toFormattedDateString() }}</time></p>
    @if ($post['cover'])<img src="{{ $post['cover']['medium'] }}" alt="{{ $post['cover']['alt'] }}" width="960" loading="lazy">@endif
    <div>{!! $post['body'] !!}</div>
</article>
@if ($related)
    <h2>{{ __('Related articles') }}</h2>
    <ul>@foreach ($related as $item)<li><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>@endforeach</ul>
@endif
