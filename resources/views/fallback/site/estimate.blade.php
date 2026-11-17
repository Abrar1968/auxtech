<h1>{{ $intro['heading'] }}</h1>
<p>{{ $intro['lead'] }}</p>
<ul>@foreach ($services as $service)<li>{{ $service['title'] }}</li>@endforeach</ul>
