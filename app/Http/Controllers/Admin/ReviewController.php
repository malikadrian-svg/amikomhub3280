<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display all reviews in the admin panel with search + filter.
     *
     * Route: GET admin/reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user:id,name,email', 'event:id,title,start_date'])
            ->latest();

        // Search by reviewer name/email or event title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u
                    ->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%"))
                  ->orWhereHas('event', fn ($e) => $e
                    ->where('title', 'LIKE', "%{$search}%"))
                  ->orWhere('title', 'LIKE', "%{$search}%")
                  ->orWhere('body', 'LIKE', "%{$search}%");
            });
        }

        // Filter by approval status
        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }

        // Filter by star rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->integer('rating'));
        }

        $reviews = $query->paginate(20)->withQueryString();

        // Summary statistics for the page header
        $stats = [
            'total'    => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'hidden'   => Review::where('is_approved', false)->count(),
            'avg'      => round((float) Review::where('is_approved', true)->avg('rating'), 1),
        ];

        // Top-rated events (min 3 reviews)
        $topEvents = \App\Models\Event::withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->having('approved_reviews_count', '>=', 3)
            ->orderByDesc('approved_reviews_avg_rating')
            ->take(5)
            ->get();

        // Top-rated partners
        $topPartners = \App\Models\Partner::withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->having('approved_reviews_count', '>=', 1)
            ->orderByDesc('approved_reviews_avg_rating')
            ->take(5)
            ->get();

        return view('admin.reviews.index', compact(
            'reviews',
            'stats',
            'topEvents',
            'topPartners',
        ));
    }

    /**
     * Permanently delete a review (admin action).
     *
     * Route: DELETE admin/reviews/{review}
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus secara permanen.');
    }

    /**
     * Toggle the is_approved flag (show/hide a review without deleting it).
     *
     * Route: PATCH admin/reviews/{review}/toggle
     */
    public function toggleApproval(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        $status = $review->is_approved ? 'ditampilkan' : 'disembunyikan';

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', "Ulasan berhasil {$status}.");
    }
}
