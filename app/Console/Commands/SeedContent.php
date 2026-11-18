<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Project;
use App\Models\Solution;
use App\Support\SiteCache;
use Database\Seeders\CatalogueSeeder;
use Database\Seeders\ContentSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\PortfolioSeeder;
use Database\Seeders\SeedOptions;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

/**
 * Seeds the whole site's content (services, platforms, industries,
 * technologies, the full portfolio, FAQs) and optionally attaches the images
 * from the Auxtech image pack. Safe to run again: records edited in the admin
 * are kept unless --overwrite is given; images are only added where missing.
 */
class SeedContent extends Command
{
    protected $signature = 'auxtech:seed
        {--overwrite : Replace seeded text that was edited in the admin panel}
        {--images= : Path to the unzipped image pack, to attach logos and screenshots automatically}';

    protected $description = 'Seed the Auxtech site content (and optionally its images)';

    public function handle(): int
    {
        SeedOptions::$overwrite = (bool) $this->option('overwrite');

        foreach ([PermissionSeeder::class, CatalogueSeeder::class, PortfolioSeeder::class, ContentSeeder::class] as $seeder) {
            $this->components->task(class_basename($seeder), fn () => $this->callSilent('db:seed', ['--class' => $seeder, '--force' => true]) === 0);
        }

        if ($path = $this->option('images')) {
            if (! $this->attachImages(rtrim($path, '/'))) {
                return self::FAILURE;
            }
        }

        SiteCache::flush();
        $this->components->info(sprintf('Done: %d projects, %d clients, %d platforms.', Project::query()->count(), Client::query()->count(), Solution::query()->count()));

        return self::SUCCESS;
    }

    private function attachImages(string $path): bool
    {
        $manifest = $path.'/manifest.json';
        if (! is_file($manifest)) {
            $this->components->error("No manifest.json in {$path}. Point --images at the unzipped auxtech-image-pack folder.");

            return false;
        }

        $data = json_decode((string) file_get_contents($manifest), true);
        $added = 0;

        foreach ($data['logos'] ?? [] as $logo) {
            $added += $this->attach(Client::query()->where('slug', $logo['slug'])->first(), 'logo', "{$path}/{$logo['file']}", $logo['alt']);
        }

        foreach ($data['projects'] ?? [] as $item) {
            $project = Project::query()->where('slug', $item['slug'])->first();
            foreach ($item['files'] as $file) {
                $collection = ['Desktop screenshot' => 'cover', 'Mobile screenshot' => 'mobile', 'Gallery' => 'gallery'][$file['field']];
                $added += $this->attach($project, $collection, "{$path}/{$item['folder']}/{$file['file']}", $file['alt'], $file['size'] ?? null);
            }
        }

        foreach ($data['platforms'] ?? [] as $cover) {
            $added += $this->attach(Solution::query()->where('slug', $cover['slug'])->first(), 'cover', "{$path}/{$cover['file']}", $cover['alt'], $cover['size'] ?? null);
        }

        $this->components->info("{$added} images attached.");

        return true;
    }

    /** @param  array{0: int, 1: int}|null  $size */
    private function attach(?Model $model, string $collection, string $file, string $alt, ?array $size = null): int
    {
        if (! $model instanceof HasMedia || ! is_file($file)) {
            return 0;
        }

        // Single-image fields are skipped when already filled; gallery images are skipped by file name.
        $existing = $model->getMedia($collection);
        if ($collection !== 'gallery' ? $existing->isNotEmpty() : $existing->contains(fn ($m) => $m->file_name === basename($file))) {
            return 0;
        }

        $properties = ['alt' => ['en' => $alt]];
        $size ??= ($info = @getimagesize($file)) ? [$info[0], $info[1]] : null;
        if ($size) {
            $properties += ['width' => $size[0], 'height' => $size[1]];
        }

        $model->addMedia($file)->preservingOriginal()->withCustomProperties($properties)->toMediaCollection($collection);

        return 1;
    }
}
