<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Visit;
use Carbon\CarbonPeriod;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const LEAD_STATUSES = [
        'new' => 'Новая',
        'in_progress' => 'В работе',
        'done' => 'Завершена',
        'spam' => 'Спам',
    ];

    public function index(): View
    {
        $isManager = request()->user()?->role?->slug === 'manager';
        $chartDates = collect(CarbonPeriod::create(now()->subDays(6)->startOfDay(), now()->startOfDay()));

        $visitsByDay = Visit::query()
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as visits_count')
            ->where('created_at', '>=', now()->startOfDay()->subDays(6))
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->pluck('visits_count', 'visit_date');

        $pageRefreshesByDay = Visit::query()
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as refreshes_count')
            ->where('created_at', '>=', now()->startOfDay()->subDays(6))
            ->where('method', 'GET')
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->pluck('refreshes_count', 'visit_date');

        $leadsByDay = Lead::query()
            ->selectRaw('DATE(created_at) as lead_date, COUNT(*) as leads_count')
            ->where('created_at', '>=', now()->startOfDay()->subDays(6))
            ->groupBy('lead_date')
            ->orderBy('lead_date')
            ->pluck('leads_count', 'lead_date');

        $visitsChart = $chartDates
            ->map(function ($date) use ($visitsByDay) {
                $dateKey = $date->format('Y-m-d');

                return [
                    'label' => $date->translatedFormat('d M'),
                    'value' => (int) ($visitsByDay[$dateKey] ?? 0),
                ];
            });

        $pageRefreshesChart = $chartDates
            ->map(function ($date) use ($pageRefreshesByDay) {
                $dateKey = $date->format('Y-m-d');

                return [
                    'label' => $date->translatedFormat('d M'),
                    'value' => (int) ($pageRefreshesByDay[$dateKey] ?? 0),
                ];
            });

        $leadsChart = $chartDates
            ->map(function ($date) use ($leadsByDay) {
                $dateKey = $date->format('Y-m-d');

                return [
                    'label' => $date->translatedFormat('d M'),
                    'value' => (int) ($leadsByDay[$dateKey] ?? 0),
                ];
            });

        $visitsChartSeries = [
            'visits' => [
                'label' => 'Посещения',
                'labels' => $visitsChart->pluck('label')->values(),
                'values' => $visitsChart->pluck('value')->values(),
            ],
            'refreshes' => [
                'label' => 'Обновления страниц',
                'labels' => $pageRefreshesChart->pluck('label')->values(),
                'values' => $pageRefreshesChart->pluck('value')->values(),
            ],
            'leads' => [
                'label' => 'Лиды',
                'labels' => $leadsChart->pluck('label')->values(),
                'values' => $leadsChart->pluck('value')->values(),
            ],
        ];

        $latestLeadsQuery = Lead::query();
        $calendarLeadsQuery = Lead::query();

        if ($isManager) {
            $latestLeadsQuery->where('status', '!=', 'done');
            $calendarLeadsQuery->where('status', '!=', 'done');
        }

        $calendarLeads = $calendarLeadsQuery
            ->latest('created_at')
            ->get([
                'id',
                'status',
                'created_at',
            ]);

        $leadCalendarMap = $calendarLeads
            ->groupBy(function (Lead $lead): string {
                return $lead->created_at->format('Y-m-d');
            })
            ->map(function ($leads): array {
                return [
                    'count' => $leads->count(),
                    'items' => $leads->map(function (Lead $lead): array {
                        return [
                            'id' => $lead->id,
                            'status' => $lead->status,
                            'time' => $lead->created_at->format('H:i'),
                        ];
                    })->values()->all(),
                ];
            });

        return view('dashboard', [
            'uniqueVisitors' => Visit::query()
                ->where('status_code', '<', 400)
                ->distinct('visitor_id')
                ->count('visitor_id'),
            'pageRefreshes' => Visit::query()->where('method', 'GET')->count(),
            'uniqueIps' => Visit::query()->whereNotNull('ip')->distinct('ip')->count('ip'),
            'leadsCount' => Lead::query()->count(),
            'visitsChart' => $visitsChart,
            'pageRefreshesChart' => $pageRefreshesChart,
            'leadsChart' => $leadsChart,
            'visitsChartSeries' => $visitsChartSeries,
            'leadCalendarMap' => $leadCalendarMap,
            'leadStatuses' => self::LEAD_STATUSES,
            'latestLeads' => $latestLeadsQuery
                ->latest()
                ->limit(3)
                ->get([
                    'id',
                    'name',
                    'content',
                    'status',
                    'comments',
                    'created_at',
                    'updated_at',
                ]),
            'latestIps' => Visit::query()
                ->whereNotNull('ip')
                ->latest()
                ->limit(7)
                ->get(['id', 'ip', 'created_at']),
        ]);
    }
}
