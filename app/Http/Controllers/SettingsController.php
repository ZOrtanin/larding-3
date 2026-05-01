<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\CmsUpdateService;
use App\Services\MailSettingsService;
use App\Mail\TestMailSettingsMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SettingsController extends Controller
{
    // Ключи настроек сайта, которые сохраняются в таблицу settings.
    private const SETTINGS_KEYS = [
        'site_name',
        'site_description',
        'site_logo',
        'validation_rules',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'orders_notification_email',
    ];

    // Открывает страницу настроек и подставляет сохранённые значения.
    public function edit(Request $request): View {
        $settings = $this->loadSettings();
        $updateState = $this->loadUpdateState(app(CmsUpdateService::class));

        return view('settings.edit', [
            'user' => $request->user(),
            'settings' => $settings,
            'updateState' => $updateState,
        ]);
    }

    // Сохраняет общие настройки сайта и почты из формы админки.
    public function update(Request $request): RedirectResponse {
        $update_settings_name = '';

        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'site_logo' => ['nullable', 'string', 'max:2048'],
            'validation_rules' => ['nullable', 'string', 'max:10000'],
            'mail_driver' => ['nullable', 'string', 'max:255'],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'string', 'max:20'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', 'max:255'],
            'mail_from_address' => ['nullable', 'email', 'max:255'],
            'mail_from_name' => ['nullable', 'string', 'max:255'],
            'orders_notification_email' => ['nullable', 'email', 'max:255'],
        ]);



        foreach (self::SETTINGS_KEYS as $key) {
            
            if($request[$key]!== null){
                Setting::setValue($key, $validated[$key] ?? null);
                $update_settings_name = explode('_', $key)[0];
            }
            
        }

        
        return Redirect::route('settings.edit')->with('status', 'settings-updated-'.$update_settings_name);
       
        
    }

    public function sendTestMail(MailSettingsService $mailSettingsService): RedirectResponse
    {
        try {
            $mailSettingsService->apply();

            $recipient = $mailSettingsService->notificationEmail();

            if ($recipient === '') {
                return Redirect::route('settings.edit')->with('cms_mail_error', 'Укажите email для уведомлений о заказах.');
            }

            Mail::to($recipient)->send(new TestMailSettingsMail());

            return Redirect::route('settings.edit')->with('status', 'mail-test-sent');
        } catch (\Throwable $exception) {
            return Redirect::route('settings.edit')->with('cms_mail_error', $exception->getMessage());
        }
    }

    public function updateCms(CmsUpdateService $cmsUpdateService): RedirectResponse
    {
        try {
            $latestRelease = $cmsUpdateService->latestRelease();

            if ($latestRelease === null || ! $cmsUpdateService->remoteUpdateAvailable()) {
                return Redirect::route('settings.edit')->with('status', 'cms-update-not-available');
            }

            $cmsUpdateService->updateFromArchiveUrl(
                progress: null,
                archiveUrl: $latestRelease['url'],
                targetVersion: $latestRelease['version'],
            );

            return Redirect::route('settings.edit')->with('status', 'cms-updated');
        } catch (\Throwable $exception) {
            return Redirect::route('settings.edit')
                ->with('cms_update_error', $exception->getMessage());
        }
    }

    // Загружает настройки сайта из базы и дополняет их значениями по умолчанию.
    private function loadSettings(): array {
        $storedSettings = Setting::query()
            ->whereIn('key', self::SETTINGS_KEYS)
            ->pluck('value', 'key');

        $defaults = [
            'site_name' => 'Супер сайт',
            'site_description' => '',
            'site_logo' => '',
            'validation_rules' => '',
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.larding.com',
            'mail_port' => '465',
            'mail_username' => 'info@larding.ru',
            'mail_password' => '',
            'mail_encryption' => 'ssl',
            'mail_from_address' => 'info@larding.ru',
            'mail_from_name' => 'Larding CMS',
            'orders_notification_email' => 'orders@larding.ru',
        ];

        foreach ($defaults as $key => $value) {
            $defaults[$key] = $storedSettings[$key] ?? $value;
        }

        return $defaults;
    }

    private function loadUpdateState(CmsUpdateService $cmsUpdateService): array
    {
        $state = [
            'current_version' => $cmsUpdateService->currentVersion(),
            'installed_version' => $cmsUpdateService->installedVersion() ?? 'unknown',
            'latest_version' => null,
            'archive_url' => null,
            'update_available' => false,
            'manifest_error' => null,
        ];

        try {
            $latestRelease = $cmsUpdateService->latestRelease();

            if ($latestRelease !== null) {
                $state['latest_version'] = $latestRelease['version'];
                $state['archive_url'] = $latestRelease['url'];
                $state['update_available'] = $cmsUpdateService->remoteUpdateAvailable();
            }
        } catch (\Throwable $exception) {
            $state['manifest_error'] = $exception->getMessage();
        }

        return $state;
    }
}
