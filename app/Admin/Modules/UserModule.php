<?php

namespace App\Admin\Modules;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserModule extends Module
{
    public function key(): string
    {
        return 'users';
    }

    public function model(): string
    {
        return User::class;
    }

    public function fields(): array
    {
        return [
            Field::text('name')->required(),
            Field::email('email')->required(),
            Field::password()->help('At least 10 characters. Leave empty to keep the current password.'),
            Field::select('role', fn () => Role::query()->pluck('name', 'name')->all())->required()->virtual()->side(),
            Field::toggle('is_active', 'Can sign in')->default(true),
        ];
    }

    public function columns(): array
    {
        return ['name' => 'Name', 'email' => 'Email', 'is_active' => 'Active', 'last_login_at' => 'Last login'];
    }

    public function group(): string
    {
        return 'System';
    }

    public function searchable(): array
    {
        return ['name', 'email'];
    }

    public function rules(?Model $record): array
    {
        return ['email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($record)]];
    }

    public function beforeSave(array $data, ?Model $record): array
    {
        // Nobody may lock themselves out or demote themselves.
        if ($record && $record->is(auth()->user())) {
            unset($data['is_active'], $data['role']);
        }

        return $data;
    }

    public function afterSave(Model $record, array $data): void
    {
        if (! empty($data['role'])) {
            $record->syncRoles([$data['role']]);
        }
    }

    public function extraValues(Model $record): array
    {
        return ['role' => $record->getRoleNames()->first()];
    }

    public function deletionBlocker(Model $record): ?string
    {
        return $record->is(auth()->user()) ? 'You cannot delete your own account.' : null;
    }
}
