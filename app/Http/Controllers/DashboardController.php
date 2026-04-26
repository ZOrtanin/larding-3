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

        $visitsByDay = Visit::query()
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as visits_count')
            ->where('created_at', '>=', now()->startOfDay()->subDays(6))
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->pluck('visits_count', 'visit_date');

        $visitsChart = collect(CarbonPeriod::create(now()->subDays(6)->startOfDay(), now()->startOfDay()))
            ->map(function ($date) use ($visitsByDay) {
                $dateKey = $date->format('Y-m-d');

                return [
                    'label' => $date->translatedFormat('d M'),
                    'value' => (int) ($visitsByDay[$dateKey] ?? 0),
                ];
            });

        $latestLeadsQuery = Lead::query();

        if ($isManager) {
            $latestLeadsQuery->where('status', '!=', 'done');
        }

        return view('dashboard', [
            'uniqueVisitors' => Visit::query()->distinct('visitor_id')->count('visitor_id'),
            'pageRefreshes' => Visit::query()->where('method', 'GET')->count(),
            'uniqueIps' => Visit::query()->whereNotNull('ip')->distinct('ip')->count('ip'),
            'leadsCount' => Lead::query()->count(),
            'visitsChart' => $visitsChart,
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
                ->limit(3)
                ->get(['id', 'ip', 'created_at']),
        ]);
    }
}
