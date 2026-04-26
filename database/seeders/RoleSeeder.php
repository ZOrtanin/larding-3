<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Супер администратор', 'slug' => 'super_admin'],
            ['name' => 'Администратор', 'slug' => 'admin'],
            ['name' => 'Редактор', 'slug' => 'editor'],
            ['name' => 'Менеджер', 'slug' => 'manager'],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}
