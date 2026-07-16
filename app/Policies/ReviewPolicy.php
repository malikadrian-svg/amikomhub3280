<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine whether the user can create a review for an event.
     */
    public function create(User $user, Event $event): bool
    {
        return $user->canReviewEvent($event);
    }

    /**
     * Determine whether the user can update a review.
     * Only the review's author may edit it.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id && $review->isEditable();
    }

    /**
     * Determine whether the user can delete a review.
     * The review's author OR any admin/organizer with the right permission.
     */
    public function delete(User $user, Review $review): bool
    {
        if ($user->id === $review->user_id) {
            return true;
        }

        // Platform admin or Organizer with moderate permission
        return $user->hasPermission('reviews.moderate');
    }

    /**
     * Admins/Organizers can toggle the is_approved flag on any review.
     */
    public function toggleApproval(User $user, Review $review): bool
    {
        return $user->hasPermission('reviews.moderate');
    }
}
