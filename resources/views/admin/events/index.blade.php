@extends('layouts.admin')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-8); flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h2 class="h2" style="margin-bottom: var(--space-2);">MANAJEMEN EVENT</h2>
            <p class="body" style="color: var(--ink-400);">Kelola semua acara, tiket, dan kapasitas dengan mudah.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: var(--space-2);">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            TAMBAH EVENT
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: var(--feedback-success); color: var(--ink-0); padding: var(--space-4); border: 2px solid var(--ink-950); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3); box-shadow: 4px 4px 0 var(--ink-950);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span style="font-weight: 700;">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="table" style="margin: 0; border: none; box-shadow: none;">
                <thead>
                    <tr>
                        <th style="border-left: none; width: 64px; text-align: center;">NO</th>
                        <th>JUDUL EVENT</th>
                        <th>KATEGORI</th>
                        <th>TANGGAL & WAKTU</th>
                        <th style="border-right: none; text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $index => $event)
                    <tr>
                        <td style="border-left: none; font-weight: 700; color: var(--ink-400); text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--space-4);">
                                <div style="width: 48px; height: 60px; border: 2px solid var(--ink-700); box-shadow: 2px 2px 0 var(--ink-950); background-color: var(--ink-900); overflow: hidden;">
                                    <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                                         ? asset('storage/' . $event->poster_path)
                                         : 'https://placehold.co/16x20' }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                    <p class="body" style="font-weight: 700; color: var(--ink-0); margin: 0; text-transform: uppercase;">{{ $event->title }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: var(--ink-950); color: var(--ink-0); border-color: var(--ink-700);">
                                {{ $event->category->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--ink-200); font-weight: 500; font-size: 14px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}
                            </div>
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: var(--space-2);">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn" style="padding: var(--space-2); background-color: var(--ink-800); color: var(--ink-0); border: 2px solid var(--ink-700);">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Anda yakin ingin menghapus data acara ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="padding: var(--space-2); background-color: transparent; border: 2px solid var(--error-border); color: var(--error-border);">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-10); border: none;">
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <div style="width: 80px; height: 80px; border: 4px solid var(--ink-700); background-color: var(--ink-950); display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4); box-shadow: 4px 4px 0 var(--ink-950);">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" style="color: var(--ink-400);">
                                        <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                </div>
                                <p class="h4" style="margin-bottom: var(--space-2);">BELUM ADA EVENT</p>
                                <p class="body" style="color: var(--ink-400);">Mulai dengan menambahkan event pertama.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="padding: var(--space-4) var(--space-6); border-top: var(--border-width-default) solid var(--ink-700); background-color: var(--ink-900);">
            <p class="caption" style="font-weight: 700; color: var(--ink-200);">TOTAL: {{ $events->count() }} EVENT</p>
        </div>
    </div>
</div>
@endsection
