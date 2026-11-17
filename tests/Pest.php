<?php

use App\Enums\PublishStatus;
use App\Models\Client;
use App\Models\Industry;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extend(TestCase::class)->in('Install');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function admin(string $role = 'Super Admin'): User
{
    test()->seed(PermissionSeeder::class);

    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole($role);

    return $user;
}

function industry(string $slug = 'fashion', string $name = 'Fashion & Apparel'): Industry
{
    return Industry::query()->firstOrCreate(['slug' => $slug], ['name' => ['en' => $name, 'bn' => 'ফ্যাশন']]);
}

function solution(array $attributes = []): Solution
{
    return Solution::query()->create($attributes + [
        'name' => ['en' => 'Auxtech Ecommerce'],
        'summary' => ['en' => 'A complete e-commerce system.'],
        'status' => PublishStatus::Published,
    ]);
}

function service(array $attributes = []): Service
{
    return Service::query()->create($attributes + [
        'title' => ['en' => 'Website Development', 'bn' => 'ওয়েবসাইট ডেভেলপমেন্ট'],
        'excerpt' => ['en' => 'Websites for Bangladeshi businesses.'],
        'status' => PublishStatus::Published,
    ]);
}

function project(array $attributes = []): Project
{
    $name = $attributes['name'] ?? 'Gearzen';
    unset($attributes['name']);

    return Project::query()->create($attributes + [
        'client_id' => Client::query()->create(['name' => $name])->id,
        'industry_id' => industry()->id,
        'title' => ['en' => $name],
        'tagline' => ['en' => "Online store for {$name}."],
        'live_url' => 'https://example.com',
        'status' => PublishStatus::Published,
    ]);
}

function post(array $attributes = []): Post
{
    return Post::query()->create($attributes + [
        'title' => ['en' => 'E-commerce website cost in Bangladesh'],
        'excerpt' => ['en' => 'A price guide.'],
        'body' => ['en' => '<p>'.str_repeat('word ', 450).'</p>'],
        'status' => PublishStatus::Published,
        'published_at' => now()->subDay(),
    ]);
}

/** A valid lead submission. */
function leadPayload(array $overrides = []): array
{
    return $overrides + [
        'name' => 'Mahin Rahman',
        'phone' => '01805019415',
        'message' => 'I need an online store.',
        'source' => 'contact_form',
        'started_at' => now()->subSeconds(30)->getTimestampMs(),
    ];
}
