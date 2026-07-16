<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerProfileController extends Controller
{
    /**
     * Show the public profile page for a partner.
     *
     * Route: GET /partners/{partner}
     *
     * Loads all data needed for the partner profile in a single eager-loaded
     * query to avoid N+1. We paginate reviews separately.
     */
    public function show(Request $request, Partner $partner)
    {
        $categories = \App\Models\Category::all();

        // Eager-load completed and upcoming events (limited for performance)
        $completedEvents = $partner->completedEvents()
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->take(6)
            ->get();

        $upcomingEvents = $partner->upcomingEvents()
            ->take(4)
            ->get();

        // Paginated approved reviews with reviewer info, newest first
        $reviews = $partner->approvedReviews()
            ->with('user:id,name,avatar')
            ->with('event:id,title')
            ->latest('reviews.created_at')
            ->paginate(10, ['reviews.*'], 'page', $request->page);

        // Pre-compute aggregates (not cached — served from indexed queries)
        $avgRating         = $partner->averageRating();
        $totalReviews      = $partner->totalReviews();
        $distribution      = $partner->ratingDistribution();
        $totalEvents       = $partner->events()->count();
        $totalCompleted    = $partner->completedEvents()->count();
        $totalUpcoming     = $partner->upcomingEvents()->count();

        return view('partner-profile', compact(
            'partner',
            'categories',
            'completedEvents',
            'upcomingEvents',
            'reviews',
            'avgRating',
            'totalReviews',
            'distribution',
            'totalEvents',
            'totalCompleted',
            'totalUpcoming',
        ));
    }
}
