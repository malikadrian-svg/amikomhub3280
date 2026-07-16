<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles the "My Tickets" section for authenticated customers.
 *
 * NOTE (M1 transition): This controller now queries `orders` (the new model)
 * instead of `transactions`. The view receives $orders instead of $transactions.
 * The `my-tickets.blade.php` view will need to be updated to use order fields
 * once this controller change goes live. Until then, check that the view
 * references are compatible.
 *
 * Full checkout refactor (orders → order_items → tickets) is M6.
 */
class TicketController extends Controller
{
    /**
     * Display all paid orders belonging to the authenticated user.
     *
     * Route: GET /my-tickets  (middleware: auth)
     */
    public function index()
    {
        $categories = Category::active()->get();
        $user       = Auth::user();

        $orders = $user->orders()
            ->with('event.category', 'event.organization')
            ->whereIn('status', ['paid', 'completed'])
            ->latest()
            ->get();

        // Collect all event IDs to batch-check review status (avoids N+1)
        $eventIds = $orders->pluck('event_id')->unique()->all();

        // Map event_id → user's review (null if not reviewed)
        $userReviews = $user->reviews()
            ->whereIn('event_id', $eventIds)
            ->get()
            ->keyBy('event_id');

        return view('my-tickets', compact('orders', 'categories', 'userReviews'));
    }

    /**
     * Display a single order detail / e-ticket.
     *
     * Security: verifies the order belongs to the authenticated user.
     * A user must NEVER see another user's order.
     *
     * Route: GET /my-ticket/{order_number}  (middleware: auth)
     */
    public function show(string $order_number)
    {
        $categories = Category::active()->get();
        $user       = Auth::user();

        $order = Order::with(['event.category', 'event.organization', 'items.ticketType', 'items.tickets'])
            ->where('order_number', $order_number)
            ->firstOrFail();

        // Ownership check
        if ($order->user_id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses ke tiket ini.');
        }

        $event = $order->event;

        // Review eligibility for the ticket detail page CTA
        $userReview      = $user->reviewForEvent($event->id);
        $canReview       = $user->canReviewEvent($event);
        $reviewableAfter = Carbon::parse($event->start_date)->addDay()->startOfDay();

        return view('ticket', compact(
            'order',
            'categories',
            'userReview',
            'canReview',
            'reviewableAfter',
        ));
    }
}
