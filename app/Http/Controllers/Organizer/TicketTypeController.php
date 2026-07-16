<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketTypeRequest;
use App\Http\Requests\UpdateTicketTypeRequest;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function store(StoreTicketTypeRequest $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        $data = $request->validated();
        $data['event_id'] = $event->id;
        $data['quantity_available'] = $data['quantity_total'];
        $data['is_active'] = true;

        TicketType::create($data);

        return back()->with('success', 'Jenis tiket berhasil ditambahkan.');
    }

    public function update(UpdateTicketTypeRequest $request, $eventId, TicketType $ticketType)
    {
        $data = $request->validated();

        // Ensure we don't reduce total below sold tickets
        $sold = $ticketType->quantity_total - $ticketType->quantity_available;
        if ($data['quantity_total'] < $sold) {
            return back()->with('error', "Kapasitas total tidak boleh kurang dari tiket yang sudah terjual ($sold).");
        }

        // Adjust available quantity
        $data['quantity_available'] = $data['quantity_total'] - $sold;

        $ticketType->update($data);

        return back()->with('success', 'Jenis tiket berhasil diperbarui.');
    }

    public function destroy($eventId, TicketType $ticketType)
    {
        $event = Event::findOrFail($eventId);
        $this->authorize('update', $event);

        $sold = $ticketType->quantity_total - $ticketType->quantity_available;
        if ($sold > 0) {
            return back()->with('error', 'Tidak dapat menghapus jenis tiket yang sudah memiliki penjualan. Anda bisa menonaktifkannya saja.');
        }

        $ticketType->delete();

        return back()->with('success', 'Jenis tiket berhasil dihapus.');
    }
}
