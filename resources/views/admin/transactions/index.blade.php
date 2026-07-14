@extends('layouts.admin')

@section('title', 'Laporan Transaksi - Admin')
@section('page_title', 'LAPORAN TRANSAKSI')
@section('page_subtitle', 'Pantau arus kas dan penjualan tiket Anda.')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="margin-bottom: var(--space-8);">
        <h2 class="h2" style="margin-bottom: var(--space-2);">@yield('page_title')</h2>
        <p class="body" style="color: var(--ink-400);">@yield('page_subtitle')</p>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: var(--space-6); border-bottom: 4px solid var(--ink-950); background-color: var(--amber-500);">
            <div style="display: flex; align-items: center; gap: var(--space-4);">
                <div style="width: 48px; height: 48px; border: 2px solid var(--ink-950); background-color: var(--ink-0); display: flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--ink-950);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="color: var(--ink-950);">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="h3" style="margin: 0; color: var(--ink-950);">DAFTAR TRANSAKSI</h3>
                </div>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="table" style="margin: 0; border: none; box-shadow: none;">
                <thead>
                    <tr>
                        <th style="border-left: none; width: 64px; text-align: center;">NO</th>
                        <th>ORDER ID</th>
                        <th>DETAIL PEMBELI</th>
                        <th>EVENT</th>
                        <th>TANGGAL TRANSAKSI</th>
                        <th>STATUS</th>
                        <th style="border-right: none; text-align: right;">TOTAL TAGIHAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $trx)
                    <tr style="background-color: {{ $trx->status == 'pending' ? 'var(--ink-950)' : 'transparent' }};">
                        <td style="border-left: none; font-weight: 700; color: var(--ink-400); text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge" style="font-family: monospace; font-size: 14px; background-color: {{ $trx->status == 'pending' ? 'var(--ink-800)' : 'var(--ink-950)' }}; color: {{ $trx->status == 'pending' ? 'var(--ink-200)' : 'var(--ink-0)' }}; border-color: {{ $trx->status == 'pending' ? 'var(--ink-700)' : 'var(--ink-700)' }};">
                                {{ $trx->order_id }}
                            </span>
                        </td>
                        <td>
                            <p class="body" style="font-weight: 700; margin: 0; text-transform: uppercase; color: var(--ink-0);">{{ $trx->customer_name }}</p>
                            <p class="caption" style="color: var(--ink-400); margin: var(--space-1) 0 0 0;">
                                {{ $trx->customer_email }}<br>
                                {{ $trx->customer_phone }}
                            </p>
                        </td>
                        <td>
                            <p class="body" style="font-weight: 700; margin: 0;">{{ $trx->event->title ?? '-' }}</p>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--ink-200); font-weight: 500; font-size: 14px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $trx->created_at->format('d M Y, H:i') }}
                            </div>
                        </td>
                        <td>
                            @if($trx->status === 'settlement' || $trx->status === 'success')
                                <span class="badge" style="background-color: var(--feedback-success); color: var(--ink-0); border-color: var(--ink-950);">SUCCESS</span>
                            @elseif($trx->status === 'pending')
                                <span class="badge" style="background-color: var(--ink-800); color: var(--amber-500); border-color: var(--amber-700);">PENDING</span>
                            @else
                                <span class="badge" style="background-color: transparent; color: var(--error-border); border-color: var(--error-border);">{{ strtoupper($trx->status) }}</span>
                            @endif
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <p class="h4" style="margin: 0; color: {{ $trx->status == 'pending' ? 'var(--ink-400)' : 'var(--ink-0)' }};">
                                RP {{ number_format($trx->total_price, 0, ',', '.') }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: var(--space-10); border: none;">
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <div style="width: 80px; height: 80px; border: 4px solid var(--ink-700); background-color: var(--ink-950); display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4); box-shadow: 4px 4px 0 var(--ink-950);">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="color: var(--ink-400);">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <p class="h4" style="margin-bottom: var(--space-2);">BELUM ADA TRANSAKSI</p>
                                <p class="body" style="color: var(--ink-400);">Belum ada data transaksi yang masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="padding: var(--space-4) var(--space-6); border-top: var(--border-width-default) solid var(--ink-700); background-color: var(--ink-900); display: flex; justify-content: space-between; align-items: center;">
            <p class="caption" style="font-weight: 700; color: var(--ink-200); margin: 0;">TOTAL: {{ $transactions->total() }} TRANSAKSI</p>
            @if($transactions->hasPages())
                <div>
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection