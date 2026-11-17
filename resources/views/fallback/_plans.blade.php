@if (! empty($plans))
    <section>
        <h2>{{ __('Pricing') }}</h2>
        @foreach ($plans as $plan)
            <h3>{{ $plan['name'] }}@if ($plan['price'] !== null): {{ $plan['currency'] }} {{ number_format($plan['price']) }}@if ($plan['period']) / {{ $plan['period'] }}@endif @endif</h3>
            @if ($plan['description'])<p>{{ $plan['description'] }}</p>@endif
            @if ($plan['features'])<ul>@foreach ($plan['features'] as $feature)<li>{{ $feature }}</li>@endforeach</ul>@endif
        @endforeach
    </section>
@endif
