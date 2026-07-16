<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Services\TenantContext;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Tenant scope is active thanks to the `org` middleware
        $orgId = app(TenantContext::class)->getId();

        // High level metrics
        $totalEvents = Event::count();
        $totalOrders = Order::whereIn('status', ['paid', 'completed'])->count();
        
        // Sum total tickets (OrderItems quantity) and total gross amount across paid orders
        $metrics = Order::whereIn('status', ['paid', 'completed'])
            ->withSum('items', 'quantity')
            ->get();
            
        $totalTicketsSold = $metrics->sum('items_sum_quantity');
        
        // Sum revenue metrics from OrderCommission
        $commissions = \App\Models\OrderCommission::whereHas('order', function ($query) {
            $query->whereIn('status', ['paid', 'completed']);
        })->get();
        
        $totalRevenue = $commissions->sum('gross_amount');
        $totalCommission = $commissions->sum('commission_amount');
        $netRevenue = $commissions->sum('organizer_amount');
        
        // Upcoming Events
        $upcomingEvents = Event::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        // Recent Orders
        $recentOrders = Order::with('user', 'event')
            ->whereIn('status', ['paid', 'completed'])
            ->latest()
            ->take(5)
            ->get();

        return view('organizer.dashboard', compact(
            'totalEvents',
            'totalOrders',
            'totalTicketsSold',
            'totalRevenue',
            'totalCommission',
            'netRevenue',
            'upcomingEvents',
            'recentOrders'
        ));
    }
}
