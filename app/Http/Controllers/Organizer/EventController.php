<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        // $this->authorize('events.view'); handled by middleware/trait generally, 
        // but we can explicitly authorize the action if we have an EventPolicy for viewAny
        $this->authorize('viewAny', Event::class);

        // Fetch events scoped to the current organization (handled by OrganizationScope)
        $events = Event::with('category')->latest()->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $this->authorize('create', Event::class);
        $categories = Category::orderBy('name')->get();

        return view('organizer.events.create', compact('categories'));
    }

    public function store(StoreEventRequest $request)
    {
        $orgId = app(TenantContext::class)->getId();
        
        $data = $request->validated();
        $data['organization_id'] = $orgId;
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        $data['status'] = 'draft'; // Always start as draft

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Event berhasil dibuat sebagai draf. Silakan tambahkan tiket sebelum mengajukan persetujuan.');
    }

    public function show(Request $request, $organization, Event $event)
    {
        $this->authorize('view', $event);
        
        $event->load('ticketTypes', 'approvalLogs.reviewer');

        return view('organizer.events.show', compact('event'));
    }

    public function edit(Request $request, $organization, Event $event)
    {
        $this->authorize('update', $event);
        $categories = Category::orderBy('name')->get();

        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(UpdateEventRequest $request, $organization, Event $event)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Detail event berhasil diperbarui.');
    }

    public function destroy(Request $request, $organization, Event $event)
    {
        $this->authorize('delete', $event);

        if ($event->orders()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus event yang sudah memiliki transaksi.');
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Submit a draft event for admin approval.
     */
    public function submitForApproval(Request $request, $organization, Event $event)
    {
        $this->authorize('update', $event);

        if ($event->status !== 'draft') {
            return back()->with('error', 'Hanya event berstatus draf yang dapat diajukan.');
        }

        if ($event->ticketTypes()->count() === 0) {
            return back()->with('error', 'Event harus memiliki minimal 1 jenis tiket sebelum diajukan.');
        }

        $event->update(['status' => 'pending']);

        $event->approvalLogs()->create([
            'status_from' => 'draft',
            'status_to'   => 'pending',
            'notes'       => 'Event diajukan untuk persetujuan admin.',
        ]);

        return back()->with('success', 'Event berhasil diajukan! Menunggu persetujuan admin.');
    }
}
