<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // Statistik kartu ringkasan
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])
            ->sum('total_price');

        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])
            ->count();

        $activeEvents = Event::where('date', '>=', now())->count();

        $pendingOrders = Transaction::where('status', 'pending')->count();

        // Review statistics
        $totalReviews  = Review::count();
        $avgRating     = round((float) Review::where('is_approved', true)->avg('rating'), 1);
        $pendingReviews = Review::where('is_approved', false)->count();

        // 5 transaksi terakhir untuk tabel ringkasan
        $recentTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        // 5 most recent reviews
        $recentReviews = Review::with(['user:id,name,avatar', 'event:id,title'])
            ->where('is_approved', true)
            ->latest()
            ->take(5)
            ->get();

        // Data user yang sedang login
        $admin = Auth::user();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'totalReviews',
            'avgRating',
            'pendingReviews',
            'recentTransactions',
            'recentReviews',
            'admin'
        ));
    }
}
