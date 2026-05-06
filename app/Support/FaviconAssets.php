<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class FaviconAssets
{
    /**
     * @return array<string, string>
     */
    public static function urls(): array
    {
        return [
            'ico' => self::publicAssetUrl('system/favicon/favicon.ico', 'favicon.ico'),
            'svg' => self::publicAssetUrl('system/favicon/favicon-512.png', 'favicon.svg'),
            'png32' => self::publicAssetUrl('system/favicon/favicon-32x32.png', 'favicon-32x32.png'),
            'png16' => self::publicAssetUrl('system/favicon/favicon-16x16.png', 'favicon-16x16.png'),
            'apple' => self::publicAssetUrl('system/favicon/apple-touch-icon.png', 'apple-touch-icon.png'),
            'manifest' => self::publicAssetUrl('system/favicon/site.webmanifest', 'site.webmanifest'),
            'browserconfig' => self::publicAssetUrl('system/favicon/browserconfig.xml', 'browserconfig.xml'),
        ];
    }

    private static function publicAssetUrl(string $customPath, string $defaultPath): string
    {
        if (File::exists(public_path($customPath))) {
            return self::versionedAssetUrl($customPath);
        }

        return self::versionedAssetUrl($defaultPath);
    }

    private static function versionedAssetUrl(string $path): string
    {
        $absolutePath = public_path($path);
        $url = asset($path);

        if (! File::exists($absolutePath)) {
            return $url;
        }

        $timestamp = File::lastModified($absolutePath);

        return $url.'?v='.$timestamp;
    }
}
