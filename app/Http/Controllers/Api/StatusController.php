<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class StatusController extends Controller
{
    // Возвращает технический статус сайта для внешних проверок и API-мониторинга.
    public function show(): JsonResponse
    {
        $checks = [
            'app' => $this->checkApplication(),
            'database' => $this->checkDatabase(),
            'site_settings' => $this->checkSiteSettings(),
        ];

        $hasFailure = collect($checks)->contains(
            fn (array $check): bool => $check['status'] === 'fail'
        );

        $status = $hasFailure ? 'degraded' : 'ok';

        return response()->json(
            [
                'status' => $status,
                'checks' => $checks,
                'meta' => [
                    'app_name' => config('app.name'),
                    'environment' => app()->environment(),
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
            $hasFailure ? 503 : 200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    // Проверяет, что приложение и базовая конфигурация доступны.
    private function checkApplication(): array
    {
        return [
            'status' => 'ok',
            'message' => 'Приложение отвечает.',
            'url' => config('app.url'),
        ];
    }

    // Проверяет доступность соединения с базой данных.
    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'status' => 'ok',
                'message' => 'Соединение с базой данных установлено.',
                'connection' => DB::getDefaultConnection(),
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'fail',
                'message' => 'Не удалось подключиться к базе данных.',
                'connection' => DB::getDefaultConnection(),
                'error' => $exception->getMessage(),
            ];
        }
    }

    // Проверяет наличие таблицы настроек и базовых значений сайта.
    private function checkSiteSettings(): array
    {
        try {
            if (! Schema::hasTable('settings')) {
                return [
                    'status' => 'fail',
                    'message' => 'Таблица settings не найдена.',
                ];
            }

            $siteName = Setting::getValue('site_name');
            $siteDescription = Setting::getValue('site_description');

            return [
                'status' => $siteName ? 'ok' : 'warn',
                'message' => $siteName
                    ? 'Настройки сайта доступны.'
                    : 'Настройки сайта доступны, но имя сайта не заполнено.',
                'site_name_filled' => filled($siteName),
                'site_description_filled' => filled($siteDescription),
            ];
        } catch (Throwable $exception) {
            return [
                'status' => 'fail',
                'message' => 'Не удалось проверить настройки сайта.',
                'error' => $exception->getMessage(),
            ];
        }
    }
}
