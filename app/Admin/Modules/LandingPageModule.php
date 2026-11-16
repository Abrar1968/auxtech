<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Industry;
use App\Models\LandingPage;
use App\Models\Service;
use App\Models\Solution;
use App\Support\ReservedSlugs;
use Illuminate\Database\Eloquent\Model;

class LandingPageModule extends Module
{
    public function key(): string
    {
        return 'landing-pages';
    }

    public function model(): string
    {
        return LandingPage::class;
    }

    public function fields(): array
    {
        return [
            Field::text('title', 'Heading (H1)')->translatable()->required(),
            Field::slug()->required(),
            Field::textarea('intro')->translatable()->max(800),
            Field::richtext('body')->translatable()->help('Must be unique, useful content for this exact topic.'),
            Field::text('location')->help('City or area, e.g. Mirpur.'),
            Field::belongsTo('service_id', Service::class, 'title', 'Service'),
            Field::belongsTo('solution_id', Solution::class),
            Field::belongsTo('industry_id', Industry::class),
            ...Field::publishing(),
        ];
    }

    public function columns(): array
    {
        return ['title' => 'Heading', 'slug' => 'Slug', 'location' => 'Location', 'status' => 'Status'];
    }

    public function label(): string
    {
        return 'Landing Pages';
    }

    public function group(): string
    {
        return 'SEO';
    }

    public function searchable(): array
    {
        return ['title', 'slug', 'location'];
    }

    public function rules(?Model $record): array
    {
        return ['slug' => ['required', 'string', 'max:191', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'not_in:'.implode(',', ReservedSlugs::all())]];
    }
}
