<h1>{{ $seo['breadcrumbs'][1]['name'] ?? __('FAQ') }}</h1>
@foreach ($groups as $group)
    @include('fallback._faqs', ['faqs' => $group['faqs']])
@endforeach
