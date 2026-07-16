@extends('layouts.admin')

@section('title', 'Manajemen Ulasan')

@section('content')

    {{-- Header --}}
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-8); flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h1 class="display" style="margin-bottom: var(--space-2);">MANAJEMEN ULASAN</h1>
            <p class="body" style="color: var(--slate-200);">Moderasi dan kelola semua ulasan dari pengguna.</p>
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
        <div style="background-color: #dcfce7; border: 2px solid #22c55e; padding: var(--space-4); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3);">
            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            <span style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 700; color: #15803d;">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ── Stats Cards ─────────────────────────────────────────────────────── --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-4); margin-bottom: var(--space-8);">
        <div class="card" style="padding: var(--space-5);">
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">TOTAL ULASAN</p>
            <h3 class="h2" style="margin: 0; color: var(--slate-0);">{{ number_format($stats['total']) }}</h3>
        </div>
        <div class="card" style="padding: var(--space-5);">
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">DITAMPILKAN</p>
            <h3 class="h2" style="margin: 0; color: #16a34a;">{{ number_format($stats['approved']) }}</h3>
        </div>
        <div class="card" style="padding: var(--space-5);">
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">DISEMBUNYIKAN</p>
            <h3 class="h2" style="margin: 0; color: #dc2626;">{{ number_format($stats['hidden']) }}</h3>
        </div>
        <div class="card" style="padding: var(--space-5);">
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">RATA-RATA RATING</p>
            <h3 class="h2" style="margin: 0; color: #f59e0b;">
                {{ $stats['avg'] > 0 ? '★ ' . number_format($stats['avg'], 1) : '—' }}
            </h3>
        </div>
    </div>

    {{-- ── Top Lists ────────────────────────────────────────────────────────── --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6); margin-bottom: var(--space-8);">

        {{-- Top Events --}}
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-600);">
                <h3 class="h4" style="margin: 0;">TOP EVENT</h3>
            </div>
            <div style="padding: var(--space-4) var(--space-6);">
                @forelse($topEvents as $i => $ev)
                    <div style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2) 0; border-bottom: 1px solid var(--slate-700);">
                        <span style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; color: var(--slate-400); min-width: 20px;">#{{ $i+1 }}</span>
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-weight: 700; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--slate-0);">{{ $ev->title }}</p>
                            <p class="caption" style="margin: 0; color: var(--slate-400);">{{ $ev->approved_reviews_count }} ulasan</p>
                        </div>
                        <span style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 700; color: #f59e0b; flex-shrink: 0;">
                            ★ {{ number_format($ev->approved_reviews_avg_rating, 1) }}
                        </span>
                    </div>
                @empty
                    <p class="body" style="color: var(--slate-400); margin: var(--space-3) 0;">Belum ada data cukup.</p>
                @endforelse
            </div>
        </div>

        {{-- Top Partners --}}
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-600);">
                <h3 class="h4" style="margin: 0;">TOP PARTNER</h3>
            </div>
            <div style="padding: var(--space-4) var(--space-6);">
                @forelse($topPartners as $i => $pt)
                    <div style="display: flex; align-items: center; gap: var(--space-3); padding: var(--space-2) 0; border-bottom: 1px solid var(--slate-700);">
                        <span style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; color: var(--slate-400); min-width: 20px;">#{{ $i+1 }}</span>
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-weight: 700; font-size: 13px; color: var(--slate-0);">{{ $pt->name }}</p>
                            <p class="caption" style="margin: 0; color: var(--slate-400);">{{ $pt->approved_reviews_count }} ulasan</p>
                        </div>
                        <span style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 700; color: #f59e0b; flex-shrink: 0;">
                            ★ {{ number_format($pt->approved_reviews_avg_rating, 1) }}
                        </span>
                    </div>
                @empty
                    <p class="body" style="color: var(--slate-400); margin: var(--space-3) 0;">Belum ada data cukup.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Filter Bar ───────────────────────────────────────────────────────── --}}
    <div class="card" style="padding: var(--space-5); margin-bottom: var(--space-6);">
        <form method="GET" action="{{ route('admin.reviews.index') }}"
              style="display: flex; flex-wrap: wrap; gap: var(--space-4); align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="label" style="display: block; margin-bottom: var(--space-1);">CARI</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama, email, event, atau isi ulasan..."
                       class="input" style="width: 100%; box-sizing: border-box;">
            </div>
            <div style="min-width: 150px;">
                <label class="label" style="display: block; margin-bottom: var(--space-1);">STATUS</label>
                <select name="status" class="input" style="width: 100%;">
                    <option value="">Semua Status</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Ditampilkan</option>
                    <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Disembunyikan</option>
                </select>
            </div>
            <div style="min-width: 120px;">
                <label class="label" style="display: block; margin-bottom: var(--space-1);">RATING</label>
                <select name="rating" class="input" style="width: 100%;">
                    <option value="">Semua Rating</option>
                    @for ($s = 5; $s >= 1; $s--)
                        <option value="{{ $s }}" {{ request('rating') == $s ? 'selected' : '' }}>{{ $s }} Bintang</option>
                    @endfor
                </select>
            </div>
            <div style="display: flex; gap: var(--space-2);">
                <button type="submit" class="btn btn-primary">FILTER</button>
                <a href="{{ route('admin.reviews.index') }}" class="btn" style="background-color: var(--slate-700); color: var(--slate-0);">RESET</a>
            </div>
        </form>
    </div>

    {{-- ── Reviews Table ────────────────────────────────────────────────────── --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-600); display: flex; justify-content: space-between; align-items: center;">
            <h3 class="h4" style="margin: 0;">DAFTAR ULASAN</h3>
            <span class="caption" style="color: var(--slate-400);">{{ $reviews->total() }} ulasan ditemukan</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="table" style="margin: 0; border: none; box-shadow: none;">
                <thead>
                    <tr>
                        <th style="border-left: none;">Pengguna</th>
                        <th>Event</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Tgl</th>
                        <th>Status</th>
                        <th style="border-right: none; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr style="{{ !$review->is_approved ? 'opacity: 0.6;' : '' }}">
                            <td style="border-left: none;">
                                <span style="display: block; font-weight: 700; font-size: 13px;">{{ $review->user->name ?? '—' }}</span>
                                <span class="caption" style="color: var(--slate-400);">{{ $review->user->email ?? '' }}</span>
                            </td>
                            <td>
                                <span style="font-size: 13px; font-weight: 500; display: block; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $review->event->title ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-family: 'IBM Plex Mono', monospace; font-size: 14px; font-weight: 700; color: #f59e0b;">
                                    @for ($s = 1; $s <= $review->rating; $s++)★@endfor
                                </span>
                                <span class="caption" style="color: var(--slate-400);"> {{ $review->rating }}/5</span>
                            </td>
                            <td style="max-width: 240px;">
                                @if($review->title)
                                    <p style="font-weight: 700; font-size: 13px; margin: 0 0 2px 0; color: var(--slate-0);">{{ $review->title }}</p>
                                @endif
                                <p class="caption" style="color: var(--slate-200); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px;">{{ $review->body }}</p>
                            </td>
                            <td>
                                <span class="caption" style="color: var(--slate-400);">
                                    {{ $review->created_at->format('d M Y') }}<br>
                                    {{ $review->created_at->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1.5px solid #22c55e; padding: 4px 8px; font-weight: 700; font-size: 11px;">TAMPIL</span>
                                @else
                                    <span class="badge" style="background-color: #fee2e2; color: #b91c1c; border: 1.5px solid #ef4444; padding: 4px 8px; font-weight: 700; font-size: 11px;">HIDDEN</span>
                                @endif
                            </td>
                            <td style="border-right: none; text-align: right; white-space: nowrap;">
                                <div style="display: flex; gap: var(--space-2); justify-content: flex-end;">
                                    {{-- Toggle visibility --}}
                                    <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}" style="margin: 0;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn"
                                            style="padding: var(--space-1) var(--space-3); font-size: 12px; background-color: {{ $review->is_approved ? '#475569' : '#16a34a' }}; color: #ffffff; border: 2px solid {{ $review->is_approved ? '#334155' : '#15803d' }}; font-weight: 700;">
                                            {{ $review->is_approved ? 'SEMBUNYIKAN' : 'TAMPILKAN' }}
                                        </button>
                                    </form>
                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                          onsubmit="return confirm('Hapus ulasan ini secara permanen?')" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn"
                                            style="padding: var(--space-1) var(--space-3); font-size: 12px; background-color: #dc2626; color: #ffffff; border: 2px solid #b91c1c; font-weight: 700;">
                                            HAPUS
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: var(--space-10); border: none;">
                                <p class="body-lg" style="color: var(--slate-400);">Tidak ada ulasan yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reviews->hasPages())
            <div style="padding: var(--space-4) var(--space-6); border-top: 1px solid var(--slate-700);">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

@endsection
