<?php

namespace App\Console\Commands;

use App\Models\DailyRevenue;
use App\Models\Order;
use App\Models\OrderCommission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateDailyRevenue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:aggregate-daily-revenue {--date= : The date to aggregate (YYYY-MM-DD). Defaults to yesterday.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate daily revenue from orders and calculate platform vs organizer splits.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateStr = $this->option('date');
        
        // Default to yesterday if no date is provided
        $date = $dateStr ? Carbon::parse($dateStr) : Carbon::yesterday();
        $dateStart = $date->copy()->startOfDay();
        $dateEnd = $date->copy()->endOfDay();

        $this->info("Aggregating revenue for: {$date->format('Y-m-d')}");

        DB::transaction(function () use ($date, $dateStart, $dateEnd) {
            // Get all paid/completed orders for the specific date
            $orders = Order::whereIn('status', ['paid', 'completed'])
                ->whereBetween('updated_at', [$dateStart, $dateEnd])
                ->with('organization', 'commission', 'items')
                ->get();

            if ($orders->isEmpty()) {
                $this->info("No orders found for this date.");
                return;
            }

            // Aggregate per organization
            $orgTotals = [];
            
            // Platform totals
            $platformGross = 0;
            $platformFee = 0;
            $platformCommission = 0;
            $platformTickets = 0;

            foreach ($orders as $order) {
                $orgId = $order->organization_id;
                
                if (!isset($orgTotals[$orgId])) {
                    $orgTotals[$orgId] = [
                        'gross_revenue' => 0,
                        'platform_fee' => 0,
                        'commission_amount' => 0,
                        'tickets_sold' => 0,
                    ];
                }

                $gross = $order->total_amount;
                $fee = $order->platform_fee;
                $commission = $order->commission ? $order->commission->commission_amount : 0;
                $tickets = $order->items->sum('quantity');

                // Add to Org
                $orgTotals[$orgId]['gross_revenue'] += $gross;
                $orgTotals[$orgId]['platform_fee'] += $fee;
                $orgTotals[$orgId]['commission_amount'] += $commission;
                $orgTotals[$orgId]['tickets_sold'] += $tickets;

                // Add to Platform
                $platformGross += $gross;
                $platformFee += $fee;
                $platformCommission += $commission;
                $platformTickets += $tickets;
            }

            // Save organization aggregates
            foreach ($orgTotals as $orgId => $totals) {
                DailyRevenue::updateOrCreate(
                    [
                        'date' => $date->format('Y-m-d'),
                        'organization_id' => $orgId,
                    ],
                    [
                        'gross_revenue' => $totals['gross_revenue'],
                        'platform_fee' => $totals['platform_fee'],
                        'commission_amount' => $totals['commission_amount'],
                        'net_revenue' => $totals['gross_revenue'] - $totals['platform_fee'] - $totals['commission_amount'],
                        'tickets_sold' => $totals['tickets_sold'],
                    ]
                );
            }

            // Save platform aggregate (organization_id is null)
            DailyRevenue::updateOrCreate(
                [
                    'date' => $date->format('Y-m-d'),
                    'organization_id' => null,
                ],
                [
                    'gross_revenue' => $platformGross,
                    'platform_fee' => $platformFee,
                    'commission_amount' => $platformCommission,
                    'net_revenue' => $platformFee + $platformCommission, // For platform, net revenue is the fees + commissions it earned
                    'tickets_sold' => $platformTickets,
                ]
            );

            $this->info("Aggregation completed successfully.");
        });
    }
}
