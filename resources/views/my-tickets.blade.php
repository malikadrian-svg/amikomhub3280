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

    @if($transactions->isEmpty())
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
            @foreach($transactions as $transaction)
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-wrap: wrap;">

                {{-- Left: Event poster strip --}}
                <div style="width: 8px; background-color: var(--purple-500); flex-shrink: 0;"></div>

                {{-- Center: Ticket info --}}
                <div style="flex: 1; padding: var(--space-4) var(--space-4);">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: var(--space-2);">
                        <div>
                            <span class="badge" style="margin-bottom: var(--space-2); display: inline-block;">
                                {{ strtoupper($transaction->event->category->name ?? 'EVENT') }}
                            </span>
                            <h3 class="h4" style="margin: 0 0 var(--space-2) 0; color: var(--slate-0);">
                                {{ $transaction->event->title }}
                            </h3>
                            <div style="display: flex; flex-wrap: wrap; gap: var(--space-3);">
                                <span class="caption" style="color: var(--slate-400); display: flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($transaction->event->date)->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                                <span class="caption" style="color: var(--slate-400); display: flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $transaction->event->location }}
                                </span>
                            </div>
                        </div>
                        {{-- Status badge --}}
                        <div style="text-align: right;">
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                font-family: 'IBM Plex Mono', monospace;
                                font-size: 11px;
                                font-weight: 700;
                                letter-spacing: 0.05em;
                                background-color: {{ in_array(strtolower($transaction->status), ['success','settlement','capture']) ? '#dcfce7' : '#fef9c3' }};
                                color: {{ in_array(strtolower($transaction->status), ['success','settlement','capture']) ? '#15803d' : '#854d0e' }};
                                border: 1.5px solid {{ in_array(strtolower($transaction->status), ['success','settlement','capture']) ? '#22c55e' : '#eab308' }};
                            ">
                                LUNAS
                            </span>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div style="border-top: 1px dashed var(--slate-700); margin: var(--space-3) 0;"></div>

                    {{-- Footer: Order ID + Price + View Button --}}
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: var(--space-2);">
                        <div>
                            <p class="caption" style="margin: 0; color: var(--slate-400);">ORDER ID</p>
                            <p style="margin: 0; font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 700; color: var(--slate-0);">
                                {{ $transaction->order_id }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <p class="caption" style="margin: 0 0 4px 0; color: var(--slate-400);">TOTAL BAYAR</p>
                            <p class="h5" style="margin: 0; color: var(--purple-500);">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <a href="{{ route('ticket', $transaction->order_id) }}" class="btn btn-primary" style="padding: var(--space-1) var(--space-3); font-size: 13px;">
                            LIHAT TIKET
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="margin-left: 6px;"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Summary --}}
        <p class="caption" style="text-align: center; color: var(--slate-400); margin-top: var(--space-6);">
            Menampilkan {{ $transactions->count() }} tiket lunas
        </p>
    @endif

</main>
@endsection
