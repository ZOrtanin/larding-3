<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('lead-form', function (Request $request) {
            $visitorId = (string) $request->cookie('visitor_id', '');
            $ip = (string) $request->ip();
            $fingerprint = $visitorId !== '' ? 'visitor:'.$visitorId : 'ip:'.$ip;

            return Limit::perMinute(5)
                ->by('lead-form:'.$fingerprint)
                ->response(function () {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Слишком много заявок. Попробуйте снова через минуту.',
                    ], 429);
                });
        });
    }
}
