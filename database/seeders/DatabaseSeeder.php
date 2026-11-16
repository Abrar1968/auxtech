<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([PermissionSeeder::class, CatalogueSeeder::class, PortfolioSeeder::class, ContentSeeder::class]);

        // The browser installer passes the admin account; locally it comes from .env.
        $admin = config('auxtech.install_admin') ?? [
            'name' => env('ADMIN_NAME', 'Auxtech Admin'),
            'email' => env('ADMIN_EMAIL', 'auxtechbd@gmail.com'),
            'password' => env('ADMIN_PASSWORD'),
        ];

        if (filled($admin['password'] ?? null) && ! User::query()->where('email', $admin['email'])->exists()) {
            User::query()->create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => $admin['password'],
                'is_active' => true,
            ])->forceFill(['email_verified_at' => now()])->assignRole('Super Admin');
        }
    }
}
