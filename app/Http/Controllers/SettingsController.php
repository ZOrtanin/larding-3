<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\CmsUpdateService;
use App\Services\FaviconGenerator;
use App\Services\MailSettingsService;
use App\Mail\TestMailSettingsMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const UPDATE_STATE_SESSION_KEY = 'cms_update_state';
    private const UPDATE_PROGRESS_CACHE_PREFIX = 'cms_update_progress_user_';

    // Ключи настроек сайта, которые сохраняются в таблицу settings.
    private const SETTINGS_KEYS = [
        'site_name',
        'site_description',
        'site_logo',
        'site_favicon_source',
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
        $updateState = $this->loadUpdateState(
            app(CmsUpdateService::class),
            $request->session()->get(self::UPDATE_STATE_SESSION_KEY, []),
        );

        return view('settings.edit', [
            'user' => $request->user(),
            'settings' => $settings,
            'updateState' => $updateState,
        ]);
    }

    public function updateSiteInformation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'site_logo' => ['nullable', 'string', 'max:2048'],
        ]);

        foreach (['site_name', 'site_description', 'site_logo'] as $key) {
            Setting::setValue($key, $validated[$key] ?? null);
        }

        return Redirect::route('settings.edit')->with('status', 'settings-updated-site-information');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
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

        if ($request->has('validation_rules')) {
            Setting::setValue('validation_rules', $validated['validation_rules'] ?? null);

            return Redirect::route('settings.edit')->with('status', 'settings-updated-validation');
        }

        $mailKeys = [
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

        foreach ($mailKeys as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, $validated[$key] ?? null);
            }
        }

        return Redirect::route('settings.edit')->with('status', 'settings-updated-mail');
    }

    public function updateFavicon(Request $request, FaviconGenerator $faviconGenerator): RedirectResponse
    {
        $validated = $request->validate([
            'site_favicon' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
        ]);

        $favicon = $faviconGenerator->generateFromUpload($request->file('site_favicon'));
        Setting::setValue('site_favicon_source', $favicon['source_path']);

        return Redirect::route('settings.edit')->with('status', 'settings-updated-favicon');
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

    public function updateCms(Request $request, CmsUpdateService $cmsUpdateService): RedirectResponse|JsonResponse
    {
        $progressKey = $this->cmsUpdateProgressKey($request);

        try {
            $this->storeUpdateProgress($progressKey, [
                'status' => 'running',
                'step' => 'init',
                'message' => 'Подготавливаем обновление...',
                'percent' => 5,
                'updated_at' => now()->toDateTimeString(),
            ]);

            $latestRelease = $cmsUpdateService->latestRelease();

            if ($latestRelease === null || ! $cmsUpdateService->remoteUpdateAvailable()) {
                $this->storeUpdateProgress($progressKey, [
                    'status' => 'completed',
                    'step' => 'done',
                    'message' => 'Новых обновлений не найдено.',
                    'percent' => 100,
                    'updated_at' => now()->toDateTimeString(),
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Новых обновлений не найдено.',
                        'state' => [
                            'latest_version' => $latestRelease['version'] ?? null,
                            'archive_url' => $latestRelease['url'] ?? null,
                            'update_available' => false,
                            'manifest_error' => null,
                            'checked_at' => now()->toDateTimeString(),
                        ],
                    ]);
                }

                return Redirect::route('settings.edit')
                    ->with('status', 'cms-update-not-available')
                    ->with(self::UPDATE_STATE_SESSION_KEY, [
                        'latest_version' => $latestRelease['version'] ?? null,
                        'archive_url' => $latestRelease['url'] ?? null,
                        'update_available' => false,
                        'manifest_error' => null,
                        'checked_at' => now()->toDateTimeString(),
                    ]);
            }

            $cmsUpdateService->updateFromArchiveUrl(
                progress: function (string $message) use ($progressKey): void {
                    $this->storeUpdateProgress($progressKey, $this->progressPayloadFromMessage($message));
                },
                archiveUrl: $latestRelease['url'],
                targetVersion: $latestRelease['version'],
            );

            $this->storeUpdateProgress($progressKey, [
                'status' => 'completed',
                'step' => 'done',
                'message' => 'Обновление завершено.',
                'percent' => 100,
                'updated_at' => now()->toDateTimeString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CMS успешно обновлена.',
                    'state' => [
                        'latest_version' => $latestRelease['version'],
                        'archive_url' => $latestRelease['url'],
                        'update_available' => false,
                        'manifest_error' => null,
                        'checked_at' => now()->toDateTimeString(),
                    ],
                ]);
            }

            return Redirect::route('settings.edit')->with('status', 'cms-updated');
        } catch (\Throwable $exception) {
            $this->storeUpdateProgress($progressKey, [
                'status' => 'error',
                'step' => 'error',
                'message' => $exception->getMessage(),
                'percent' => 100,
                'updated_at' => now()->toDateTimeString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $exception->getMessage(),
                    'state' => [
                        'latest_version' => null,
                        'archive_url' => null,
                        'update_available' => false,
                        'manifest_error' => $exception->getMessage(),
                        'checked_at' => now()->toDateTimeString(),
                    ],
                ], 422);
            }

            return Redirect::route('settings.edit')
                ->with(self::UPDATE_STATE_SESSION_KEY, [
                    'latest_version' => null,
                    'archive_url' => null,
                    'update_available' => false,
                    'manifest_error' => $exception->getMessage(),
                    'checked_at' => now()->toDateTimeString(),
                ])
                ->with('cms_update_error', $exception->getMessage());
        }
    }

    public function cmsUpdateProgress(Request $request): JsonResponse
    {
        $progress = Cache::get($this->cmsUpdateProgressKey($request), [
            'status' => 'idle',
            'step' => null,
            'message' => null,
            'percent' => 0,
            'updated_at' => null,
        ]);

        return response()->json([
            'status' => 'ok',
            'data' => $progress,
        ]);
    }

    public function checkCmsUpdate(Request $request, CmsUpdateService $cmsUpdateService): RedirectResponse|JsonResponse
    {
        try {
            $latestRelease = $cmsUpdateService->latestRelease();

            $state = [
                'latest_version' => $latestRelease['version'] ?? null,
                'archive_url' => $latestRelease['url'] ?? null,
                'update_available' => $latestRelease !== null
                    ? $cmsUpdateService->remoteUpdateAvailable()
                    : false,
                'manifest_error' => null,
                'checked_at' => now()->toDateTimeString(),
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Проверка обновлений завершена.',
                    'state' => $state,
                ]);
            }

            return Redirect::route('settings.edit')
                ->with('status', 'cms-update-checked')
                ->with(self::UPDATE_STATE_SESSION_KEY, $state);
        } catch (\Throwable $exception) {
            $state = [
                'latest_version' => null,
                'archive_url' => null,
                'update_available' => false,
                'manifest_error' => $exception->getMessage(),
                'checked_at' => now()->toDateTimeString(),
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $exception->getMessage(),
                    'state' => $state,
                ], 422);
            }

            return Redirect::route('settings.edit')
                ->with(self::UPDATE_STATE_SESSION_KEY, $state)
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
            'site_favicon_source' => '',
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

    private function loadUpdateState(CmsUpdateService $cmsUpdateService, array $storedState = []): array
    {
        return array_merge([
            'current_version' => $cmsUpdateService->currentVersion(),
            'installed_version' => $cmsUpdateService->installedVersion() ?? 'unknown',
            'latest_version' => null,
            'archive_url' => null,
            'update_available' => false,
            'manifest_error' => null,
            'checked_at' => null,
        ], $storedState);
    }

    private function cmsUpdateProgressKey(Request $request): string
    {
        return self::UPDATE_PROGRESS_CACHE_PREFIX.($request->user()?->id ?? 'guest');
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function storeUpdateProgress(string $key, array $payload): void
    {
        Cache::put($key, $payload, now()->addMinutes(30));
    }

    /**
     * @return array<string, mixed>
     */
    private function progressPayloadFromMessage(string $message): array
    {
        $normalized = mb_strtolower($message);

        return match (true) {
            str_contains($normalized, 'скачивание') => [
                'status' => 'running',
                'step' => 'download',
                'message' => $message,
                'percent' => 20,
                'updated_at' => now()->toDateTimeString(),
            ],
            str_contains($normalized, 'распаковка') => [
                'status' => 'running',
                'step' => 'extract',
                'message' => $message,
                'percent' => 45,
                'updated_at' => now()->toDateTimeString(),
            ],
            str_contains($normalized, 'обновление файлов') => [
                'status' => 'running',
                'step' => 'files',
                'message' => $message,
                'percent' => 65,
                'updated_at' => now()->toDateTimeString(),
            ],
            str_contains($normalized, 'миграц') => [
                'status' => 'running',
                'step' => 'migrations',
                'message' => $message,
                'percent' => 82,
                'updated_at' => now()->toDateTimeString(),
            ],
            str_contains($normalized, 'кэш') => [
                'status' => 'running',
                'step' => 'cache',
                'message' => $message,
                'percent' => 94,
                'updated_at' => now()->toDateTimeString(),
            ],
            str_contains($normalized, 'завершено') => [
                'status' => 'completed',
                'step' => 'done',
                'message' => $message,
                'percent' => 100,
                'updated_at' => now()->toDateTimeString(),
            ],
            default => [
                'status' => 'running',
                'step' => 'processing',
                'message' => $message,
                'percent' => 10,
                'updated_at' => now()->toDateTimeString(),
            ],
        };
    }
}
