<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use ZipArchive;

class CmsUpdateService
{
    public const VERSION_KEY = 'app.version';

    public function __construct(
        private readonly InstallationState $installationState,
    ) {
    }

    public function currentVersion(): string
    {
        return (string) config('cms.version', '0.0.0');
    }

    public function installedVersion(): ?string
    {
        return Setting::getValue(self::VERSION_KEY);
    }

    public function updateAvailable(): bool
    {
        return version_compare(
            $this->currentVersion(),
            $this->installedVersion() ?? '0.0.0',
            '>'
        );
    }

    /**
     * @return array{version: string, url: string}|null
     */
    public function latestRelease(): ?array
    {
        $manifestUrl = trim((string) config('cms.update_manifest_url', ''));

        if ($manifestUrl === '') {
            return null;
        }

        $payload = Http::timeout(30)->retry(2, 1000)->get($manifestUrl)->throw()->json();

        if (! is_array($payload)) {
            throw new RuntimeException('Некорректный ответ от сервера обновлений.');
        }

        $version = trim((string) ($payload['version'] ?? ''));
        $url = trim((string) ($payload['url'] ?? config('cms.update_url', '')));

        if ($version === '' || $url === '') {
            throw new RuntimeException('Сервер обновлений вернул неполные данные.');
        }

        return [
            'version' => $version,
            'url' => $url,
        ];
    }

    public function remoteUpdateAvailable(): bool
    {
        $release = $this->latestRelease();

        if ($release === null) {
            return false;
        }

        return version_compare(
            $release['version'],
            $this->installedVersion() ?? '0.0.0',
            '>'
        );
    }

    public function recordInstalledVersion(?string $version = null): void
    {
        Setting::setValue(self::VERSION_KEY, $version ?? $this->currentVersion());
    }

    public function updateFromArchiveUrl(?callable $progress = null, ?string $archiveUrl = null, ?string $targetVersion = null): void
    {
        if (! $this->installationState->isInstalled()) {
            throw new RuntimeException('Обновление доступно только для уже установленной CMS.');
        }

        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Расширение ZipArchive недоступно на сервере.');
        }

        $url = trim((string) ($archiveUrl ?? config('cms.update_url', '')));

        if ($url === '') {
            throw new RuntimeException('Не задан URL архива обновления.');
        }

        $workingDirectory = storage_path('app/updates/'.date('Ymd_His'));
        $archivePath = $workingDirectory.'/release.zip';
        $extractPath = $workingDirectory.'/extracted';

        $this->ensureDirectory($workingDirectory);
        $this->ensureDirectory($extractPath);

