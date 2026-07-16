@extends('layouts.admin')

@section('header', 'Persetujuan Event')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
    
    <div class="card-header" style="margin-bottom: 0; padding: var(--space-6) var(--space-8); display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--slate-700);">
        <div>
            <h2 class="h3" style="margin: 0;">Antrean Persetujuan Event</h2>
            <p class="body-sm" style="margin: 0; color: var(--slate-400);">Tinjau event yang diajukan oleh penyelenggara.</p>
        </div>
        
        <form action="{{ route('admin.event-approvals.index') }}" method="GET" style="display: flex; gap: var(--space-2);">
            <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto;">
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table class="table" style="margin: 0; border: none; box-shadow: none;">
            <thead>
                <tr>
                    <th style="border-left: none;">Event</th>
                    <th>Penyelenggara</th>
                    <th>Jadwal</th>
                    <th>Tiket</th>
                    <th style="border-right: none; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td style="border-left: none;">
                            <div style="display: flex; align-items: center; gap: var(--space-3);">
                                @if($event->image)
                                    <img src="{{ Storage::url($event->image) }}" style="width: 40px; height: 40px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--slate-700);">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background-color: var(--slate-800); border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; color: var(--slate-500);">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div style="max-width: 200px;">
                                    <div style="font-weight: 600; color: var(--slate-0); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $event->title }}">{{ $event->title }}</div>
                                    <div class="caption" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">{{ $event->location }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="#" style="color: var(--purple-500); text-decoration: none; font-weight: 600;">{{ $event->organization->name }}</a>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: var(--slate-0);">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</div>
                            <div class="caption">{{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}</div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: var(--slate-800); color: var(--slate-200); border-color: var(--slate-700);">{{ $event->ticketTypes()->count() }} Tipe</span>
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: var(--space-2);">
                                <!-- Preview link (opens event details in new tab) -->
                                <a href="{{ route('events.show', $event) }}" target="_blank" class="btn btn-secondary" style="height: 32px; padding: 0 var(--space-2); font-size: 13px; text-decoration: none;" title="Pratinjau Halaman Publik">
                                    Pratinjau
                                </a>
                                
                                @if ($status === 'pending')
                                    <form action="{{ route('admin.event-approvals.approve', $event) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-primary" style="height: 32px; padding: 0 var(--space-2); font-size: 13px;" onclick="return confirm('Setujui event ini?')">
                                            Setujui
                                        </button>
                                    </form>
                                    <button onclick="rejectEvent({{ $event->id }})" class="btn btn-destructive" style="height: 32px; padding: 0 var(--space-2); font-size: 13px;">
                                        Tolak
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-8); border: none;">
                            <p class="body" style="color: var(--slate-400); margin: 0;">Tidak ada event dalam antrean persetujuan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if ($events->hasPages())
        <div style="padding: var(--space-4); border-top: 1px solid var(--slate-700);">
            {{ $events->links() }}
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="modal-backdrop" style="display: none;">
    <div class="modal">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--slate-700); padding-bottom: var(--space-4); margin-bottom: var(--space-4);">
            <h3 class="h3" style="margin: 0; color: var(--error-border); display: flex; align-items: center; gap: 8px;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Tolak Event
            </h3>
            <button onclick="closeRejectModal()" style="background: none; border: none; color: var(--slate-400); cursor: pointer;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="reject-form" action="" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label class="label">Alasan Penolakan <span style="color: var(--error-border);">*</span></label>
                <textarea name="notes" rows="4" required minlength="10"
                    class="form-control" style="border-color: var(--error-border);"
                    placeholder="Jelaskan alasan detail mengapa event ini ditolak..."></textarea>
                <p class="caption" style="margin-top: 4px;">Alasan ini akan dikirimkan ke penyelenggara agar mereka dapat memperbaiki pengajuannya.</p>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: var(--space-3); margin-top: var(--space-6);">
                <button type="button" onclick="closeRejectModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-destructive">Konfirmasi Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function rejectEvent(id) {
        const modal = document.getElementById('reject-modal');
        const form = document.getElementById('reject-form');
        form.action = `/admin/event-approvals/${id}/reject`;
        modal.style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').style.display = 'none';
        document.getElementById('reject-form').reset();
    }
</script>
@endsection
