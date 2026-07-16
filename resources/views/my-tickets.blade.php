@extends('layouts.app')

@section('title', 'Tiket Saya - AmikomEventHub')

@section('content')
<main style="max-width: 900px; margin: 0 auto; padding: var(--space-8) var(--space-4);">

    {{-- Page header --}}
    <div style="margin-bottom: var(--space-6);">
        <div style="display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-2);">
            {{-- User avatar --}}
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="Avatar"
                     style="width: 48px; height: 48px; border-radius: 50%; border: 2px solid var(--purple-500); object-fit: cover;">
            @else
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--purple-500); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 18px; border: 2px solid var(--purple-700);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h1 class="h2" style="margin: 0 0 2px 0;">TIKET SAYA</h1>
                <p class="caption" style="color: var(--slate-400); margin: 0;">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <p class="body" style="color: var(--slate-400); margin: var(--space-3) 0 0 0;">
            Semua tiket yang telah Anda beli akan tampil di sini.
        </p>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div style="background-color: #dcfce7; border: 2px solid #22c55e; padding: var(--space-4); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3);">
            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            <span style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 700; color: #15803d;">{{ session('success') }}</span>
        </div>
    @endif

    @if($orders->isEmpty())
        {{-- Empty state --}}
        <div class="card" style="text-align: center; padding: var(--space-10);">
            <svg width="64" height="64" fill="none" stroke="var(--slate-600)" stroke-width="1.5" stroke-linecap="square" viewBox="0 0 24 24" style="margin: 0 auto var(--space-4) auto; display: block;">
                <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
            <h3 class="h3" style="margin: 0 0 var(--space-2) 0; color: var(--slate-400);">BELUM ADA TIKET</h3>
            <p class="body" style="color: var(--slate-400); margin: 0 0 var(--space-6) 0;">
                Anda belum memiliki tiket yang lunas. Temukan event seru dan beli tiketnya sekarang!
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="display: inline-flex;">
                JELAJAHI EVENT
            </a>
        </div>
    @else
        {{-- Ticket grid --}}
        <div style="display: flex; flex-direction: column; gap: var(--space-4);">
            @foreach($orders as $order)
            @php
                $event       = $order->event;
                $userReview  = $userReviews->get($event->id);
                $isFinished  = $event->isFinished();
                $isReviewable = $event->isReviewable();
                $canReview   = $isReviewable && !$userReview
                               && Auth::user()->canReviewEvent($event);
            @endphp
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-wrap: wrap;">

                {{-- Left: color strip --}}
                <div style="width: 8px; background-color: {{ $isFinished ? 'var(--slate-600)' : 'var(--purple-500)' }}; flex-shrink: 0;"></div>

                {{-- Center: Ticket info --}}
                <div style="flex: 1; padding: var(--space-4) var(--space-4);">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: var(--space-2);">
                        <div>
                            <span class="badge" style="margin-bottom: var(--space-2); display: inline-block;">
                                {{ strtoupper($event->category->name ?? 'EVENT') }}
                            </span>
                            <h3 class="h4" style="margin: 0 0 var(--space-2) 0; color: var(--slate-0);">
                                {{ $event->title }}
                            </h3>
                            <div style="display: flex; flex-wrap: wrap; gap: var(--space-3);">
                                <span class="caption" style="color: var(--slate-400); display: flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                                <span class="caption" style="color: var(--slate-400); display: flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $event->location }}
                                </span>
                            </div>
                        </div>
                        {{-- Status badge --}}
                        <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: var(--space-1);">
                            <span style="display: inline-block; padding: 4px 12px; font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; letter-spacing: 0.05em; background-color: #dcfce7; color: #15803d; border: 1.5px solid #22c55e;">
                                LUNAS
                            </span>
                            @if($isFinished)
                                <span style="display: inline-block; padding: 3px 8px; font-family: 'IBM Plex Mono', monospace; font-size: 10px; font-weight: 700; background-color: var(--slate-700); color: var(--slate-400); border: 1px solid var(--slate-600);">
                                    SELESAI
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div style="border-top: 1px dashed var(--slate-700); margin: var(--space-3) 0;"></div>

                    {{-- Footer: Order ID + Price + Actions --}}
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: var(--space-2);">
                        <div>
                            <p class="caption" style="margin: 0; color: var(--slate-400);">ORDER ID</p>
                            <p style="margin: 0; font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 700; color: var(--slate-0);">
                                {{ $order->order_number }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <p class="caption" style="margin: 0 0 4px 0; color: var(--slate-400);">TOTAL BAYAR</p>
                            <p class="h5" style="margin: 0; color: var(--purple-500);">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Action buttons --}}
                        <div style="display: flex; gap: var(--space-2); flex-wrap: wrap; align-items: center;">
                            {{-- Review CTA --}}
                            @if($canReview)
                                <a href="{{ route('events.show', $event) }}#review-list"
                                   class="btn"
                                   style="padding: var(--space-1) var(--space-3); font-size: 12px; background-color: #f59e0b; color: #0a0a0a; border: 2px solid #d97706; display: flex; align-items: center; gap: 5px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#0a0a0a" stroke-width="1.5">
                                        <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                    </svg>
                                    TULIS ULASAN
                                </a>
                            @elseif($userReview)
                                <a href="{{ route('events.show', $event) }}#review-list"
                                   class="btn"
                                   style="padding: var(--space-1) var(--space-3); font-size: 12px; background-color: var(--slate-700); color: var(--slate-200); border: 2px solid var(--slate-600); display: flex; align-items: center; gap: 5px;">
                                    ✓ SUDAH DIULAS
                                </a>
                            @endif

                            <a href="{{ route('ticket', $order->order_number) }}" class="btn btn-primary" style="padding: var(--space-1) var(--space-3); font-size: 13px;">
                                LIHAT TIKET
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="margin-left: 6px;"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Summary --}}
        <p class="caption" style="text-align: center; color: var(--slate-400); margin-top: var(--space-6);">
            Menampilkan {{ $orders->count() }} tiket lunas
        </p>
    @endif

</main>
@endsection
