@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<main class="page-container" style="padding-top: var(--space-12); padding-bottom: var(--space-12); display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div class="card" style="width: 100%; max-width: 480px; text-align: center; padding: var(--space-8);">
        <div style="width: 96px; height: 96px; background-color: var(--feedback-success); color: var(--slate-0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-6) auto; border: 1px solid var(--slate-700); box-shadow: var(--shadow-hard-sm);">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="h2" style="margin-bottom: var(--space-4);">TERIMA KASIH!</h2>
        
        <p class="body-lg" style="color: var(--slate-200); margin-bottom: var(--space-8); line-height: 1.6;">
            Pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> sedang diproses atau telah berhasil. E-Ticket akan dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>) setelah pembayaran terkonfirmasi lunas.
        </p>
        
        <a href="{{ route('home') }}" class="btn btn-primary w-100" style="padding: var(--space-4); font-size: 18px;">
            KEMBALI KE BERANDA
        </a>
    </div>
</main>
@endsection