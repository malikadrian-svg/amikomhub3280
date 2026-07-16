<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    /**
     * Display all tickets (paid transactions) belonging to the authenticated user.
     *
     * Route: GET /my-tickets  (middleware: auth)
     *
     * For each ticket, we determine review eligibility so the view can
     * show "Tulis Ulasan" or "Edit Ulasan" buttons inline.
     */
    public function index()
    {
        $categories = \App\Models\Category::all();
        $user       = Auth::user();

        $transactions = $user->transactions()
            ->with('event.category')
            ->whereIn('status', ['success', 'settlement', 'capture'])
            ->latest()
            ->get();

        // Collect all event IDs to batch-check review status (avoids N+1)
        $eventIds = $transactions->pluck('event_id')->unique()->all();

        // Map event_id → user's review (null if not reviewed)
        $userReviews = $user->reviews()
            ->whereIn('event_id', $eventIds)
            ->get()
            ->keyBy('event_id');

        return view('my-tickets', compact('transactions', 'categories', 'userReviews'));
    }

    /**
     * Display a single e-ticket.
     *
     * Security: verifies that the transaction belongs to the authenticated user.
     * A user must NEVER see another user's ticket.
     *
     * Route: GET /my-ticket/{order_id}  (middleware: auth)
     *
     * @param  string  $order_id
     */
    public function show(string $order_id)
    {
        $categories = \App\Models\Category::all();
        $user       = Auth::user();

        $transaction = Transaction::with('event.category', 'event.partner')
            ->where('order_id', $order_id)
            ->firstOrFail();

        // Ownership check: abort with 403 if this ticket doesn't belong to the user
        if ($transaction->user_id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $event = $transaction->event;

        // Review eligibility for the ticket detail page CTA
        $userReview      = $user->reviewForEvent($event->id);
        $canReview       = $user->canReviewEvent($event);
        $reviewableAfter = Carbon::parse($event->date)->addDay()->startOfDay();

        return view('ticket', compact(
            'transaction',
            'categories',
            'userReview',
            'canReview',
            'reviewableAfter',
        ));
    }
}
