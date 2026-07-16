@extends('layouts.organizer')

@section('content')
{{-- Page Header --}}
<div style="margin-bottom: var(--space-8); display: flex; justify-content: space-between; align-items: flex-start;">
    <div style="display: flex; align-items: center; gap: var(--space-4);">
        <a href="{{ route('organizer.events.index', request()->route('organization')) }}" style="width: 40px; height: 40px; border-radius: var(--radius-md); background: var(--slate-100); border: 1px solid var(--slate-200); display: flex; align-items: center; justify-content: center; color: var(--slate-500); text-decoration: none; transition: all 0.2s; flex-shrink: 0;" onmouseover="this.style.background='var(--purple-50)';this.style.color='var(--purple-600)'" onmouseout="this.style.background='var(--slate-100)';this.style.color='var(--slate-500)'">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <div style="display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-1);">
                <h1 class="h2" style="margin: 0; color: var(--slate-900);">{{ $event->title }}</h1>
                @if ($event->status === 'draft')
                    <span class="badge">Draf</span>
                @elseif ($event->status === 'pending')
                    <span class="badge" style="background: rgba(234,179,8,0.1); color: #854d0e; border-color: rgba(234,179,8,0.3);">Menunggu Review</span>
                @elseif ($event->status === 'approved' || $event->status === 'published')
                    <span class="badge" style="background: rgba(22,163,74,0.08); color: #166534; border-color: rgba(22,163,74,0.2);">Aktif</span>
                @elseif ($event->status === 'rejected')
                    <span class="badge" style="background: rgba(220,38,38,0.08); color: #991b1b; border-color: rgba(220,38,38,0.2);">Ditolak</span>
                @endif
            </div>
            <p class="body-sm" style="color: var(--slate-500); margin: 0;">{{ $event->location }} &bull; {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</p>
        </div>
    </div>

    <div style="display: flex; gap: var(--space-3); flex-shrink: 0;">
        @if ($event->status === 'draft')
            <form action="{{ route('organizer.events.submit', [request()->route('organization'), $event]) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary" {{ $event->ticketTypes->count() === 0 ? 'disabled' : '' }}
                    style="{{ $event->ticketTypes->count() === 0 ? 'opacity: 0.5; cursor: not-allowed;' : '' }}"
                    {{ $event->ticketTypes->count() > 0 ? 'onclick="return confirm(\'Ajukan event ini untuk ditinjau admin?\')"' : '' }}>
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: var(--space-2);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Ajukan Persetujuan
                </button>
            </form>
        @endif
        <a href="{{ route('organizer.events.edit', [request()->route('organization'), $event]) }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: var(--space-2);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Detail
        </a>
    </div>
</div>

@if ($event->status === 'draft' && $event->ticketTypes->count() === 0)
<div style="background: rgba(99,102,241,0.06); border: 1px solid rgba(99,102,241,0.2); border-radius: var(--radius-md); padding: var(--space-4) var(--space-5); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3);">
    <svg width="20" height="20" fill="none" stroke="#6366f1" viewBox="0 0 24 24" style="flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <p style="color: #4338ca; font-size: 14px; font-weight: 500; margin: 0;">Tambahkan setidaknya 1 jenis tiket sebelum Anda dapat mengajukan event ini untuk ditinjau oleh admin.</p>
</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: var(--space-8);">

    {{-- Left: Info & Image --}}
    <div style="display: flex; flex-direction: column; gap: var(--space-6);">
        <div class="card" style="padding: 0; overflow: hidden;">
            @if($event->image)
                <img src="{{ Storage::url($event->image) }}" style="width: 100%; height: 180px; object-fit: cover;" alt="Banner">
            @else
                <div style="width: 100%; height: 180px; background: var(--purple-50); display: flex; align-items: center; justify-content: center; color: var(--purple-300);">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
            <div style="padding: var(--space-5);">
                <h3 class="h4" style="margin-bottom: var(--space-4); color: var(--slate-900);">Informasi Event</h3>
                <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                    <div>
                        <p class="caption" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); margin-bottom: 4px;">Kategori</p>
                        <p class="body-sm" style="color: var(--slate-800); margin: 0;">{{ $event->category->name }}</p>
                    </div>
                    <div>
                        <p class="caption" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); margin-bottom: 4px;">Mulai</p>
                        <p class="body-sm" style="color: var(--slate-800); margin: 0;">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="caption" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); margin-bottom: 4px;">Selesai</p>
                        <p class="body-sm" style="color: var(--slate-800); margin: 0;">{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="caption" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); margin-bottom: 4px;">Lokasi</p>
                        <p class="body-sm" style="color: var(--slate-800); margin: 0;">{{ $event->location }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Approval Logs --}}
        @if($event->approvalLogs->count() > 0)
        <div class="card" style="padding: var(--space-5);">
            <h3 class="h4" style="margin-bottom: var(--space-4); color: var(--slate-900);">Riwayat Persetujuan</h3>
            <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                @foreach($event->approvalLogs as $log)
                <div style="border-left: 3px solid {{ $log->status_to === 'approved' ? 'var(--feedback-success)' : ($log->status_to === 'rejected' ? 'var(--feedback-error)' : 'var(--feedback-warning)') }}; padding-left: var(--space-3);">
                    <p style="font-size: 13px; font-weight: 600; color: var(--slate-800); margin: 0 0 2px 0;">{{ ucfirst($log->status_from) }} &rarr; {{ ucfirst($log->status_to) }}</p>
                    <p class="caption" style="color: var(--slate-400); margin: 0 0 var(--space-2) 0;">{{ $log->created_at->format('d M Y, H:i') }}</p>
                    @if($log->notes)
                        <p style="font-size: 13px; color: var(--slate-600); background: var(--slate-50); padding: var(--space-2) var(--space-3); border-radius: var(--radius-sm); margin: 0;">{{ $log->notes }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Ticket Types --}}
    <div>
        <div class="card" style="padding: var(--space-6);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--slate-100); padding-bottom: var(--space-4); margin-bottom: var(--space-6);">
                <h3 class="h4" style="margin: 0; color: var(--slate-900);">Jenis Tiket</h3>
                @if($event->status === 'draft')
                <button onclick="document.getElementById('ticket-modal').classList.remove('hidden')" class="btn btn-secondary" style="padding: 6px var(--space-4); font-size: 13px;">
                    + Tambah Tiket
                </button>
                @endif
            </div>

            <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                @forelse ($event->ticketTypes as $ticket)
                    <div style="border: 1px solid var(--slate-200); border-radius: var(--radius-md); padding: var(--space-5); display: flex; justify-content: space-between; align-items: center; gap: var(--space-4); transition: box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 2px 12px rgba(139,92,246,0.08)'" onmouseout="this.style.boxShadow='none'">
                        <div>
                            <div style="display: flex; align-items: center; gap: var(--space-2); margin-bottom: var(--space-1);">
                                <h4 style="font-size: 16px; font-weight: 700; color: var(--slate-900); margin: 0;">{{ $ticket->name }}</h4>
                                @if(!$ticket->is_active)
                                    <span class="badge" style="background: rgba(220,38,38,0.08); color: #991b1b; border-color: rgba(220,38,38,0.2); font-size: 10px;">Nonaktif</span>
                                @endif
                            </div>
                            <p style="font-size: 20px; font-weight: 700; color: var(--purple-600); margin: 0 0 var(--space-2) 0;">Rp {{ number_format($ticket->price, 0, ',', '.') }}</p>
                            <p class="caption" style="color: var(--slate-400); margin: 0;">
                                Kapasitas: {{ $ticket->quantity_total - $ticket->quantity_available }} / {{ $ticket->quantity_total }} terjual
                            </p>
                            @if($ticket->start_sale_date)
                                <p class="caption" style="color: var(--slate-400); margin: 4px 0 0 0;">Penjualan: {{ \Carbon\Carbon::parse($ticket->start_sale_date)->format('d M') }} – {{ \Carbon\Carbon::parse($ticket->end_sale_date)->format('d M Y') }}</p>
                            @endif
                        </div>

                        <div style="display: flex; gap: var(--space-2); flex-shrink: 0;">
                            <button onclick="editTicket({{ $ticket->toJson() }})" class="btn btn-secondary" style="padding: 6px var(--space-3); font-size: 13px;">Edit</button>
                            @if($event->status === 'draft' && $ticket->quantity_available === $ticket->quantity_total)
                            <form action="{{ route('organizer.events.ticket-types.destroy', [request()->route('organization'), $event, $ticket]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus tiket ini?')" class="btn btn-destructive" style="padding: 6px var(--space-3); font-size: 13px;">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: var(--space-10);">
                        <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--purple-50); border: 1px solid var(--purple-100); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-3); color: var(--purple-400);">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <p class="body" style="color: var(--slate-500); margin: 0;">Belum ada jenis tiket. Silakan buat tiket untuk acara Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Ticket Modal --}}
