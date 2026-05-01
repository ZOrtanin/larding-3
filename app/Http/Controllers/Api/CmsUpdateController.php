<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CmsUpdateService;
use Illuminate\Http\JsonResponse;

class CmsUpdateController extends Controller
{
    // Возвращает состояние обновлений CMS для внешних клиентов и админ-приложений.
    public function show(CmsUpdateService $cmsUpdateService): JsonResponse
    {
        try {
            $latestRelease = $cmsUpdateService->latestRelease();

            return response()->json(
                [
                    'status' => 'ok',
                    'data' => [
                        'current_version' => $cmsUpdateService->currentVersion(),
                        'installed_version' => $cmsUpdateService->installedVersion() ?? 'unknown',
                        'latest_version' => $latestRelease['version'] ?? null,
                        'archive_url' => $latestRelease['url'] ?? null,
                        'update_available' => $latestRelease !== null
                            ? $cmsUpdateService->remoteUpdateAvailable()
                            : false,
                    ],
                    'meta' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ],
                200,
                ['Content-Type' => 'application/json; charset=UTF-8'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        } catch (\Throwable $exception) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Не удалось проверить обновления CMS.',
                    'error' => $exception->getMessage(),
                    'meta' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ],
                422,
                ['Content-Type' => 'application/json; charset=UTF-8'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }
    }

    // Запускает обновление CMS через API без переустановки системы.
    public function update(CmsUpdateService $cmsUpdateService): JsonResponse
    {
        try {
            $latestRelease = $cmsUpdateService->latestRelease();

            if ($latestRelease === null || ! $cmsUpdateService->remoteUpdateAvailable()) {
                return response()->json(
                    [
                        'status' => 'ok',
                        'message' => 'Новых обновлений не найдено.',
                        'data' => [
                            'current_version' => $cmsUpdateService->currentVersion(),
                            'installed_version' => $cmsUpdateService->installedVersion() ?? 'unknown',
                            'latest_version' => $latestRelease['version'] ?? null,
                            'archive_url' => $latestRelease['url'] ?? null,
                            'update_available' => false,
                        ],
                        'meta' => [
                            'timestamp' => now()->toIso8601String(),
                        ],
                    ],
                    200,
                    ['Content-Type' => 'application/json; charset=UTF-8'],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                );
            }

            $cmsUpdateService->updateFromArchiveUrl(
                progress: null,
                archiveUrl: $latestRelease['url'],
                targetVersion: $latestRelease['version'],
            );

            return response()->json(
                [
                    'status' => 'ok',
                    'message' => 'CMS успешно обновлена.',
                    'data' => [
                        'current_version' => $cmsUpdateService->currentVersion(),
                        'installed_version' => $cmsUpdateService->installedVersion() ?? $latestRelease['version'],
                        'latest_version' => $latestRelease['version'],
                        'archive_url' => $latestRelease['url'],
                        'update_available' => false,
                    ],
                    'meta' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ],
                200,
                ['Content-Type' => 'application/json; charset=UTF-8'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        } catch (\Throwable $exception) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Не удалось обновить CMS.',
                    'error' => $exception->getMessage(),
                    'meta' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ],
                422,
                ['Content-Type' => 'application/json; charset=UTF-8'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }
    }
}
