<?php

namespace Database\Seeders;

use App\Admin\ModuleRegistry;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/** Roles and permissions from config/admin.php. Safe to run again after adding modules. */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $groups = ['content' => []] + array_fill_keys(array_keys(config('admin.abilities')), []);

        foreach (app(ModuleRegistry::class)->all() as $module) {
            $group = collect(config('admin.groups'))->search(fn (array $keys) => in_array($module->permission(), $keys, true)) ?: 'content';

            foreach (['view', 'create', 'update', 'delete'] as $action) {
                $groups[$group][] = $module->permission().'.'.$action;
            }
        }

        foreach (config('admin.abilities') as $group => $abilities) {
            $groups[$group] = array_merge($groups[$group] ?? [], $abilities);
        }

        foreach (array_unique(array_merge(...array_values($groups))) as $name) {
            Permission::findOrCreate($name, 'web');
        }

        foreach (config('admin.roles') as $roleName => $grants) {
            $role = Role::findOrCreate($roleName, 'web');

            // Super Admin passes every check through Gate::before and needs no explicit list.
            $role->syncPermissions($grants === ['*'] ? [] : array_unique(array_merge(...array_map(fn (string $g) => $groups[$g] ?? [], $grants))));
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
