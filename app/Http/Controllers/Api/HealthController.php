<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    // Возвращает минимальный heartbeat-ответ API.
    public function show(): JsonResponse
    {
        return response()->json(
            [
                'status' => 'ok',
                'time' => now()->toIso8601String(),
            ],
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
