<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class FrontAssetService
{
    private const GENERATED_DIRECTORY = 'system/generated';

    private const FRONT_CSS_FILENAME = 'front-custom.css';

    public function publishFrontCss(string $css): ?string
    {
        $css = trim($css);
        $publicDirectory = public_path(self::GENERATED_DIRECTORY);
        $publicFilePath = $publicDirectory.'/'.self::FRONT_CSS_FILENAME;

        if ($css === '') {
            if (File::exists($publicFilePath)) {
                File::delete($publicFilePath);
            }

            return null;
        }

        File::ensureDirectoryExists($publicDirectory);

        $normalizedCss = $css.PHP_EOL;

        if (! File::exists($publicFilePath) || File::get($publicFilePath) !== $normalizedCss) {
            File::put($publicFilePath, $normalizedCss);
        }

        $version = File::lastModified($publicFilePath);

        return asset(self::GENERATED_DIRECTORY.'/'.self::FRONT_CSS_FILENAME).'?v='.$version;
    }
}