<div id="ticket-modal" class="hidden" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; display: none; align-items: center; justify-content: center; padding: var(--space-4);">
    <div style="background: #ffffff; border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.15); width: 100%; max-width: 520px; overflow: hidden;">
        <div style="padding: var(--space-5) var(--space-6); border-bottom: 1px solid var(--slate-100); display: flex; justify-content: space-between; align-items: center;">
            <h3 id="modal-title" class="h4" style="margin: 0; color: var(--slate-900);">Tambah Tiket Baru</h3>
            <button onclick="closeModal()" style="background: none; border: none; color: var(--slate-400); cursor: pointer; padding: 4px; border-radius: var(--radius-sm); transition: color 0.2s;" onmouseover="this.style.color='var(--slate-700)'" onmouseout="this.style.color='var(--slate-400)'">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="ticket-form" action="{{ route('organizer.events.ticket-types.store', [request()->route('organization'), $event]) }}" method="POST" style="padding: var(--space-6);">
            @csrf
            <div id="method-spoof"></div>

            <div style="display: flex; flex-direction: column; gap: var(--space-4);">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Nama Tiket <span style="color: var(--feedback-error);">*</span></label>
                    <input type="text" id="t_name" name="name" required class="form-control" placeholder="Contoh: VIP, Early Bird, Reguler">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Harga (Rp) <span style="color: var(--feedback-error);">*</span></label>
                        <input type="number" id="t_price" name="price" required min="0" class="form-control" placeholder="0">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Kapasitas <span style="color: var(--feedback-error);">*</span></label>
                        <input type="number" id="t_qty" name="quantity_total" required min="1" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Maks. per Transaksi <span style="color: var(--feedback-error);">*</span></label>
                        <input type="number" id="t_max" name="max_per_order" required min="1" max="10" value="5" class="form-control">
                    </div>
                    <div id="status-container" style="display: none; align-items: center; gap: var(--space-2); margin-top: 28px;">
                        <input type="checkbox" id="t_active" name="is_active" value="1" checked style="width: 16px; height: 16px; accent-color: var(--purple-600);">
                        <label for="t_active" style="font-size: 13px; font-weight: 600; color: var(--slate-700); cursor: pointer;">Tiket Aktif</label>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Deskripsi (Opsional)</label>
                    <textarea id="t_desc" name="description" rows="2" class="form-control" placeholder="Manfaat atau keterangan tiket ini..."></textarea>
                </div>
            </div>

            <div style="margin-top: var(--space-6); display: flex; justify-content: flex-end; gap: var(--space-3);">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Tiket</button>
            </div>
        </form>
    </div>
