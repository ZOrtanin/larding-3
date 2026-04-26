<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    private const VISITOR_ID_LENGTH = 32;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTrack($request, $response)) {
            return $response;
        }

        $visitorId = $this->resolveVisitorId($request);

        Visit::query()->create([
            'visitor_id' => $visitorId,
            'ip' => $request->ip(),
            'method' => $request->method(),
            'status_code' => $response->getStatusCode(),
            'user_agent' => $request->userAgent(),
            'browser' => $this->detectBrowser($request->userAgent()),
            'platform' => $this->detectPlatform($request->userAgent()),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'url' => $request->fullUrl(),
            'referer' => $request->headers->get('referer'),
            'utm_source' => $request->query('utm_source'),
            'utm_medium' => $request->query('utm_medium'),
            'utm_campaign' => $request->query('utm_campaign'),
            'utm_content' => $request->query('utm_content'),
            'utm_term' => $request->query('utm_term'),
            'is_mobile' => $this->isMobile($request->userAgent()),
        ]);

        if ($request->cookie('visitor_id') !== $visitorId) {
            $response->headers->setCookie(cookie(
                'visitor_id',
                $visitorId,
                60 * 24 * 365,
                '/',
                null,
                false,
                false,
                false,
                'lax'
            ));
        }

        return $response;
    }

    private function resolveVisitorId(Request $request): string
    {
        $sessionVisitorId = $this->resolveVisitorIdFromSession($request);

        if ($sessionVisitorId !== null) {
            return $sessionVisitorId;
        }

        $cookieVisitorId = trim((string) $request->cookie('visitor_id', ''));

        if ($cookieVisitorId !== '' && mb_strlen($cookieVisitorId) <= self::VISITOR_ID_LENGTH) {
            return $cookieVisitorId;
        }

        return Str::lower(Str::random(self::VISITOR_ID_LENGTH));
    }

    private function resolveVisitorIdFromSession(Request $request): ?string
    {
        if (! $request->hasSession()) {
            return null;
        }

        $sessionId = trim((string) $request->session()->getId());

        if ($sessionId === '') {
            return null;
        }

        return md5($sessionId);
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (
            (string) env('DB_CONNECTION', '') === ''
            || ! file_exists(base_path('.env'))
            || $request->routeIs('install.*')
            || $request->is('install')
            || $request->is('install/*')
            || ! filter_var(env('APP_INSTALLED', false), FILTER_VALIDATE_BOOL)
        ) {
            return false;
        }

        if (
            $request->is('up')
            || $request->is('dashboard')
            || $request->is('lids')
            || $request->is('statistics')
            || $request->is('settings')
            || $request->is('profile')
            || $request->is('files')
            || $request->is('block-templates')
            || $request->is('block-templates/*')
            || $request->is('blocks/*')
            || $request->is('block/*')
            || $request->is('notifications/*')
        ) {
            return false;
        }

        return true;
    }

    private function isMobile(?string $userAgent): bool
    {
        if (! $userAgent) {
            return false;
        }

        return (bool) preg_match('/android|iphone|ipad|ipod|mobile|opera mini|iemobile/i', $userAgent);
    }

    private function detectBrowser(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'OPR/'), str_contains($userAgent, 'Opera') => 'Opera',
            str_contains($userAgent, 'Chrome/') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') && ! str_contains($userAgent, 'Chrome/') => 'Safari',
            default => 'Unknown',
        };
    }

    private function detectPlatform(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown';
        }

        $normalized = strtolower($userAgent);

        return match (true) {
            str_contains($normalized, 'windows') => 'Windows',
            str_contains($normalized, 'iphone'), str_contains($normalized, 'ipad'), str_contains($normalized, 'ios') => 'iOS',
            str_contains($normalized, 'android') => 'Android',
            str_contains($normalized, 'mac os x'), str_contains($normalized, 'macintosh') => 'macOS',
            str_contains($normalized, 'linux') => 'Linux',
            default => 'Unknown',
        };
    }

    private function detectDeviceType(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'desktop';
        }

        $normalized = strtolower($userAgent);

        return match (true) {
            str_contains($normalized, 'ipad'), str_contains($normalized, 'tablet') => 'tablet',
            $this->isMobile($userAgent) => 'mobile',
            default => 'desktop',
        };
    }
}
