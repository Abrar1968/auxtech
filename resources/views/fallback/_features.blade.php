@foreach ($solution['feature_groups'] ?? [] as $group)
    <h3>{{ $group['name'] }}</h3>
    <ul>
        @foreach ($group['features'] as $feature)
            <li>{{ $feature['title'] }}@if ($feature['description']): {{ $feature['description'] }}@endif</li>
        @endforeach
    </ul>
@endforeach
