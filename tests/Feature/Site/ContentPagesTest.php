<?php

use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\Technology;
use Database\Seeders\CatalogueSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(fn () => $this->seed(CatalogueSeeder::class));

it('shows team members on the about page', function () {
    TeamMember::query()->create(['name' => 'Rahim', 'role' => ['en' => 'Lead developer'], 'socials' => [['label' => 'LinkedIn', 'url' => 'https://linkedin.com/in/x']]]);
    TeamMember::query()->create(['name' => 'Hidden', 'is_active' => false]);

    $this->get('/about')->assertInertia(fn (Assert $page) => $page
        ->has('team', 1)->where('team.0.name', 'Rahim')->where('team.0.role', 'Lead developer')->where('team.0.socials.0.label', 'LinkedIn')
        ->where('story', fn ($story) => str_contains($story, 'Dhaka'))->has('reasons', 6));
});

it('lists the technology used on a case study', function () {
    $project = project(['slug' => 'gearzen']);
    $project->technologies()->attach(Technology::query()->create(['name' => 'Laravel', 'category' => 'Backend']));

    $this->get('/work/gearzen')->assertInertia(fn (Assert $page) => $page->where('project.technologies.0.name', 'Laravel'));
});

it('lists blog posts by tag', function () {
    $post = post(['slug' => 'cost-guide']);
    $post->tags()->attach(Tag::query()->create(['name' => ['en' => 'Pricing']]));

    $this->get('/blog/tag/pricing')->assertOk()->assertInertia(fn (Assert $page) => $page->has('posts', 1)->where('intro.heading', 'Pricing'));
});

it('prints a server-rendered hero shell that matches the page title', function () {
    project(['slug' => 'gearzen']);

    $this->get('/work/gearzen')->assertSee('data-shell aria-hidden="true"', false)->assertSee('Gearzen', false);
    $this->get('/')->assertSee('data-shell', false);
    $this->get('/login')->assertDontSee('data-shell', false);
});
