<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\TeamMember;

class TeamMemberModule extends Module
{
    public function key(): string
    {
        return 'team';
    }

    public function model(): string
    {
        return TeamMember::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->required(),
            Field::text('role')->translatable(),
            Field::textarea('bio')->translatable()->max(1000),
            Field::repeater('socials', [Field::text('label')->required()->max(40), Field::url('url')->required()], 'Links'),
            Field::image('photo'),
            Field::toggle('is_active', 'Visible')->default(true),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Name', 'role' => 'Role', 'is_active' => 'Visible'];
    }

    public function label(): string
    {
        return 'Team';
    }

    public function searchable(): array
    {
        return ['name'];
    }
}
