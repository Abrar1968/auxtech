<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Stat;

class StatModule extends Module
{
    public function key(): string
    {
        return 'stats';
    }

    public function model(): string
    {
        return Stat::class;
    }

    public function fields(): array
    {
        return [
            Field::text('value')->max(40)->required()->help('The number shown, e.g. "30". It must be true on the day it is published.'),
            Field::text('suffix')->max(20)->help('e.g. "+" or "%".'),
            Field::text('label')->translatable()->required(),
            Field::toggle('is_active', 'Visible')->default(true),
        ];
    }

    public function columns(): array
    {
        return ['value' => 'Value', 'suffix' => 'Suffix', 'label' => 'Label', 'is_active' => 'Visible'];
    }

    public function label(): string
    {
        return 'Home Stats';
    }
}
