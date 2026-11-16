<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Client;
use App\Models\Industry;

class ClientModule extends Module
{
    public function key(): string
    {
        return 'clients';
    }

    public function model(): string
    {
        return Client::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->required(),
            Field::slug(),
            Field::url('website_url', 'Website'),
            Field::text('location'),
            Field::belongsTo('industry_id', Industry::class),
            Field::image('logo'),
            Field::toggle('show_logo', 'Show in client strip')->default(true),
            Field::toggle('is_featured', 'Featured'),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Name', 'industry.name' => 'Industry', 'website_url' => 'Website', 'show_logo' => 'Shown'];
    }

    public function searchable(): array
    {
        return ['name', 'website_url'];
    }

    public function with(): array
    {
        return ['industry'];
    }
}
