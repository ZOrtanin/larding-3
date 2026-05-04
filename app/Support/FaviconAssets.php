<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class FaviconAssets
{
    /**
     * @return array<string, string>
     */
    public static function urls(): array
    {
        if (Storage::disk('public')->exists('system/favicon/favicon.ico')) {
            return [
                'ico' => asset('storage/system/favicon/favicon.ico'),
                'svg' => asset('storage/system/favicon/favicon-512.png'),
                'png32' => asset('storage/system/favicon/favicon-32x32.png'),
                'png16' => asset('storage/system/favicon/favicon-16x16.png'),
                'apple' => asset('storage/system/favicon/apple-touch-icon.png'),
                'manifest' => asset('storage/system/favicon/site.webmanifest'),
                'browserconfig' => asset('storage/system/favicon/browserconfig.xml'),
            ];
        }

        return [
            'ico' => asset('favicon.ico'),
            'svg' => asset('favicon.svg'),
            'png32' => asset('favicon-32x32.png'),
            'png16' => asset('favicon-16x16.png'),
            'apple' => asset('apple-touch-icon.png'),
            'manifest' => asset('site.webmanifest'),
            'browserconfig' => asset('browserconfig.xml'),
        ];
    }
}
