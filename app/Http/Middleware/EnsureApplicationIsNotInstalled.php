<?php

namespace App\Http\Middleware;

use App\Services\InstallationState;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApplicationIsNotInstalled
{
    public function __construct(
        private readonly InstallationState $installationState,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->installationState->isInstallerEnabled()) {
            abort(404);
        }

        if (! $this->installationState->isInstalled()) {
            return $next($request);
        }

        if ($request->user() !== null) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
}
