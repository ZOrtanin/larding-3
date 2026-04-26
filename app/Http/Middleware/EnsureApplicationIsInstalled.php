<?php

namespace App\Http\Middleware;

use App\Services\InstallationState;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApplicationIsInstalled
{
    public function __construct(
        private readonly InstallationState $installationState,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->installationState->isInstalled()) {
            return $next($request);
        }

        if ($request->routeIs('install.*')) {
            return $next($request);
        }

        return redirect()->route('install.index');
    }
}
