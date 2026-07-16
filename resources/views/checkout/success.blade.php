@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<main class="page-container" style="padding-top: var(--space-12); padding-bottom: var(--space-12); display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div class="card" style="width: 100%; max-width: 480px; text-align: center; padding: var(--space-8);">

        {{-- Success icon --}}
        <div style="width: 96px; height: 96px; background-color: #22c55e; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-6) auto; border: 1px solid var(--slate-700); box-shadow: 0 0 0 8px rgba(34,197,94,0.1);">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="h2" style="margin-bottom: var(--space-4);">TERIMA KASIH!</h2>

        <p class="body-lg" style="color: var(--slate-200); margin-bottom: var(--space-6); line-height: 1.6;">
            Pembayaran untuk pesanan <strong>{{ $transaction->gateway_order_id }}</strong> sedang diproses atau telah berhasil.
            E-Ticket akan dikirim ke email Anda (<strong>{{ $transaction->order->customer_email }}</strong>) setelah pembayaran terkonfirmasi lunas.
        </p>

        {{-- Order summary box --}}
        <div style="background-color: var(--slate-800); border: 1.5px solid var(--slate-700); padding: var(--space-4); margin-bottom: var(--space-6); text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-2);">
                <span class="caption" style="color: var(--slate-400);">EVENT</span>
                <span class="body" style="font-weight: 700; color: var(--slate-0);">{{ $transaction->order->event->title }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: var(--space-2);">
                <span class="caption" style="color: var(--slate-400);">TOTAL</span>
                <span class="body" style="font-weight: 700; color: var(--purple-500);">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="caption" style="color: var(--slate-400);">STATUS</span>
                <span style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; padding: 2px 10px; background-color: {{ $transaction->isPaid() ? '#dcfce7' : '#fef9c3' }}; color: {{ $transaction->isPaid() ? '#15803d' : '#854d0e' }}; border: 1px solid {{ $transaction->isPaid() ? '#22c55e' : '#eab308' }};">
                    {{ $transaction->isPaid() ? 'LUNAS' : 'MENUNGGU KONFIRMASI' }}
                </span>
            </div>
        </div>

        {{-- CTAs --}}
        <div style="display: flex; flex-direction: column; gap: var(--space-3);">
            @if($transaction->isPaid())
                <a href="{{ route('ticket', $transaction->order->order_number) }}" class="btn btn-primary" style="padding: var(--space-3); font-size: 16px; display: flex; align-items: center; justify-content: center; gap: var(--space-2);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    LIHAT TIKET SAYA
                </a>
            @endif
            <a href="{{ route('my-tickets') }}" class="btn" style="padding: var(--space-3); font-size: 15px; background-color: transparent; border-color: var(--slate-700); color: var(--slate-0); display: flex; align-items: center; justify-content: center; gap: var(--space-2);">
                SEMUA TIKET SAYA
            </a>
            <a href="{{ route('home') }}" style="display: block; text-align: center; color: var(--slate-400); font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; text-decoration: none; margin-top: var(--space-1);"
               onmouseover="this.style.color='var(--slate-0)';"
               onmouseout="this.style.color='var(--slate-400)';">
                ← KEMBALI KE BERANDA
            </a>
        </div>

    </div>
</main>
@endsection