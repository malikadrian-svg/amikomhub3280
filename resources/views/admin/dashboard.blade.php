@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
    <!-- Header -->
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-10); flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h1 class="display" style="margin-bottom: var(--space-2);">DASHBOARD RINGKASAN</h1>
            <p class="body-lg" style="color: var(--slate-200);">Selamat datang kembali, {{ auth()->user()->name ?? 'Admin' }}!</p>
        </div>
        <div style="display: flex; align-items: center; gap: var(--space-4);">
            <div style="text-align: right;">
                <p class="h6" style="margin: 0; color: var(--slate-0);">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="caption" style="margin: 0; color: var(--slate-400);">{{ (auth()->user()->role ?? 'admin') === 'admin' ? 'PENYELENGGARA UTAMA' : 'USER' }}</p>
            </div>
            <div style="width: 48px; height: 48px; background-color: var(--slate-0); border: 1px solid var(--slate-700); box-shadow: var(--shadow-hard-sm); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=ffb800&color=0a0a0a" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </header>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--space-6); margin-bottom: var(--space-10);">
        <div class="card" style="padding: var(--space-6);">
            <div style="width: 48px; height: 48px; background-color: var(--purple-500); color: #ffffff; border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">TOTAL PENDAPATAN</p>
            <h3 class="h2" style="margin: 0; color: var(--slate-0);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>

        <div class="card" style="padding: var(--space-6);">
            <div style="width: 48px; height: 48px; background-color: var(--feedback-success); color: var(--slate-0); border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
            </div>
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">TIKET TERJUAL</p>
            <h3 class="h2" style="margin: 0; color: var(--slate-0);">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
        </div>

        <div class="card" style="padding: var(--space-6);">
            <div style="width: 48px; height: 48px; background-color: var(--feedback-warning); color: #ffffff; border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">EVENT AKTIF</p>
            <h3 class="h2" style="margin: 0; color: var(--slate-0);">{{ $activeEvents }} Event</h3>
        </div>

        <div class="card" style="padding: var(--space-6);">
            <div style="width: 48px; height: 48px; background-color: var(--feedback-error); color: var(--slate-0); border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">PESANAN PENDING</p>
            <h3 class="h2" style="margin: 0; color: var(--slate-0);">{{ $pendingOrders }} Pesanan</h3>
        </div>

        {{-- Review stats card --}}
        <div class="card" style="padding: var(--space-6);">
            <div style="width: 48px; height: 48px; background-color: #d97706; color: #ffffff; border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#ffffff" stroke="#ffffff" stroke-width="1.5">
                    <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                </svg>
            </div>
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">RATA-RATA RATING</p>
            <h3 class="h2" style="margin: 0; color: #f59e0b;">{{ $avgRating > 0 ? '★ ' . number_format($avgRating, 1) : '—' }}</h3>
            <p class="caption" style="color: var(--slate-400); margin: var(--space-1) 0 0 0;">
                dari {{ number_format($totalReviews) }} ulasan
                @if($pendingReviews > 0)
                    · <span style="color: var(--feedback-error);">{{ $pendingReviews }} disembunyikan</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Latest Sales Table -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: var(--space-6);">
        <div style="padding: var(--space-6) var(--space-8); border-bottom: 2px solid var(--slate-600); display: flex; justify-content: space-between; align-items: center;">
            <h3 class="h3" style="margin: 0;">TRANSAKSI TERAKHIR</h3>
            <a href="{{ route('admin.transactions.index') }}" class="body" style="color: var(--purple-500); font-weight: 700; text-decoration: underline;">Lihat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="table" style="margin: 0; border: none; box-shadow: none;">
                <thead>
                    <tr>
                        <th style="border-left: none;">Tgl Transaksi</th>
                        <th>Pembeli</th>
                        <th>Event</th>
                        <th>Status</th>
                        <th style="border-right: none; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $trx)
                        <tr>
                            <td style="border-left: none;">
                                <span style="display: block; font-weight: 600;">{{ $trx->created_at->format('d M y - H:i') }}</span>
                                <span class="caption" style="color: var(--slate-400);">{{ $trx->order_id }}</span>
                            </td>
                            <td>
                                <span style="display: block; font-weight: 700; text-transform: uppercase;">{{ $trx->customer_name }}</span>
                                <span class="caption" style="color: var(--slate-400);">{{ $trx->customer_email }}</span>
                            </td>
                            <td style="font-weight: 500;">{{ $trx->event->title ?? '-' }}</td>
                            <td>
                                @if($trx->status === 'settlement' || $trx->status === 'success')
                                    <span class="badge" style="background-color: var(--feedback-success); color: var(--slate-0); border-color: var(--slate-0);">SUCCESS</span>
                                @elseif($trx->status === 'pending')
                                    <span class="badge" style="background-color: var(--feedback-warning); color: #ffffff; border-color: #ffffff;">PENDING</span>
                                @else
                                    <span class="badge" style="background-color: var(--feedback-error); color: var(--slate-0); border-color: var(--slate-0);">{{ strtoupper($trx->status) }}</span>
                                @endif
                            </td>
                            <td style="border-right: none; text-align: right; font-weight: 700; color: var(--purple-500); font-size: 18px;">
                                Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: var(--space-10); border: none;">
                                <p class="body-lg" style="color: var(--slate-400);">Belum ada transaksi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Reviews Table --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: var(--space-6) var(--space-8); border-bottom: 2px solid var(--slate-600); display: flex; justify-content: space-between; align-items: center;">
            <h3 class="h3" style="margin: 0;">ULASAN TERBARU</h3>
            <a href="{{ route('admin.reviews.index') }}" class="body" style="color: var(--purple-500); font-weight: 700; text-decoration: underline;">Lihat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="table" style="margin: 0; border: none; box-shadow: none;">
                <thead>
                    <tr>
                        <th style="border-left: none;">Pengguna</th>
                        <th>Event</th>
                        <th>Rating</th>
                        <th style="border-right: none;">Ulasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReviews as $review)
                        <tr>
                            <td style="border-left: none;">
                                <div style="display: flex; align-items: center; gap: var(--space-2);">
                                    @if($review->user->avatar)
                                        <img src="{{ $review->user->avatar }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid var(--purple-500); flex-shrink: 0;">
                                    @else
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--purple-500); color: #fff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 13px; flex-shrink: 0;">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span style="display: block; font-weight: 700; font-size: 13px;">{{ $review->user->name ?? '—' }}</span>
                                        <span class="caption" style="color: var(--slate-400);">{{ $review->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size: 13px; font-weight: 500; max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $review->event->title ?? '—' }}
                            </td>
                            <td>
                                <span style="font-family: 'IBM Plex Mono', monospace; font-weight: 700; color: #f59e0b; font-size: 14px;">
                                    @for($s = 1; $s <= $review->rating; $s++)★@endfor
                                </span>
                                <span class="caption" style="color: var(--slate-400);"> {{ $review->rating }}/5</span>
                            </td>
                            <td style="border-right: none; max-width: 280px;">
                                @if($review->title)
                                    <p style="font-weight: 700; font-size: 13px; margin: 0 0 2px 0; color: var(--slate-0);">{{ $review->title }}</p>
                                @endif
                                <p class="caption" style="color: var(--slate-200); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 260px;">{{ $review->body }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: var(--space-8); border: none;">
                                <p class="body" style="color: var(--slate-400); margin: 0;">Belum ada ulasan yang masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
