<?php

use App\Support\ObsoleteFiles;
use Illuminate\Support\Facades\Route;

/** Files on disk that would stop the generated feeds from being served. */
it('has no file in public/ that the web server would serve instead of a route', function () {
    $shadowed = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => in_array('GET', $route->methods()) && ! str_contains($route->uri(), '{'))
        ->map(fn ($route) => $route->uri())
        ->filter(fn (string $uri) => $uri !== '/' && file_exists(public_path($uri)))
        ->values();

    expect($shadowed->all())->toBe([]);
});

it('removes files left behind by older releases during the database update', function () {
    $file = base_path(ObsoleteFiles::PATHS[0]);
    file_put_contents($file, "User-agent: *\nDisallow:\n");

    $this->actingAs(admin())->post('/admin/system/migrate')->assertSessionHas('success');

    expect(file_exists($file))->toBeFalse();
    $this->get('/robots.txt')->assertSee('Sitemap: '.url('/sitemap.xml'));
});
