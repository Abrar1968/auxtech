<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\Redirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class RedirectModule extends Module
{
    public function key(): string
    {
        return 'redirects';
    }

    public function model(): string
    {
        return Redirect::class;
    }

    public function fields(): array
    {
        return [
            Field::text('from_path', 'From path')->max(191)->required()->rules('starts_with:/')->help('Path only, e.g. /old-page'),
            Field::text('to_url', 'To')->max(500)->required()->help('A path (/new-page) or a full URL.'),
            Field::select('status_code', [301 => '301 Permanent', 302 => '302 Temporary'])->required()->default(301),
        ];
    }

    public function columns(): array
    {
        return ['from_path' => 'From', 'to_url' => 'To', 'status_code' => 'Code', 'hits' => 'Hits'];
    }

    public function group(): string
    {
        return 'SEO';
    }

    public function searchable(): array
    {
        return ['from_path', 'to_url'];
    }

    public function rules(?Model $record): array
    {
        return ['from_path' => ['required', 'string', 'max:191', 'starts_with:/', 'different:to_url',
            Rule::unique('redirects', 'from_path')->ignore($record)]];
    }
}
