<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardAnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(DashboardAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function data(Request $request)
    {
        $range = $request->query('range', '30'); // default 30 days
        $end = Carbon::now();
        
        switch ($range) {
            case 'today':
                $start = Carbon::today();
                break;
            case '7':
                $start = Carbon::now()->subDays(7);
                break;
            case '90':
                $start = Carbon::now()->subDays(90);
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                break;
            case 'custom':
                $start = $request->query('start') ? Carbon::parse($request->query('start')) : Carbon::now()->subDays(30);
                $end = $request->query('end') ? Carbon::parse($request->query('end')) : Carbon::now();
                break;
            case '30':
            default:
                $start = Carbon::now()->subDays(30);
                break;
        }

        return response()->json([
            'summary' => $this->analyticsService->getSummary($start, $end),
            'revenue_trend' => $this->analyticsService->getRevenueTrend($start, $end),
            'platform_growth' => $this->analyticsService->getPlatformGrowth($start, $end),
            'category_distribution' => $this->analyticsService->getCategoryDistribution($start, $end),
            'top_organizers' => $this->analyticsService->getTopOrganizers($start, $end),
            'top_events' => $this->analyticsService->getTopEvents($start, $end),
        ]);
    }
}