        try {
            $this->report($progress, 'Скачивание архива обновления...');
            $response = Http::timeout(120)->retry(2, 1000)->get($url)->throw();
            $archiveContents = $response->body();

            if ($archiveContents === '') {
                throw new RuntimeException('Сервер обновлений вернул пустой архив.');
            }

            if (! $this->looksLikeZipArchive($archiveContents)) {
                $preview = substr($archiveContents, 0, 120);

                throw new RuntimeException(sprintf(
                    'Сервер обновлений вернул не ZIP-архив. Content-Type: %s. Начало ответа: %s',
                    $response->header('Content-Type', 'unknown'),
                    $this->sanitizePreview($preview),
                ));
            }

            if (file_put_contents($archivePath, $archiveContents) === false) {
                throw new RuntimeException('Не удалось сохранить архив обновления во временную директорию.');
            }

            $this->report($progress, 'Распаковка архива...');
            $packageRoot = $this->extractArchive($archivePath, $extractPath);

            $this->report($progress, 'Обновление файлов CMS...');
            $this->applyReleasePackage($packageRoot, base_path());

            $this->report($progress, 'Применение миграций...');
            Artisan::call('migrate', ['--force' => true]);

            $this->report($progress, 'Очистка кэшей...');
            Artisan::call('optimize:clear');

            $this->recordInstalledVersion($targetVersion);
            $this->report($progress, 'Обновление завершено.');
        } finally {
            $this->deletePath($workingDirectory);
        }
    }

    private function extractArchive(string $archivePath, string $extractPath): string
    {
        $zip = new ZipArchive();
        $result = $zip->open($archivePath);

        if ($result !== true) {
            throw new RuntimeException(sprintf(
                'Не удалось открыть архив обновления. Код ZipArchive: %s, файл: %s, размер: %s байт.',
                (string) $result,
                $archivePath,
                file_exists($archivePath) ? (string) filesize($archivePath) : 'missing',
            ));
        }

        $zip->extractTo($extractPath);
        $zip->close();

        return $this->detectPackageRoot($extractPath);
    }

    private function detectPackageRoot(string $extractPath): string
    {
        if ($this->looksLikePackageRoot($extractPath)) {
            return $extractPath;
        }

        $entries = array_values(array_filter(
            scandir($extractPath) ?: [],
            fn (string $entry): bool => ! in_array($entry, ['.', '..'], true)
        ));

        foreach ($entries as $entry) {
            $candidate = $extractPath.'/'.$entry;

            if (is_dir($candidate) && $this->looksLikePackageRoot($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException('Не удалось определить корень релизного пакета в архиве.');
    }

    private function looksLikePackageRoot(string $path): bool
    {
        return is_file($path.'/composer.json') && is_file($path.'/artisan');
    }

    private function applyReleasePackage(string $sourceRoot, string $targetRoot): void
    {
        $paths = config('cms.release_paths', []);

        foreach ($paths as $relativePath) {
            $sourcePath = $sourceRoot.'/'.$relativePath;
            $targetPath = $targetRoot.'/'.$relativePath;

            if (! file_exists($sourcePath)) {
                continue;
            }

            if (is_dir($sourcePath)) {
                $exclude = $relativePath === 'public' ? ['storage'] : [];
                $this->mirrorDirectory($sourcePath, $targetPath, $exclude);
                continue;
            }

            $this->ensureDirectory(dirname($targetPath));
            copy($sourcePath, $targetPath);
        }
    }

    private function mirrorDirectory(string $source, string $target, array $excludedTopLevelNames = []): void
    {
        $this->ensureDirectory($target);

        $sourceEntries = $this->directoryEntries($source);
        $targetEntries = $this->directoryEntries($target);

        foreach ($targetEntries as $name => $targetEntryPath) {
            if (in_array($name, $excludedTopLevelNames, true)) {
                continue;
            }

            if (! array_key_exists($name, $sourceEntries)) {
                $this->deletePath($targetEntryPath);
            }
        }

        foreach ($sourceEntries as $name => $sourceEntryPath) {
            if (in_array($name, $excludedTopLevelNames, true)) {
                continue;
            }

            $targetEntryPath = $target.'/'.$name;

            if (is_dir($sourceEntryPath)) {
                $this->mirrorDirectory($sourceEntryPath, $targetEntryPath);
                continue;
            }

            $this->ensureDirectory(dirname($targetEntryPath));
            copy($sourceEntryPath, $targetEntryPath);
        }
    }

    /**
     * @return array<string, string>
     */
    private function directoryEntries(string $path): array
    {
        $entries = [];

        foreach (scandir($path) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $entries[$entry] = $path.'/'.$entry;
        }

        return $entries;
    }

    private function ensureDirectory(string $path): void
    {
        if (is_dir($path)) {
            return;
        }

        mkdir($path, 0775, true);
    }

    private function deletePath(string $path): void
    {
        if (! file_exists($path) && ! is_link($path)) {
            return;
        }

        if (is_file($path) || is_link($path)) {
            @unlink($path);
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        /** @var SplFileInfo $item */
        foreach ($iterator as $item) {
            $pathname = $item->getPathname();

            if ($item->isDir()) {
                @rmdir($pathname);
                continue;
            }

            @unlink($pathname);
        }

        @rmdir($path);
    }

    private function report(?callable $progress, string $message): void
    {
        if ($progress !== null) {
            $progress($message);
        }
    }

    private function looksLikeZipArchive(string $contents): bool
    {
        return str_starts_with($contents, "PK\x03\x04")
            || str_starts_with($contents, "PK\x05\x06")
            || str_starts_with($contents, "PK\x07\x08");
    }

    private function sanitizePreview(string $preview): string
    {
        $preview = preg_replace('/[[:cntrl:]]+/', ' ', $preview) ?? '';
        $preview = trim($preview);

        return $preview === '' ? '[binary or empty content]' : $preview;
    }
}
