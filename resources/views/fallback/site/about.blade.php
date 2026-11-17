<h1>{{ __('About') }} {{ $site['name'] }}</h1>
<p>{{ $site['description'] }}</p>
@if ($site['slogan'])<p><em>{{ $site['slogan'] }}</em></p>@endif
<ul>
    <li>{{ $site['address'] }}</li>
    <li>{{ $site['hours'] }}</li>
    <li>{{ __(':count published projects', ['count' => $projectCount]) }}</li>
    @foreach ($stats as $stat)<li>{{ $stat['value'] }}{{ $stat['suffix'] }} {{ $stat['label'] }}</li>@endforeach
</ul>
@if ($team)
    <h2>{{ __('Team') }}</h2>
    <ul>@foreach ($team as $member)<li>{{ $member['name'] }}@if ($member['role']), {{ $member['role'] }}@endif</li>@endforeach</ul>
@endif
