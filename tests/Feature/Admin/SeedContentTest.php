<?php

use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\File;

/** php artisan auxtech:seed: the whole site's content, safe to re-run, optional image pack. */
it('seeds every project, client, service and platform', function () {
    $this->artisan('auxtech:seed')->assertSuccessful();

    $gearzen = Project::query()->where('slug', 'gearzen')->firstOrFail();

    expect(Project::query()->count())->toBe(33)
        ->and(Client::query()->where('show_logo', true)->count())->toBe(Project::query()->where('type', 'live')->count())
        ->and(Service::query()->whereNull('parent_id')->count())->toBe(6)
        ->and($gearzen->technologies()->pluck('name')->all())->toContain('Laravel', 'Vue.js')
        ->and($gearzen->features()->count())->toBeGreaterThan(3)
        ->and($gearzen->getTranslation('summary', 'bn'))->not->toBeEmpty();
});

it('keeps admin edits when run again, and restores the seeded text with --overwrite', function () {
    $this->artisan('auxtech:seed')->assertSuccessful();
    $project = Project::query()->where('slug', 'gearzen')->firstOrFail();
    $project->setTranslation('tagline', 'en', 'Edited in the admin')->save();

    $this->artisan('auxtech:seed')->assertSuccessful();
    expect($project->fresh()->getTranslation('tagline', 'en'))->toBe('Edited in the admin')
        ->and(Project::query()->count())->toBe(33);

    $this->artisan('auxtech:seed', ['--overwrite' => true])->assertSuccessful();
    expect($project->fresh()->getTranslation('tagline', 'en'))->not->toBe('Edited in the admin');
});

it('attaches logos and screenshots from the image pack once', function () {
    $pack = storage_path('framework/testing-pack');
    File::ensureDirectoryExists("{$pack}/1-client-logos");
    File::ensureDirectoryExists("{$pack}/2-project-screenshots/01-gearzen");
    $png = function (string $path) {
        $im = imagecreatetruecolor(40, 20);
        imagepng($im, $path);
        imagedestroy($im);
    };
    $png("{$pack}/1-client-logos/01-gearzen.png");
    $png("{$pack}/2-project-screenshots/01-gearzen/1-desktop-screenshot.png");
    $png("{$pack}/2-project-screenshots/01-gearzen/3-gallery-1.png");
    File::put("{$pack}/manifest.json", json_encode([
        'logos' => [['file' => '1-client-logos/01-gearzen.png', 'slug' => 'gearzen', 'alt' => 'Gearzen logo']],
        'projects' => [['folder' => '2-project-screenshots/01-gearzen', 'slug' => 'gearzen', 'files' => [
            ['file' => '1-desktop-screenshot.png', 'field' => 'Desktop screenshot', 'alt' => 'Gearzen home page', 'size' => [40, 20]],
            ['file' => '3-gallery-1.png', 'field' => 'Gallery', 'alt' => 'Gearzen section'],
        ]]],
        'platforms' => [],
    ]));

    $this->artisan('auxtech:seed', ['--images' => $pack])->expectsOutputToContain('3 images attached')->assertSuccessful();
    $this->artisan('auxtech:seed', ['--images' => $pack])->expectsOutputToContain('0 images attached')->assertSuccessful();

    $project = Project::query()->where('slug', 'gearzen')->firstOrFail();
    expect($project->getFirstMedia('cover')->getCustomProperty('alt'))->toBe(['en' => 'Gearzen home page'])
        ->and($project->getMedia('gallery'))->toHaveCount(1)
        ->and($project->client->getFirstMedia('logo'))->not->toBeNull();

    File::deleteDirectory($pack);
});

it('explains a wrong image pack path', function () {
    $this->artisan('auxtech:seed', ['--images' => '/no/such/folder'])->expectsOutputToContain('No manifest.json')->assertFailed();
});
