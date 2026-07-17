@extends('layouts.admin')

@section('header', 'Persetujuan Event')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div style="background: #dcfce7; border: 1px solid #16a34a; color: #15803d; padding: var(--space-4) var(--space-6); border-radius: var(--radius-md); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3);">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background: #fee2e2; border: 1px solid #dc2626; color: #b91c1c; padding: var(--space-4) var(--space-6); border-radius: var(--radius-md); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3);">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        {{ session('error') }}
    </div>
@endif

<div class="card" style="padding: 0; overflow: hidden;">

    {{-- Header --}}
    <div style="padding: var(--space-6) var(--space-8); display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--slate-700); flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h2 class="h3" style="margin: 0;">Antrean Persetujuan Event</h2>
            <p class="body-sm" style="margin: 4px 0 0 0; color: var(--slate-400);">Tinjau dan setujui event yang diajukan oleh penyelenggara.</p>
        </div>

        <form action="{{ route('admin.event-approvals.index') }}" method="GET">
            <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto; min-width: 200px;">
                <option value="pending_review" {{ $status === 'pending_review' ? 'selected' : '' }}>⏳ Menunggu Persetujuan</option>
                <option value="approved"       {{ $status === 'approved'       ? 'selected' : '' }}>✅ Disetujui</option>
                <option value="rejected"       {{ $status === 'rejected'       ? 'selected' : '' }}>❌ Ditolak</option>
            </select>
        </form>
    </div>

    {{-- Status badge helper --}}
    @php
        $statusLabel = match($status) {
            'pending_review' => ['Menunggu Persetujuan', '#f59e0b', '#fef3c7'],
            'approved'       => ['Disetujui',            '#16a34a', '#dcfce7'],
            'rejected'       => ['Ditolak',              '#dc2626', '#fee2e2'],
            default          => [$status,                '#6b7280', '#f3f4f6'],
        };
    @endphp

    {{-- Table --}}
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--slate-700);">
                    <th style="padding: var(--space-3) var(--space-6); text-align: left; font-size: 12px; font-weight: 600; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em;">Event</th>
                    <th style="padding: var(--space-3) var(--space-4); text-align: left; font-size: 12px; font-weight: 600; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em;">Penyelenggara</th>
                    <th style="padding: var(--space-3) var(--space-4); text-align: left; font-size: 12px; font-weight: 600; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em;">Jadwal</th>
                    <th style="padding: var(--space-3) var(--space-4); text-align: left; font-size: 12px; font-weight: 600; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em;">Tiket</th>
                    <th style="padding: var(--space-3) var(--space-6); text-align: right; font-size: 12px; font-weight: 600; color: var(--slate-400); text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr style="border-bottom: 1px solid var(--slate-800); transition: background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background=''">
                        {{-- Event info --}}
                        <td style="padding: var(--space-4) var(--space-6);">
                            <div style="display: flex; align-items: center; gap: var(--space-3);">
                                @if($event->poster_path)
                                    <img src="{{ Storage::url($event->poster_path) }}"
                                         style="width: 44px; height: 44px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--slate-700); flex-shrink: 0;">
                                @else
                                    <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--slate-800); border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; color: var(--slate-500); flex-shrink: 0;">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div style="min-width: 0;">
                                    <div style="font-weight: 600; color: var(--slate-0); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px;" title="{{ $event->title }}">
                                        {{ $event->title }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--slate-400); margin-top: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px;">
                                        📍 {{ $event->location ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Organizer --}}
                        <td style="padding: var(--space-4);">
                            <div style="font-weight: 600; color: var(--purple-400);">{{ $event->organization?->name ?? '-' }}</div>
                            <div style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">{{ $event->organization?->email ?? '' }}</div>
                        </td>

                        {{-- Schedule --}}
                        <td style="padding: var(--space-4);">
                            <div style="font-weight: 500; color: var(--slate-0);">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</div>
                            <div style="font-size: 12px; color: var(--slate-400); margin-top: 2px;">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} –
                                {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}
                            </div>
                        </td>

                        {{-- Tickets --}}
                        <td style="padding: var(--space-4);">
                            @php $ticketCount = $event->ticketTypes()->count(); @endphp
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 600;
                                background: {{ $ticketCount > 0 ? 'rgba(139,92,246,0.15)' : 'var(--slate-800)' }};
                                color: {{ $ticketCount > 0 ? 'var(--purple-400)' : 'var(--slate-400)' }};
                                border: 1px solid {{ $ticketCount > 0 ? 'rgba(139,92,246,0.3)' : 'var(--slate-700)' }};">
                                {{ $ticketCount }} Tipe
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td style="padding: var(--space-4) var(--space-6);">
                            <div style="display: flex; justify-content: flex-end; align-items: center; gap: var(--space-2); flex-wrap: nowrap;">

                                {{-- Preview button --}}
                                <a href="{{ route('events.show', $event) }}" target="_blank"
                                   style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: var(--radius-sm); border: 1px solid var(--slate-600); background: var(--slate-800); color: var(--slate-200); font-size: 13px; font-weight: 500; text-decoration: none; white-space: nowrap; transition: all 0.15s;"
                                   title="Pratinjau Halaman Publik"
                                   onmouseover="this.style.borderColor='var(--slate-400)';this.style.color='white';"
                                   onmouseout="this.style.borderColor='var(--slate-600)';this.style.color='var(--slate-200)';">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Pratinjau
                                </a>

                                @if($status === 'pending_review')
                                    {{-- Approve button --}}
                                    <form action="{{ route('admin.event-approvals.approve', $event) }}" method="POST" style="margin: 0;">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                onclick="return confirm('Setujui event \"{{ addslashes($event->title) }}\"? Event akan segera aktif.')"
                                                style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: var(--radius-sm); border: 1px solid #16a34a; background: rgba(22,163,74,0.15); color: #4ade80; font-size: 13px; font-weight: 500; cursor: pointer; white-space: nowrap; transition: all 0.15s;"
                                                onmouseover="this.style.background='rgba(22,163,74,0.3)';"
                                                onmouseout="this.style.background='rgba(22,163,74,0.15)';">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- Reject button --}}
                                    <button onclick="openRejectModal({{ $event->id }}, '{{ addslashes($event->title) }}')"
                                            style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: var(--radius-sm); border: 1px solid #dc2626; background: rgba(220,38,38,0.15); color: #f87171; font-size: 13px; font-weight: 500; cursor: pointer; white-space: nowrap; transition: all 0.15s;"
                                            onmouseover="this.style.background='rgba(220,38,38,0.3)';"
                                            onmouseout="this.style.background='rgba(220,38,38,0.15)';">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Tolak
                                    </button>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-16) var(--space-8);">
                            <div style="color: var(--slate-500);">
                                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto var(--space-4) auto; display: block; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="body" style="color: var(--slate-400); margin: 0;">Tidak ada event dengan status ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($events->hasPages())
        <div style="padding: var(--space-4) var(--space-6); border-top: 1px solid var(--slate-700);">
            {{ $events->links() }}
        </div>
    @endif

