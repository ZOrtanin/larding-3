<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithApiToken
{
    // Проверяет Bearer-токен пользователя для доступа к защищённым API-методам.
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) $request->bearerToken();

        if ($token === '') {
            return $this->unauthorizedResponse('API токен не передан.');
        }

        $user = User::query()
            ->where('api_token', $token)
            ->first();

        if (! $user) {
            return $this->unauthorizedResponse('API токен недействителен.');
        }

        $request->setUserResolver(static fn (): User => $user);

        return $next($request);
    }

    // Возвращает унифицированный JSON-ответ для неавторизованных запросов.
    private function unauthorizedResponse(string $message): JsonResponse
    {
        return response()->json(
            [
                'message' => $message,
            ],
            401,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
