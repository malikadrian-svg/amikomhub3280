<?php

namespace App\Services\Admin;

use App\Models\Event;
use App\Models\Order;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsService
{
    /**
     * Get summary metrics for the given date range.
     */
    public function getSummary(Carbon $start, Carbon $end): array
    {
        $cacheKey = "admin.analytics.summary.{$start->format('Ymd')}.{$end->format('Ymd')}";
        
        return Cache::remember($cacheKey, 600, function () use ($start, $end) {
            $orders = Order::whereIn('status', ['paid', 'completed'])
                ->whereBetween('created_at', [$start, $end]);

            $gmv = (clone $orders)->sum('total_amount');
            $platformFee = (clone $orders)->sum('platform_fee');
            
            $ticketsSold = (clone $orders)->withSum('items', 'quantity')->get()->sum('items_sum_quantity');

            $newUsers = User::whereBetween('created_at', [$start, $end])->count();
            $newOrgs = Organization::where('status', 'approved')
                ->whereBetween('approved_at', [$start, $end])
                ->count();

            return [
                'gmv' => (int) $gmv,
                'platform_fee' => (int) $platformFee,
                'tickets_sold' => (int) $ticketsSold,
                'new_users' => $newUsers,
                'new_orgs' => $newOrgs,
            ];
        });
    }

    /**
     * Get revenue trend data for line/area chart.
     */
    public function getRevenueTrend(Carbon $start, Carbon $end): array
    {
        $cacheKey = "admin.analytics.revenue_trend.{$start->format('Ymd')}.{$end->format('Ymd')}";
        
        return Cache::remember($cacheKey, 600, function () use ($start, $end) {
            $trend = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as total_gmv'),
                    DB::raw('SUM(platform_fee) as total_platform_fee')
                )
                ->whereIn('status', ['paid', 'completed'])
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $dates = [];
            $gmv = [];
            $platformFee = [];
            
            $period = \Carbon\CarbonPeriod::create($start, $end);
            $trendKeyed = $trend->keyBy('date');

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $dates[] = $date->format('d M');
                
                if (isset($trendKeyed[$dateStr])) {
                    $gmv[] = (int) $trendKeyed[$dateStr]->total_gmv;
                    $platformFee[] = (int) $trendKeyed[$dateStr]->total_platform_fee;
                } else {
                    $gmv[] = 0;
                    $platformFee[] = 0;
                }
            }

            return [
                'labels' => $dates,
                'series' => [
                    ['name' => 'GMV', 'data' => $gmv],
                    ['name' => 'Platform Fee', 'data' => $platformFee],
                ]
            ];
        });
    }

    /**
     * Get platform growth (Users vs Organizations).
     */
    public function getPlatformGrowth(Carbon $start, Carbon $end): array
    {
        $cacheKey = "admin.analytics.growth.{$start->format('Ymd')}.{$end->format('Ymd')}";
        
        return Cache::remember($cacheKey, 600, function () use ($start, $end) {
            $users = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('date')
                ->pluck('total', 'date');

            $orgs = Organization::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('date')
                ->pluck('total', 'date');

            $dates = [];
            $userData = [];
            $orgData = [];
            
            $period = \Carbon\CarbonPeriod::create($start, $end);

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $dates[] = $date->format('d M');
                $userData[] = $users[$dateStr] ?? 0;
                $orgData[] = $orgs[$dateStr] ?? 0;
            }

            return [
                'labels' => $dates,
                'series' => [
                    ['name' => 'Users Registered', 'data' => $userData],
                    ['name' => 'Organizations Registered', 'data' => $orgData],
                ]
            ];
        });
    }

    /**
     * Get category distribution for donut chart.
     */
    public function getCategoryDistribution(Carbon $start, Carbon $end): array
    {
        $cacheKey = "admin.analytics.category.{$start->format('Ymd')}.{$end->format('Ymd')}";
        
        return Cache::remember($cacheKey, 600, function () use ($start, $end) {
            $distribution = Order::join('events', 'orders.event_id', '=', 'events.id')
                ->join('categories', 'events.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('COUNT(orders.id) as total_orders'))
                ->whereIn('orders.status', ['paid', 'completed'])
                ->whereBetween('orders.created_at', [$start, $end])
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total_orders')
                ->take(5)
                ->get();

            return [
                'labels' => $distribution->pluck('name')->toArray(),
                'series' => $distribution->pluck('total_orders')->toArray(),
            ];
        });
    }

    /**
     * Get top organizers.
     */
    public function getTopOrganizers(Carbon $start, Carbon $end): array
    {
        $cacheKey = "admin.analytics.top_orgs.{$start->format('Ymd')}.{$end->format('Ymd')}";
        
        return Cache::remember($cacheKey, 600, function () use ($start, $end) {
            return Organization::withSum(['orders as total_revenue' => function ($query) use ($start, $end) {
                    $query->whereIn('status', ['paid', 'completed'])
                          ->whereBetween('created_at', [$start, $end]);
                }], 'total_amount')
                ->withCount(['orders as total_orders' => function ($query) use ($start, $end) {
                    $query->whereIn('status', ['paid', 'completed'])
                          ->whereBetween('created_at', [$start, $end]);
                }])
                ->having('total_revenue', '>', 0)
                ->orderByDesc('total_revenue')
                ->take(5)
                ->get()
                ->toArray();
        });
    }

    /**
     * Get top events.
     */
    public function getTopEvents(Carbon $start, Carbon $end): array
    {
        $cacheKey = "admin.analytics.top_events.{$start->format('Ymd')}.{$end->format('Ymd')}";
        
        return Cache::remember($cacheKey, 600, function () use ($start, $end) {
            return Event::withSum(['orders as total_revenue' => function ($query) use ($start, $end) {
                    $query->whereIn('status', ['paid', 'completed'])
                          ->whereBetween('created_at', [$start, $end]);
                }], 'total_amount')
                ->withCount(['orders as total_orders' => function ($query) use ($start, $end) {
                    $query->whereIn('status', ['paid', 'completed'])
                          ->whereBetween('created_at', [$start, $end]);
                }])
                ->having('total_revenue', '>', 0)
                ->orderByDesc('total_revenue')
                ->take(5)
                ->get()
                ->toArray();
        });
    }
}
