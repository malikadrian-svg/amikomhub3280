@extends('layouts.organizer')

@section('content')
<div style="margin-bottom: var(--space-8); display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h1 class="display" style="margin-bottom: var(--space-1); color: #1e293b;">Manajemen Event</h1>
        <p class="body" style="color: #6b7280;">Kelola event, tiket, dan status persetujuan.</p>
    </div>
    <a href="{{ route('organizer.events.create', request()->route('organization')) }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: var(--space-2);">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Event Baru
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="table" style="margin: 0; border: none; box-shadow: none;">
            <thead>
                <tr>
                    <th style="border-left: none;">Nama Event</th>
                    <th>Kategori</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th style="border-right: none; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td style="border-left: none;">
                            <div style="display: flex; align-items: center; gap: var(--space-3);">
                                @if($event->image)
                                    <img src="{{ Storage::url($event->image) }}" style="width: 48px; height: 48px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid #e2e8f0;" alt="{{ $event->title }}">
                                @else
                                    <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--purple-50); border: 1px solid var(--purple-100); display: flex; align-items: center; justify-content: center; color: var(--purple-400);">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 600; color: #1e293b; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $event->title }}">{{ $event->title }}</div>
                                    <div class="caption" style="color: var(--slate-400); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $event->location }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $event->category->name }}</td>
                        <td>
                            <div style="font-weight: 500; color: #334155;">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, H:i') }}</div>
                            <div class="caption" style="color: var(--slate-400);">s/d {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y, H:i') }}</div>
                        </td>
                        <td>
                            @if ($event->status === 'draft')
                                <span class="badge">Draf</span>
                            @elseif ($event->status === 'pending')
                                <span class="badge" style="background: rgba(234, 179, 8, 0.1); color: #854d0e; border-color: rgba(234, 179, 8, 0.3);">Menunggu Review</span>
                            @elseif ($event->status === 'approved' || $event->status === 'published')
                                <span class="badge" style="background: rgba(22, 163, 74, 0.08); color: #166534; border-color: rgba(22, 163, 74, 0.2);">Aktif</span>
                            @elseif ($event->status === 'rejected')
                                <span class="badge" style="background: rgba(220, 38, 38, 0.08); color: #991b1b; border-color: rgba(220, 38, 38, 0.2);">Ditolak</span>
                            @else
                                <span class="badge">{{ ucfirst($event->status) }}</span>
                            @endif
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <a href="{{ route('organizer.events.show', [request()->route('organization'), $event]) }}" style="color: var(--purple-600); font-weight: 600; text-decoration: none; font-size: 14px;">Kelola →</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-10); border: none;">
                            <div style="color: var(--slate-400);">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto var(--space-3); opacity: 0.4;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="body" style="margin: 0;">Belum ada event yang dibuat.</p>
                                <a href="{{ route('organizer.events.create', request()->route('organization')) }}" class="btn btn-primary" style="margin-top: var(--space-4); display: inline-flex; align-items: center; gap: var(--space-2);">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Buat Event Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($events->hasPages())
        <div style="padding: var(--space-4) var(--space-6); border-top: 1px solid #f1f5f9;">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
