<?php

use App\Http\Middleware\AuthenticateWithApiToken;
use App\Http\Middleware\EnsureApplicationIsInstalled;
use App\Http\Middleware\EnsureUserHasAssignedRole;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\TrackVisit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

if (! function_exists('ensureAppKeyExists')) {
    function ensureEnvironmentFileExists(string $envPath, string $examplePath): void
    {
        if (file_exists($envPath) || ! file_exists($examplePath)) {
            return;
        }

        copy($examplePath, $envPath);
    }

    function ensureAppKeyExists(string $envPath): void
    {
        if (! file_exists($envPath) || ! is_writable($envPath)) {
            return;
        }

        $contents = (string) file_get_contents($envPath);

        if (preg_match('/^APP_KEY=.+/m', $contents) === 1) {
            return;
        }

        $key = 'base64:'.base64_encode(random_bytes(32));

        if (preg_match('/^APP_KEY=.*$/m', $contents) === 1) {
            $contents = (string) preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$key, $contents, 1);
        } else {
            $contents = rtrim($contents).PHP_EOL.'APP_KEY='.$key.PHP_EOL;
        }

        file_put_contents($envPath, $contents);
        putenv('APP_KEY='.$key);
        $_ENV['APP_KEY'] = $key;
        $_SERVER['APP_KEY'] = $key;
    }
}

ensureEnvironmentFileExists(
    dirname(__DIR__).'/.env',
    dirname(__DIR__).'/.env.example',
);

ensureAppKeyExists(dirname(__DIR__).'/.env');

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.token' => AuthenticateWithApiToken::class,
            'installed' => EnsureApplicationIsInstalled::class,
            'role.assigned' => EnsureUserHasAssignedRole::class,
            'role' => EnsureUserHasRole::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'leads',
        ]);

        $middleware->append(TrackVisit::class);
        $middleware->web(append: [
            EnsureApplicationIsInstalled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
