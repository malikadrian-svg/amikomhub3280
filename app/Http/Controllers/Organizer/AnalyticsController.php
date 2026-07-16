<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\DailyRevenue;
use App\Services\TenantContext;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Get organizer revenue data for charts.
     */
    public function revenueChart(Request $request)
    {
        $organization = TenantContext::organization();
        
        if (!$organization) {
            return response()->json(['error' => 'No active organization found.'], 403);
        }

        $days = $request->query('days', 30);
        $startDate = Carbon::today()->subDays($days - 1);
        
        $revenues = DailyRevenue::where('organization_id', $organization->id)
            ->where('date', '>=', $startDate->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        // Fill in missing days with zeros
        $labels = [];
        $grossData = [];
        $netData = [];

        for ($i = 0; $i < $days; $i++) {
            $currentDate = $startDate->copy()->addDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($currentDate)->format('d M');
            
            $dayData = $revenues->firstWhere('date', $currentDate);
            
            $grossData[] = $dayData ? $dayData->gross_revenue : 0;
            $netData[] = $dayData ? $dayData->net_revenue : 0;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pendapatan Kotor (Gross)',
                    'data' => $grossData,
                    'borderColor' => '#94a3b8', // slate-400
                    'backgroundColor' => 'rgba(148, 163, 184, 0.1)',
                ],
                [
                    'label' => 'Pendapatan Bersih (Net)',
                    'data' => $netData,
                    'borderColor' => '#8b5cf6', // purple-500
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ]
            ]
        ]);
    }
}