</div>

<script>
    const baseUrl = "{{ route('organizer.events.ticket-types.store', [request()->route('organization'), $event]) }}";
    const modal = document.getElementById('ticket-modal');

    document.getElementById('ticket-modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function closeModal() {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.getElementById('ticket-form').reset();
        document.getElementById('ticket-form').action = baseUrl;
        document.getElementById('method-spoof').innerHTML = '';
        document.getElementById('modal-title').innerText = 'Tambah Tiket Baru';
        document.getElementById('status-container').style.display = 'none';
    }

    function editTicket(ticket) {
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        document.getElementById('modal-title').innerText = 'Edit Tiket';

        document.getElementById('ticket-form').action = `${baseUrl}`.replace('ticket-types', `ticket-types/${ticket.id}`);
        document.getElementById('method-spoof').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('t_name').value = ticket.name;
        document.getElementById('t_price').value = ticket.price;
        document.getElementById('t_qty').value = ticket.quantity_total;
        document.getElementById('t_max').value = ticket.max_per_order;
        document.getElementById('t_desc').value = ticket.description || '';

        document.getElementById('status-container').style.display = 'flex';
        document.getElementById('t_active').checked = ticket.is_active;
    }

    // Open modal from button click
    document.querySelectorAll('[onclick*="ticket-modal"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
        });
    });
</script>
@endsection
