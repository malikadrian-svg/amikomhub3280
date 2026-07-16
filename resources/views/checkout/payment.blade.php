@extends('layouts.app')

@section('title', 'Pembayaran - ' . $transaction->order->event->title)

@section('content')
<main class="page-container" style="padding-top: var(--space-12); padding-bottom: var(--space-12); display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div class="card" style="width: 100%; max-width: 480px; text-align: center; padding: var(--space-8);">
        <div style="width: 80px; height: 80px; background-color: var(--purple-500); color: #ffffff; border-radius: var(--radius-sm); border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-6) auto; box-shadow: var(--shadow-hard-sm);">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24">
                <path stroke-linecap="square" stroke-linejoin="miter" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        
        <h2 class="h2" style="margin-bottom: var(--space-2);">SELESAIKAN PEMBAYARAN</h2>
        <p class="body" style="color: var(--slate-200); margin-bottom: var(--space-6);">Mohon selesaikan pembayaran tiket Anda untuk event <strong>{{ $transaction->order->event->title }}</strong>.</p>
        
        <div style="background-color: #ffffff; border: 2px solid var(--slate-600); padding: var(--space-6); margin-bottom: var(--space-8);">
            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">TOTAL TAGIHAN</p>
            <h3 class="display" style="color: var(--purple-500); margin: 0;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</h3>
            <p class="caption" style="color: var(--slate-400); margin-top: var(--space-2);">ORDER ID: {{ $transaction->gateway_order_id }}</p>
        </div>
        
        <button id="pay-button" class="btn btn-primary w-100" style="padding: var(--space-4); font-size: 18px;">
            BAYAR SEKARANG
        </button>
    </div>
</main>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        // SnapToken acquired from previous step
        snap.pay('{{ $transaction->snap_token }}', {
            // Optional
            onSuccess: function(result){
                window.location.href = "{{ route('checkout.success', $transaction->gateway_order_id) }}";
            },
            // Optional
            onPending: function(result){
                window.location.href = "{{ route('checkout.success', $transaction->gateway_order_id) }}";
            },
            // Optional
            onError: function(result){
                alert("Pembayaran Gagal!");
            }
        });
    };
    
    // Auto trigger
    window.onload = function() {
        document.getElementById('pay-button').click();
    }
</script>
@endsection