<h1>{{ $intro['heading'] }}</h1>
<p>{{ $intro['lead'] }}</p>
@foreach ($groups as $group)
    @include('fallback._faqs', ['faqs' => $group['faqs']])
@endforeach
