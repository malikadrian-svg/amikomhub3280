<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    /**
     * Display all tickets (paid transactions) belonging to the authenticated user.
     *
     * Route: GET /my-tickets  (middleware: auth)
     */
    public function index()
    {
        $categories = \App\Models\Category::all();

        $transactions = Auth::user()
            ->transactions()
            ->with('event')
            ->whereIn('status', ['success', 'settlement', 'capture'])
            ->latest()
            ->get();

        return view('my-tickets', compact('transactions', 'categories'));
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

        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        // Ownership check: abort with 403 if this ticket doesn't belong to the user
        if ($transaction->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses ke tiket ini.');
        }

        return view('ticket', compact('transaction', 'categories'));
    }
}
