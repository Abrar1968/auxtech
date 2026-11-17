<h1>{{ $status }} — {{ $status === 404 ? __('Page not found') : __('Something went wrong') }}</h1>
<p><a href="{{ lroute('home') }}">{{ __('Go to the home page') }}</a> · <a href="{{ lroute('work.index') }}">{{ __('See our work') }}</a></p>
