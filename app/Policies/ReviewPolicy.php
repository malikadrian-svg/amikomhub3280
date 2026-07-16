<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine whether the user can create a review for an event.
     *
     * All five business rules are enforced here:
     *  1. User has purchased a ticket (paid transaction exists)
     *  2. Payment status is SUCCESS / settlement / capture
     *  3. The ticket belongs to this user (user_id match, enforced by querying user's transactions)
     *  4. The event has already finished AND 1 day has passed
     *  5. The user has not already reviewed this event
     */
    public function create(User $user, Event $event): bool
    {
        // Rules 4: event must be reviewable (ended + 1 day grace period)
        if (! $event->isReviewable()) {
            return false;
        }

        // Rule 5: no duplicate reviews
        if ($user->hasReviewedEvent($event->id)) {
            return false;
        }

        // Rules 1, 2, 3: user must own a paid transaction for this event
        return $user->transactions()
            ->where('event_id', $event->id)
            ->whereIn('status', ['success', 'settlement', 'capture'])
            ->exists();
    }

    /**
     * Determine whether the user can update a review.
     *
     * Only the review's author may edit it.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    /**
     * Determine whether the user can delete a review.
     *
     * The review's author OR any admin may delete a review.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $user->isAdmin();
    }

    /**
     * Admins can toggle the is_approved flag on any review.
     */
    public function toggleApproval(User $user, Review $review): bool
    {
        return $user->isAdmin();
    }
}
