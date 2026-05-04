<?php

return [
    'version' => env('CMS_VERSION', '3.0.6'),

    'update_manifest_url' => env('CMS_UPDATE_MANIFEST_URL', 'http://renewal.larding.ru/version.json'),

    'update_url' => env('CMS_UPDATE_URL', 'http://renewal.larding.ru/larding3.zip'),

    'release_paths' => [
        '.editorconfig',
        '.env.example',
        '.gitattributes',
        '.htaccess',
        'README.md',
        'README.ru.md',
        'app',
        'artisan',
        'bootstrap',
        'composer.json',
        'composer.lock',
        'config',
        'database',
        'index.php',
        'lang',
        'public',
        'resources',
        'routes',
        'vendor',
    ],
];
