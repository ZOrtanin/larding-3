<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Throwable;

class InstallationState
{
    public const INSTALLED_KEY = 'app.installed';

    public function isInstalled(): bool
    {
        if (filter_var(env('APP_INSTALLED', false), FILTER_VALIDATE_BOOL)) {
            return true;
        }

        try {
            if (! Schema::hasTable('settings')) {
                return false;
            }

            return Setting::getValue(self::INSTALLED_KEY) === '1';
        } catch (QueryException|Throwable) {
            return false;
        }
    }
}
