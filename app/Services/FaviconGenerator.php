<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FaviconGenerator
{
    private const OUTPUT_DIRECTORY = 'system/favicon';
    private const PUBLIC_OUTPUT_DIRECTORY = 'system/favicon';

    /**
     * @return array<string, string>
     */
    public function generateFromUpload(UploadedFile $file): array
    {
        if (! \extension_loaded('gd')) {
            throw new RuntimeException('Расширение GD недоступно в PHP. Без него favicon не может быть сгенерирован.');
        }

        $source = $this->createImageFromUpload($file);

        if (! $source) {
            throw new RuntimeException('Не удалось обработать изображение для favicon.');
        }

        $sourcePath = $file->storeAs(
            self::OUTPUT_DIRECTORY,
            'source.'.$file->getClientOriginalExtension(),
            'public',
        );

        $outputs = [
            'favicon-16x16.png' => 16,
            'favicon-32x32.png' => 32,
            'apple-touch-icon.png' => 180,
            'favicon-192.png' => 192,
            'favicon-512.png' => 512,
        ];

        $pngBinaries = [];

        foreach ($outputs as $filename => $size) {
            $icon = $this->makeSquareIcon($source, $size);
            $pngBinaries[$filename] = $this->pngBinary($icon);
            \imagedestroy($icon);
        }

        foreach ($pngBinaries as $filename => $binary) {
            $this->putPublicAsset($filename, $binary);
        }

        $this->putPublicAsset(
            'favicon.ico',
            $this->buildIco([
                16 => $pngBinaries['favicon-16x16.png'],
                32 => $pngBinaries['favicon-32x32.png'],
            ]),
        );

        $this->putPublicAsset(
            'site.webmanifest',
            $this->buildManifest(),
        );

        $this->putPublicAsset(
            'browserconfig.xml',
            $this->buildBrowserConfig(),
        );

        \imagedestroy($source);

        return [
            'source_path' => $sourcePath,
            'base_path' => self::OUTPUT_DIRECTORY,
        ];
    }

    private function createImageFromUpload(UploadedFile $file)
    {
        $mimeType = $file->getMimeType() ?: $file->getClientMimeType();
        $path = $file->getRealPath();

        if (! $path || ! $mimeType) {
            return false;
        }

        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => \imagecreatefromjpeg($path),
            'image/png' => \imagecreatefrompng($path),
            'image/gif' => \imagecreatefromgif($path),
            'image/webp' => \function_exists('imagecreatefromwebp') ? \imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    private function makeSquareIcon($source, int $size)
    {
        $sourceWidth = \imagesx($source);
        $sourceHeight = \imagesy($source);
        $cropSize = min($sourceWidth, $sourceHeight);
        $sourceX = (int) floor(($sourceWidth - $cropSize) / 2);
        $sourceY = (int) floor(($sourceHeight - $cropSize) / 2);

        $target = \imagecreatetruecolor($size, $size);

        \imagealphablending($target, false);
        \imagesavealpha($target, true);

        $transparent = \imagecolorallocatealpha($target, 0, 0, 0, 127);
        \imagefilledrectangle($target, 0, 0, $size, $size, $transparent);

        \imagecopyresampled(
            $target,
            $source,
            0,
            0,
            $sourceX,
            $sourceY,
            $size,
            $size,
            $cropSize,
            $cropSize,
        );

        return $target;
    }

    private function pngBinary($image): string
    {
        ob_start();
        \imagepng($image);
        $binary = ob_get_clean();

        if (! is_string($binary)) {
            throw new RuntimeException('Не удалось собрать PNG для favicon.');
        }

        return $binary;
    }

    /**
     * @param array<int, string> $pngVariants
     */
    private function buildIco(array $pngVariants): string
    {
        $iconCount = count($pngVariants);
        $header = pack('vvv', 0, 1, $iconCount);
        $directoryEntries = '';
        $imageData = '';
        $offset = 6 + ($iconCount * 16);

        foreach ($pngVariants as $size => $binary) {
            $directoryEntries .= pack(
                'CCCCvvVV',
                $size >= 256 ? 0 : $size,
                $size >= 256 ? 0 : $size,
                0,
                0,
                1,
                32,
                strlen($binary),
                $offset,
            );

            $imageData .= $binary;
            $offset += strlen($binary);
        }

        return $header.$directoryEntries.$imageData;
    }

    private function buildManifest(): string
    {
        return json_encode([
            'name' => 'Larding CMS',
            'short_name' => 'Larding',
            'icons' => [
                [
                    'src' => '/system/favicon/favicon-192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                ],
                [
                    'src' => '/system/favicon/favicon-512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ],
            ],
            'theme_color' => '#1a120d',
            'background_color' => '#1a120d',
            'display' => 'standalone',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '{}';
    }

    private function buildBrowserConfig(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<browserconfig>
  <msapplication>
    <tile>
      <square150x150logo src="/system/favicon/favicon-192.png"/>
      <TileColor>#1a120d</TileColor>
    </tile>
  </msapplication>
</browserconfig>
XML;
    }

    private function putPublicAsset(string $filename, string $contents): void
    {
        $directory = public_path(self::PUBLIC_OUTPUT_DIRECTORY);

        if (! File::exists($directory)) {
            File::ensureDirectoryExists($directory);
        }

        File::put($directory.'/'.$filename, $contents);
    }
}
