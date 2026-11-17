<h1>{{ __('Contact') }} {{ $site['name'] }}</h1>
<ul>
    <li>WhatsApp: <a href="{{ $whatsappUrl }}" rel="nofollow noopener">{{ $site['phoneDisplay'] }}</a></li>
    <li>{{ __('Email') }}: <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></li>
    <li>{{ __('Office') }}: {{ $site['address'] }}</li>
    <li>{{ __('Hours') }}: {{ $site['hours'] }}</li>
</ul>
