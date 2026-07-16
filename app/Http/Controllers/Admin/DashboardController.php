<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\Organization;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // Data metrics dengan Order dan OrderCommission, di-cache 15 menit
        $gmv = \Illuminate\Support\Facades\Cache::remember('admin.dashboard.gmv', 900, function () {
            return Order::whereIn('status', ['paid', 'completed'])->sum('total_amount');
        });
        
        $platformEarnings = \Illuminate\Support\Facades\Cache::remember('admin.dashboard.platform_earnings', 900, function () {
            $totalPlatformFees = Order::whereIn('status', ['paid', 'completed'])->sum('platform_fee');
            $totalCommissions = OrderCommission::whereHas('order', function($q) {
                $q->whereIn('status', ['paid', 'completed']);
            })->sum('commission_amount');
            return $totalPlatformFees + $totalCommissions;
        });

        $ticketsSold = \Illuminate\Support\Facades\Cache::remember('admin.dashboard.tickets_sold', 900, function () {
            return Order::whereIn('status', ['paid', 'completed'])
                ->withSum('items', 'quantity')
                ->get()
                ->sum('items_sum_quantity');
        });

        $activeEvents = \Illuminate\Support\Facades\Cache::remember('admin.dashboard.active_events', 900, function () {
            return Event::where('status', 'published')
                ->where('start_date', '>=', now())
                ->count();
        });

        // Aksi Diperlukan (Pending Approvals) - real-time (no cache)
        $pendingOrgs = Organization::where('status', 'pending')->count();
        $pendingEvents = Event::where('status', 'pending')->count();

        // Review statistics - di-cache 15 menit
        $reviewStats = \Illuminate\Support\Facades\Cache::remember('admin.dashboard.review_stats', 900, function () {
            return [
                'total' => Review::count(),
                'avg' => round((float) Review::where('is_approved', true)->avg('rating'), 1),
                'pending' => Review::where('is_approved', false)->count(),
            ];
        });
        $totalReviews = $reviewStats['total'];
        $avgRating = $reviewStats['avg'];
        $pendingReviews = $reviewStats['pending'];

        // 5 transaksi terakhir untuk tabel ringkasan (real-time)
        $recentOrders = Order::with('user', 'event')
            ->whereIn('status', ['paid', 'completed'])
            ->latest()
            ->take(5)
            ->get();

        // 5 most recent reviews (real-time)
        $recentReviews = Review::with(['user:id,name,avatar', 'event:id,title'])
            ->where('is_approved', true)
            ->latest()
            ->take(5)
            ->get();

        // Data user yang sedang login
        $admin = Auth::user();

        return view('admin.dashboard', compact(
            'gmv',
            'platformEarnings',
            'ticketsSold',
            'activeEvents',
            'pendingOrgs',
            'pendingEvents',
            'totalReviews',
            'avgRating',
            'pendingReviews',
            'recentOrders',
            'recentReviews',
            'admin'
        ));
    }
}
