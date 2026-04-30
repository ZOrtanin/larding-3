<?php

use App\Services\CmsUpdateService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cms:version', function () {
    $updater = app(CmsUpdateService::class);

    $this->info('Code version: '.$updater->currentVersion());
    $this->line('Installed version: '.($updater->installedVersion() ?? 'not set'));
})->purpose('Show current code and installed CMS versions');

Artisan::command('cms:check-update', function () {
    $updater = app(CmsUpdateService::class);

    $this->info('Installed version: '.($updater->installedVersion() ?? 'not set'));

    $release = $updater->latestRelease();

    if ($release === null) {
        $this->warn('Update manifest is not configured.');

        return self::SUCCESS;
    }

    $this->line('Latest version: '.$release['version']);
    $this->line('Archive URL: '.$release['url']);

    if (! $updater->remoteUpdateAvailable()) {
        $this->info('No new update is available.');

        return self::SUCCESS;
    }

    $this->info('A new update is available.');

    return self::SUCCESS;
})->purpose('Check latest CMS version on the update server');

Artisan::command('cms:update', function () {
    $updater = app(CmsUpdateService::class);

    $this->info('Code version: '.$updater->currentVersion());
    $this->line('Installed version: '.($updater->installedVersion() ?? 'not set'));

    $release = $updater->latestRelease();

    if ($release === null) {
        $this->warn('Update manifest is not configured.');

        return self::FAILURE;
    }

    $this->line('Latest version: '.$release['version']);

    if (! $updater->remoteUpdateAvailable()) {
        $this->info('No new update is available.');

        return self::SUCCESS;
    }

    $updater->updateFromArchiveUrl(
        function (string $message): void {
            $this->line($message);
        },
        $release['url'],
        $release['version'],
    );

    $this->info('CMS update finished successfully.');

    return self::SUCCESS;
})->purpose('Download and apply CMS update from the configured release archive');
