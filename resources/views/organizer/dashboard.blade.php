@extends('layouts.organizer')

@section('content')
<div style="margin-bottom: var(--space-8);">
    <h1 class="display" style="margin-bottom: var(--space-1); color: var(--slate-900);">Dashboard Penyelenggara</h1>
    <p class="body" style="color: var(--slate-500);">Selamat datang kembali. Ini adalah ringkasan kinerja event Anda.</p>
</div>

{{-- Revenue Overview --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-6); margin-bottom: var(--space-6);">
    {{-- Gross Revenue --}}
    <div class="card" style="background: linear-gradient(135deg, var(--purple-600), var(--purple-500)); border: none; color: #fff; padding: var(--space-6);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-4);">
            <div>
                <p style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.8; margin-bottom: var(--space-1);">Pendapatan Kotor</p>
                <h3 style="font-size: 28px; font-weight: 700; margin: 0;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p style="font-size: 12px; opacity: 0.75; margin: 0;">Penjualan tiket sebelum potongan</p>
    </div>

    {{-- Platform Commission --}}
    <div class="card" style="padding: var(--space-6);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-4);">
            <div>
                <p class="caption" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-500); margin-bottom: var(--space-1);">Potongan Platform</p>
                <h3 style="font-size: 24px; font-weight: 700; margin: 0; color: var(--feedback-error);">- Rp {{ number_format($totalCommission, 0, ',', '.') }}</h3>
            </div>
            <div style="width: 42px; height: 42px; background: rgba(220, 38, 38, 0.08); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--feedback-error);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
        </div>
        <p class="caption" style="color: var(--feedback-error); margin: 0;">Biaya layanan AmikomHub</p>
    </div>

    {{-- Net Revenue --}}
    <div class="card" style="padding: var(--space-6);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-4);">
            <div>
                <p class="caption" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-500); margin-bottom: var(--space-1);">Pendapatan Bersih</p>
                <h3 style="font-size: 24px; font-weight: 700; margin: 0; color: var(--feedback-success);">Rp {{ number_format($netRevenue, 0, ',', '.') }}</h3>
            </div>
            <div style="width: 42px; height: 42px; background: rgba(22, 163, 74, 0.08); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--feedback-success);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="caption" style="color: var(--feedback-success); margin: 0;">Total penghasilan yang akan dicairkan</p>
    </div>
</div>

{{-- Other Metrics --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-6); margin-bottom: var(--space-8);">
    {{-- Tickets Sold --}}
    <div class="card" style="padding: var(--space-6);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3);">
            <div>
                <p class="caption" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-500); margin-bottom: var(--space-1);">Tiket Terjual</p>
                <h3 class="h2" style="margin: 0; color: var(--slate-900);">{{ number_format($totalTicketsSold, 0, ',', '.') }}</h3>
            </div>
            <div style="width: 42px; height: 42px; background: var(--purple-50); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--purple-600);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
        </div>
        <p class="caption" style="color: var(--slate-400); margin: 0;">Semua event</p>
    </div>

    {{-- Total Orders --}}
    <div class="card" style="padding: var(--space-6);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3);">
            <div>
                <p class="caption" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-500); margin-bottom: var(--space-1);">Total Pesanan</p>
                <h3 class="h2" style="margin: 0; color: var(--slate-900);">{{ number_format($totalOrders, 0, ',', '.') }}</h3>
            </div>
            <div style="width: 42px; height: 42px; background: var(--purple-50); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--purple-600);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
        <p class="caption" style="color: var(--slate-400); margin: 0;">Transaksi berhasil</p>
    </div>

    {{-- Total Events --}}
    <div class="card" style="padding: var(--space-6);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3);">
            <div>
                <p class="caption" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-500); margin-bottom: var(--space-1);">Total Event</p>
                <h3 class="h2" style="margin: 0; color: var(--slate-900);">{{ number_format($totalEvents, 0, ',', '.') }}</h3>
            </div>
            <div style="width: 42px; height: 42px; background: var(--purple-50); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--purple-600);">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <p class="caption" style="color: var(--slate-400); margin: 0;">Aktif & Selesai</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-8);">

    {{-- Upcoming Events --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-5) var(--space-6); border-bottom: 1px solid var(--slate-100); margin-bottom: 0;">
            <h3 class="h4" style="margin: 0; color: var(--slate-900);">Event Mendatang</h3>
            <a href="{{ route('organizer.events.index', request()->route('organization')) }}" style="font-size: 13px; color: var(--purple-600); text-decoration: none; font-weight: 600;">Lihat Semua</a>
        </div>
        <div>
            @forelse ($upcomingEvents as $event)
                <div style="padding: var(--space-4) var(--space-6); display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--slate-100); transition: background-color 0.15s;">
                    <div style="display: flex; align-items: center; gap: var(--space-4);">
                        <div style="width: 48px; height: 48px; background: var(--purple-50); border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid var(--purple-100);">
                            <span style="font-size: 10px; color: var(--purple-500); font-weight: 600; text-transform: uppercase;">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</span>
                            <span style="font-size: 18px; font-weight: 700; color: var(--purple-700); line-height: 1;">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                        </div>
                        <div>
                            <h4 style="font-size: 14px; font-weight: 600; color: var(--slate-900); margin: 0 0 2px 0;">{{ $event->title }}</h4>
                            <p style="font-size: 12px; color: var(--slate-400); margin: 0;">{{ $event->location }}</p>
                        </div>
                    </div>
                    @if ($event->status === 'pending')
                        <span class="badge" style="background: rgba(234, 179, 8, 0.1); color: #854d0e; border-color: rgba(234, 179, 8, 0.3);">Menunggu</span>
                    @elseif ($event->status === 'approved' || $event->status === 'published')
                        <span class="badge" style="background: rgba(22, 163, 74, 0.08); color: #166534; border-color: rgba(22, 163, 74, 0.2);">Aktif</span>
                    @else
                        <span class="badge">{{ ucfirst($event->status) }}</span>
                    @endif
                </div>
            @empty
                <div style="padding: var(--space-8); text-align: center; color: var(--slate-400);">
                    Belum ada event mendatang.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--space-5) var(--space-6); border-bottom: 1px solid var(--slate-100); margin-bottom: 0;">
            <h3 class="h4" style="margin: 0; color: var(--slate-900);">Pesanan Terbaru</h3>
            <a href="#" style="font-size: 13px; color: var(--purple-600); text-decoration: none; font-weight: 600;">Lihat Semua</a>
        </div>
        <div>
            @forelse ($recentOrders as $order)
                <div style="padding: var(--space-4) var(--space-6); display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--slate-100);">
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: var(--slate-900); margin: 0 0 2px 0;">{{ $order->user->name }}</p>
                        <p style="font-size: 12px; color: var(--slate-400); margin: 0; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $order->event->title }}">{{ $order->event->title }}</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 14px; font-weight: 700; color: var(--purple-600); margin: 0 0 2px 0;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p style="font-size: 11px; color: var(--slate-400); margin: 0;">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div style="padding: var(--space-8); text-align: center; color: var(--slate-400);">
                    Belum ada pesanan masuk.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
