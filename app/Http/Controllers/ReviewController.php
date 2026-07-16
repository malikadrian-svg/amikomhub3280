<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    /**
     * Store a new review for an event.
     *
     * Route: POST /events/{event}/reviews  (middleware: auth, throttle:5,1)
     *
     * Security flow:
     *  1. `auth` middleware rejects unauthenticated users at route level
     *  2. Policy::create() verifies all 5 business rules
     *  3. StoreReviewRequest validates and sanitizes the input
     *  4. user_id + event_id are set explicitly (never from request payload)
     *  5. DB unique constraint is the final safety net against races
     */
    public function store(StoreReviewRequest $request, Event $event): RedirectResponse
    {
        // Authorize against ReviewPolicy::create($user, $event)
        Gate::authorize('create', [Review::class, $event]);

        // Create the review. user_id is assigned directly to bypass $fillable guard.
        // event_id is handled automatically by the relationship.
        $review = new Review([
            'rating'  => $request->integer('rating'),
            'title'   => $request->input('title'),
            'body'    => $request->input('body'),
        ]);
        $review->user_id = Auth::id();
        
        $event->reviews()->save($review);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Ulasan Anda berhasil dikirimkan. Terima kasih atas masukan Anda!');
    }

    /**
     * Update an existing review.
     *
     * Route: PUT /reviews/{review}  (middleware: auth)
     */
    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        // Authorize against ReviewPolicy::update($user, $review)
        Gate::authorize('update', $review);

        $review->update([
            'rating' => $request->integer('rating'),
            'title'  => $request->input('title'),
            'body'   => $request->input('body'),
        ]);

        return redirect()
            ->route('events.show', $review->event_id)
            ->with('success', 'Ulasan Anda berhasil diperbarui.');
    }

    /**
     * Delete a review (author or admin only).
     *
     * Route: DELETE /reviews/{review}  (middleware: auth)
     */
    public function destroy(Review $review): RedirectResponse
    {
        // Authorize against ReviewPolicy::delete($user, $review)
        Gate::authorize('delete', $review);

        $eventId = $review->event_id;

        $review->delete();

        return redirect()
            ->route('events.show', $eventId)
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
