<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasAssignedRole
{
    /**
     * Проверяет, что у пользователя назначена роль.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->role_id) {
            abort(403);
        }

        return $next($request);
    }
}
