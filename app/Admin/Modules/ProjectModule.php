<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Client;
use App\Models\Industry;
use App\Models\Project;
use App\Models\Service;
use App\Models\Solution;
use App\Models\SolutionFeature;
use App\Models\Technology;

class ProjectModule extends Module
{
    public function key(): string
    {
        return 'projects';
    }

    public function model(): string
    {
        return Project::class;
    }

    public function fields(): array
    {
        return [
            Field::text('title')->translatable()->required(),
            Field::slug(),
            Field::text('tagline')->translatable()->help('One sentence: who the client is and what was delivered.'),
            Field::textarea('summary')->translatable()->max(600),
            Field::richtext('challenge')->translatable(),
            Field::richtext('approach', 'What we built')->translatable(),
            Field::richtext('outcome', 'Result')->translatable(),
            Field::repeater('metrics', [Field::text('value')->required()->max(40), Field::text('label')->translatable()->required()], 'Result numbers')
                ->help('Only real, verifiable numbers.'),
            Field::url('live_url', 'Live site'),
            Field::date('launched_at', 'Launch date'),
            Field::belongsToMany('services', Service::class, 'title'),
            Field::belongsToMany('technologies', Technology::class),
            Field::belongsToMany('features', SolutionFeature::class, 'title', 'Modules used'),
            Field::images('gallery'),
            Field::select('type', ['live' => 'Live client site', 'demo' => 'Demo / pre-launch', 'product' => 'Own product'])->required()->default('live')->side(),
            Field::belongsTo('client_id', Client::class),
            Field::belongsTo('solution_id', Solution::class),
            Field::belongsTo('industry_id', Industry::class),
            Field::image('cover', 'Desktop screenshot'),
            Field::image('mobile', 'Mobile screenshot'),
            Field::toggle('is_featured', 'Featured on home page'),
            ...Field::publishing(),
        ];
    }

    public function columns(): array
    {
        return ['title' => 'Title', 'client.name' => 'Client', 'industry.name' => 'Industry', 'type' => 'Type', 'status' => 'Status', 'is_featured' => 'Featured'];
    }

    public function searchable(): array
    {
        return ['title', 'slug', 'live_url'];
    }

    public function with(): array
    {
        return ['client', 'industry'];
    }

    public function filters(): array
    {
        return [
            'status' => ['draft' => 'Draft', 'published' => 'Published'],
            'type' => ['live' => 'Live', 'demo' => 'Demo', 'product' => 'Product'],
        ];
    }
}
