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
    public function store(StoreTicketTypeRequest $request, $organization, Event $event)
    {
        $data = $request->validated();
        $data['event_id'] = $event->id;
        // Map form field 'quantity_total' to DB column 'quantity'
        $data['quantity'] = $data['quantity_total'];
        unset($data['quantity_total'], $data['quantity_available']);
        $data['is_active'] = true;

        TicketType::create($data);

        return redirect()->route('organizer.events.show', [$organization, $event])
            ->with('success', 'Jenis tiket berhasil ditambahkan.');
    }

    public function update(UpdateTicketTypeRequest $request, $organization, Event $event, TicketType $ticketType)
    {
        $data = $request->validated();

        // Ensure we don't reduce total below sold tickets
        $sold = $ticketType->quantity_sold ?? 0;
        if ($data['quantity_total'] < $sold) {
            return back()->with('error', "Kapasitas total tidak boleh kurang dari tiket yang sudah terjual ($sold).");
        }

        // Map form field 'quantity_total' to DB column 'quantity'
        $data['quantity'] = $data['quantity_total'];
        unset($data['quantity_total']);

        $ticketType->update($data);

        return redirect()->route('organizer.events.show', [$organization, $event])
            ->with('success', 'Jenis tiket berhasil diperbarui.');
    }

    public function destroy($organization, Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);

        $sold = $ticketType->quantity_sold ?? 0;
        if ($sold > 0) {
            return back()->with('error', 'Tidak dapat menghapus jenis tiket yang sudah memiliki penjualan. Anda bisa menonaktifkannya saja.');
        }

        $ticketType->delete();

        return back()->with('success', 'Jenis tiket berhasil dihapus.');
    }
}
