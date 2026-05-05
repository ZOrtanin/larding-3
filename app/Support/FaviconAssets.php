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
        if (File::exists(public_path('system/favicon/favicon.ico'))) {
            return [
                'ico' => asset('system/favicon/favicon.ico'),
                'svg' => asset('system/favicon/favicon-512.png'),
                'png32' => asset('system/favicon/favicon-32x32.png'),
                'png16' => asset('system/favicon/favicon-16x16.png'),
                'apple' => asset('system/favicon/apple-touch-icon.png'),
                'manifest' => asset('system/favicon/site.webmanifest'),
                'browserconfig' => asset('system/favicon/browserconfig.xml'),
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
