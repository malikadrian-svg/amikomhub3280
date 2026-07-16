<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventApprovalController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('approve', Event::class);

        // Allow filtering by status, default to pending
        $status = $request->query('status', 'pending');
        
        $events = Event::with('organization')
            ->where('status', $status)
            ->latest()
            ->paginate(15);

        return view('admin.event-approvals.index', compact('events', 'status'));
    }

    public function approve(Request $request, Event $event)
    {
        $this->authorize('approve', $event);

        if ($event->status !== 'pending') {
            return back()->with('error', 'Hanya event berstatus pending yang dapat disetujui.');
        }

        $event->update(['status' => 'approved']);

        $event->approvalLogs()->create([
            'reviewer_id' => auth()->id(),
            'status_from' => 'pending',
            'status_to'   => 'approved',
            'notes'       => $request->input('notes', 'Disetujui oleh Admin.'),
        ]);

        if ($event->organization && $event->organization->owner) {
            $event->organization->owner->notify(new \App\Notifications\EventApprovedNotification($event));
        }

        return back()->with('success', "Event '{$event->title}' berhasil disetujui.");
    }

    public function reject(Request $request, Event $event)
    {
        $this->authorize('approve', $event);

        $request->validate([
            'notes' => 'required|string|min:10',
        ]);

        if ($event->status !== 'pending') {
            return back()->with('error', 'Hanya event berstatus pending yang dapat ditolak.');
        }

        $event->update(['status' => 'rejected']);

        $event->approvalLogs()->create([
            'reviewer_id' => auth()->id(),
            'status_from' => 'pending',
            'status_to'   => 'rejected',
            'notes'       => $request->notes,
        ]);

        if ($event->organization && $event->organization->owner) {
            $event->organization->owner->notify(new \App\Notifications\EventRejectedNotification($event));
        }

        return back()->with('success', "Event '{$event->title}' ditolak.");
    }
}
