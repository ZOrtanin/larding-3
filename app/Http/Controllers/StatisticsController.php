<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\UrlWindow;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(): View
    {
        $stats = [
            'unique_visitors' => Visit::query()->distinct('visitor_id')->count('visitor_id'),
            'page_refreshes' => Visit::query()->where('method', 'GET')->count(),
            'unique_ips' => Visit::query()->whereNotNull('ip')->distinct('ip')->count('ip'),
            'error_responses' => Visit::query()->where('status_code', '>=', 400)->count(),
        ];

        $visits = Visit::query()
            ->latest()
            ->paginate(40, [
                'id',
                'visitor_id',
                'ip',
                'method',
                'status_code',
                'user_agent',
                'browser',
                'platform',
                'device_type',
                'url',
                'referer',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_content',
                'utm_term',
                'is_mobile',
                'created_at',
                'updated_at',
            ])->withQueryString();

        $window = UrlWindow::make($visits);
        $paginationItems = array_filter([
            $window['first'] ?? null,
            $window['slider'] ?? null,
            $window['last'] ?? null,
        ]);

        return view('statistics', [
            'stats' => $stats,
            'visits' => $visits,
            'paginationItems' => $paginationItems,
        ]);
    }

    public function destroy(): RedirectResponse
    {
        Visit::query()->delete();

        return redirect()
            ->route('statistics')
            ->with('status', 'statistics-reset');
    }
}
