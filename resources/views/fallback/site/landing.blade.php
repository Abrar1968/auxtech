<h1>{{ $landing['title'] }}</h1>
@if ($landing['intro'])<p>{{ $landing['intro'] }}</p>@endif
@if ($landing['body'])<div>{!! $landing['body'] !!}</div>@endif
@if ($service)<p><a href="{{ $service['url'] }}">{{ $service['title'] }}</a></p>@endif
@if ($solution)<p><a href="{{ $solution['url'] }}">{{ $solution['name'] }}</a></p>@endif
@include('fallback._projects', ['projects' => $projects])
@include('fallback._faqs', ['faqs' => $faqs])
