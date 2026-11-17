@if (! empty($faqs))
    <section>
        <h2>{{ __('Frequently asked questions') }}</h2>
        @foreach ($faqs as $faq)
            <h3>{{ $faq['question'] }}</h3>
            <div>{!! $faq['answer'] !!}</div>
        @endforeach
    </section>
@endif
