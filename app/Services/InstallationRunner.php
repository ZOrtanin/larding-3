<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class InstallationRunner
{
    public function initializeDatabase(): void
    {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\RoleSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\BlockSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\BlockTemplateSeeder', '--force' => true]);
    }
}
