@if (! empty($projects))
    <ul>
        @foreach ($projects as $project)
            <li>
                <a href="{{ $project['url'] }}">{{ $project['title'] }}</a>@if (! empty($project['is_demo'])) ({{ __('Demo') }})@endif
                @if (! empty($project['industry']['name'])) — {{ $project['industry']['name'] }}@endif
                @if (! empty($project['tagline'])): {{ $project['tagline'] }}@endif
            </li>
        @endforeach
    </ul>
@endif
