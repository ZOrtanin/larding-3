<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use RuntimeException;

class ImageUploadOptimizer
{
    private const MAX_WIDTH = 2560;

    private const MAX_HEIGHT = 2560;

    private const WEBP_QUALITY = 80;

    /**
     * @return array{contents: string, extension: string, mime_type: string, size: int}|null
     */
    public function optimize(UploadedFile $file): ?array
    {
        if (! \extension_loaded('gd')) {
            return null;
        }

        $mimeType = $file->getMimeType() ?: $file->getClientMimeType();
        $path = $file->getRealPath();

        if (! $path || ! $mimeType) {
            return null;
        }

        $source = $this->createImageResource($path, $mimeType);

        if (! $source) {
            return null;
        }

        try {
            $normalized = $this->normalizeImage($source);

            return match ($mimeType) {
                'image/jpeg', 'image/jpg', 'image/png' => $this->webpPayload($normalized),
                'image/webp' => $this->webpPayload($normalized),
                default => null,
            };
        } finally {
            \imagedestroy($source);

            if (isset($normalized) && $normalized !== $source) {
                \imagedestroy($normalized);
            }
        }
    }

    private function createImageResource(string $path, string $mimeType)
    {
        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => \imagecreatefromjpeg($path),
            'image/png' => \imagecreatefrompng($path),
            'image/webp' => \function_exists('imagecreatefromwebp') ? \imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    private function normalizeImage($source)
    {
        $sourceWidth = \imagesx($source);
        $sourceHeight = \imagesy($source);

        if ($sourceWidth <= self::MAX_WIDTH && $sourceHeight <= self::MAX_HEIGHT) {
            return $source;
        }

        $scale = min(
            self::MAX_WIDTH / $sourceWidth,
            self::MAX_HEIGHT / $sourceHeight,
        );

        $targetWidth = max(1, (int) round($sourceWidth * $scale));
        $targetHeight = max(1, (int) round($sourceHeight * $scale));

        $target = \imagecreatetruecolor($targetWidth, $targetHeight);

        \imagealphablending($target, false);
        \imagesavealpha($target, true);

        $transparent = \imagecolorallocatealpha($target, 0, 0, 0, 127);
        \imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $transparent);

        \imagecopyresampled(
            $target,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight,
        );

        return $target;
    }

    /**
     * @return array{contents: string, extension: string, mime_type: string, size: int}|null
     */
    private function webpPayload($image): ?array
    {
        if (! \function_exists('imagewebp')) {
            return null;
        }

        $contents = $this->captureOutput(fn () => \imagewebp($image, null, self::WEBP_QUALITY));

        return [
            'contents' => $contents,
            'extension' => 'webp',
            'mime_type' => 'image/webp',
            'size' => strlen($contents),
        ];
    }

    private function captureOutput(callable $callback): string
    {
        ob_start();
        $result = $callback();
        $contents = ob_get_clean();

        if ($result === false || ! is_string($contents)) {
            throw new RuntimeException('Не удалось оптимизировать изображение.');
        }

        return $contents;
    }
}
