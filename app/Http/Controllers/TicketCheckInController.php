<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketCheckInController extends Controller
{
    public function index()
    {
        $organization = app(\App\Services\TenantContext::class)->get();
        
        if (!$organization) {
            abort(403, 'Unauthorized access.');
        }

        return view('organizer.checkin.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticketCode = $request->input('ticket_code');
        $organization = app(\App\Services\TenantContext::class)->get();

        if (!$organization) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Use pessimistic locking to prevent double check-ins
            // NOTE: lockForUpdate is not compatible with eager loading (with()),
            // so we load the event relation separately after the lock.
            $ticket = Ticket::where(function ($query) use ($ticketCode) {
                $query->where('ticket_code', $ticketCode)
                      ->orWhere('qr_code', $ticketCode);
            })
                ->lockForUpdate()
                ->first();

            if (!$ticket) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket tidak ditemukan.',
                ], 404);
            }

            // Load event relation separately (after lock)
            $event = \App\Models\Event::find($ticket->event_id);

            if (!$event) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Event untuk tiket ini tidak ditemukan.',
                ], 404);
            }

            // Verify ticket belongs to an event owned by this organization
            if ($event->organization_id !== $organization->id) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket tidak valid untuk event Anda.',
                ], 403);
            }

            // Check status
            if ($ticket->status === 'used') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket sudah digunakan pada ' . $ticket->checked_in_at->format('d M Y H:i'),
                    'ticket' => $ticket
                ], 422);
            }

            if ($ticket->status !== 'active') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Status tiket tidak aktif (' . $ticket->status . ').',
                ], 422);
            }

            // Process check-in
            $ticket->update([
                'status' => 'used',
                'checked_in_at' => now(),
                'checked_in_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil!',
                'ticket' => [
                    'code' => $ticket->ticket_code,
                    'event_name' => $event->title,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }
}
