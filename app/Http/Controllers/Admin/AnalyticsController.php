<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyRevenue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Get platform revenue data for charts.
     */
    public function revenueChart(Request $request)
    {
        $days = $request->query('days', 30);
        $startDate = Carbon::today()->subDays($days - 1);
        
        $revenues = DailyRevenue::whereNull('organization_id')
            ->where('date', '>=', $startDate->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        // Fill in missing days with zeros
        $data = [];
        $labels = [];
        $gmvData = [];
        $platformEarningsData = [];

        for ($i = 0; $i < $days; $i++) {
            $currentDate = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($currentDate)->format('d M');
            
            $dayData = $revenues->firstWhere('date', $currentDate);
            
            $gmvData[] = $dayData ? $dayData->gross_revenue : 0;
            $platformEarningsData[] = $dayData ? $dayData->net_revenue : 0;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'GMV (Gross Merchandise Value)',
                    'data' => $gmvData,
                    'borderColor' => '#8b5cf6', // purple-500
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ],
                [
                    'label' => 'Platform Earnings',
                    'data' => $platformEarningsData,
                    'borderColor' => '#22c55e', // green-500
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ]
            ]
        ]);
    }
}
