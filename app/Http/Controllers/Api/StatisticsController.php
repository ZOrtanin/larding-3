<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    // Возвращает сводную статистику панели в формате JSON.
    public function show(): JsonResponse
    {
        $statistics = [
            'unique_visitors' => Visit::query()
                ->where('status_code', '<', 400)
                ->distinct('visitor_id')
                ->count('visitor_id'),
            'page_refreshes' => Visit::query()->where('method', 'GET')->count(),
            'unique_ips' => Visit::query()->whereNotNull('ip')->distinct('ip')->count('ip'),
            'leads_count' => Lead::query()->count(),
        ];

        return response()->json(
            [
                'status' => 'ok',
                'data' => $statistics,
                'meta' => [
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
