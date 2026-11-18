<?php

namespace Database\Seeders;

use App\Enums\ProjectType;
use App\Enums\PublishStatus;
use App\Models\Client;
use App\Models\Industry;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use App\Models\SolutionFeature;
use App\Models\Technology;
use Database\Seeders\Concerns\SeedsContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Every project Auxtech has built, with its client, services, technologies
 * and the Auxtech Ecommerce modules it uses. Text lives in
 * database/seeders/data/portfolio.php. Images are uploaded in the admin panel
 * (or attached from the image pack with php artisan auxtech:seed --images=...).
 */
class PortfolioSeeder extends Seeder
{
    use SeedsContent;

    public function run(): void
    {
        $industries = Industry::query()->pluck('id', 'slug');
        $solutions = Solution::query()->pluck('id', 'slug');
        $services = Service::query()->pluck('id', 'slug');
        $technologies = Technology::query()->pluck('id', 'slug');
        $modules = SolutionFeature::query()->pluck('id', 'slug');

        foreach (require __DIR__.'/data/portfolio.php' as $order => $item) {
            [$client] = $this->seedRecord(Client::class, ['slug' => $item['slug']], [
                'name' => $item['client'],
                'website_url' => $item['url'],
                'industry_id' => $industries[$item['industry']] ?? null,
                // Auxtech's own products and demos are not shown as clients.
                'show_logo' => $item['type'] === 'live',
                'sort_order' => $order + 1,
            ]);

            [$project, $created] = $this->seedRecord(Project::class, ['slug' => $item['slug']], [
                'client_id' => $client->id,
                'industry_id' => $industries[$item['industry']] ?? null,
                'solution_id' => $solutions[$item['solution']] ?? null,
                'type' => ProjectType::from($item['type']),
                'title' => ['en' => $item['client']],
                'tagline' => $item['tagline'],
                'summary' => $item['summary'],
                'challenge' => $item['challenge'],
                'approach' => $item['approach'],
                'outcome' => $item['outcome'],
                'live_url' => $item['url'],
                'is_featured' => $item['featured'],
                'status' => PublishStatus::Published,
                'sort_order' => $order + 1,
            ]);

            if ($created || SeedOptions::$overwrite) {
                $project->services()->sync($this->ids($services, $item['services']));
                $project->technologies()->sync($this->ids($technologies, $item['technologies']));
                $project->features()->sync($this->ids($modules, $item['modules']));
            }
        }
    }

    /**
     * @param  Collection<string, int>  $map
     * @param  list<string>  $slugs
     * @return list<int>
     */
    private function ids($map, array $slugs): array
    {
        return array_values(array_filter(array_map(fn (string $slug) => $map[$slug] ?? null, $slugs)));
    }
}
