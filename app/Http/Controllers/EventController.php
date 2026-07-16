<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display the event detail page.
     *
     * Loads reviews with eager loading to prevent N+1.
     * Also determines review eligibility for the authenticated user.
     */
    public function show(Event $event)
    {
        $categories = Category::all();

        // Eager-load the organization, category, and active ticket types
        $event->load(['organization', 'category', 'activeTicketTypes']);

        // Paginate approved reviews with reviewer info (10 per page)
        $reviews = $event->approvedReviews()
            ->with('user:id,name,avatar')
            ->latest()
            ->paginate(10);

        // Pre-compute rating data (served from indexed query — no N+1)
        $avgRating    = $event->averageRating();
        $reviewCount  = $event->reviewCount();
        $distribution = $event->ratingDistribution();

        // Determine this user's review status for the event (null = not authenticated)
        $userReview      = null;
        $canReview       = false;
        $reviewableAfter = null;

        if (Auth::check()) {
            $userReview      = Auth::user()->reviewForEvent($event->id);
            $canReview       = Auth::user()->canReviewEvent($event);
            // Tell the view when reviews unlock (for countdown / info message)
            $reviewableAfter = \Carbon\Carbon::parse($event->start_date)->addDay()->startOfDay();
        }

        return view('event-detail', compact(
            'categories',
            'event',
            'reviews',
            'avgRating',
            'reviewCount',
            'distribution',
            'userReview',
            'canReview',
            'reviewableAfter',
        ));
    }

    /**
     * Menampilkan halaman checkout event.
     */
    public function checkout($id)
    {
        return view('checkout');
    }
}