</div>

{{-- ── Reject Modal ──────────────────────────────────────────────────────────── --}}
<div id="reject-modal"
     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; padding: var(--space-4);">
    <div style="background: var(--slate-900); border: 1px solid var(--slate-700); border-radius: var(--radius-lg); padding: var(--space-8); width: 100%; max-width: 520px; box-shadow: 0 25px 50px rgba(0,0,0,0.5);">

        {{-- Modal header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-6);">
            <div>
                <h3 class="h4" style="margin: 0; color: #f87171; display: flex; align-items: center; gap: var(--space-2);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Tolak Event
                </h3>
                <p id="reject-event-name" class="body-sm" style="margin: 4px 0 0 0; color: var(--slate-400);"></p>
            </div>
            <button onclick="closeRejectModal()"
                    style="background: none; border: none; color: var(--slate-400); cursor: pointer; padding: 4px; border-radius: var(--radius-sm);"
                    onmouseover="this.style.color='white'" onmouseout="this.style.color='var(--slate-400)'">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="reject-form" action="" method="POST">
            @csrf
            @method('PATCH')

            <div style="margin-bottom: var(--space-6);">
                <label class="label" style="margin-bottom: var(--space-2); display: block;">
                    Alasan Penolakan <span style="color: #f87171;">*</span>
                </label>
                <textarea name="notes" rows="4" required minlength="10"
                          class="form-control"
                          style="border-color: rgba(220,38,38,0.5); resize: vertical;"
                          placeholder="Jelaskan secara detail alasan penolakan agar penyelenggara dapat memperbaiki pengajuannya..."></textarea>
                <p class="caption" style="margin-top: var(--space-2); color: var(--slate-400);">
                    Minimal 10 karakter. Alasan ini akan dikirimkan ke penyelenggara sebagai notifikasi.
                </p>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: var(--space-3);">
                <button type="button" onclick="closeRejectModal()" class="btn btn-secondary">Batal</button>
                <button type="submit"
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 20px; border-radius: var(--radius-sm); border: 1px solid #dc2626; background: rgba(220,38,38,0.2); color: #f87171; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.background='rgba(220,38,38,0.4)'"
                        onmouseout="this.style.background='rgba(220,38,38,0.2)'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Konfirmasi Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id, title) {
        document.getElementById('reject-form').action = `/admin/event-approvals/${id}/reject`;
        document.getElementById('reject-event-name').textContent = title;
        document.getElementById('reject-modal').style.display = 'flex';
        document.querySelector('#reject-form textarea').focus();
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').style.display = 'none';
        document.getElementById('reject-form').reset();
    }

    // Close modal when clicking backdrop
    document.getElementById('reject-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>

@endsection
