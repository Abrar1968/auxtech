<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Client;
use App\Models\Project;
use App\Models\Testimonial;

class TestimonialModule extends Module
{
    public function key(): string
    {
        return 'testimonials';
    }

    public function model(): string
    {
        return Testimonial::class;
    }

    public function fields(): array
    {
        return [
            Field::text('author_name')->required(),
            Field::text('author_role', 'Role and company')->translatable(),
            Field::textarea('quote')->translatable()->required()->max(1500)->help('Use the client\'s real words only.'),
            Field::number('rating')->rules('integer', 'between:1,5'),
            Field::url('video_url'),
            Field::belongsTo('client_id', Client::class),
            Field::belongsTo('project_id', Project::class, 'title'),
            Field::image('avatar', 'Photo'),
            Field::toggle('is_featured', 'Featured'),
            ...Field::publishing(),
        ];
    }

    public function columns(): array
    {
        return ['author_name' => 'Author', 'client.name' => 'Client', 'rating' => 'Rating', 'status' => 'Status'];
    }

    public function searchable(): array
    {
        return ['author_name'];
    }

    public function with(): array
    {
        return ['client'];
    }
}
