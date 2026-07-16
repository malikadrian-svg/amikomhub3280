@extends('layouts.app')

@section('title', 'E-Ticket - ' . $transaction->event->title)

@section('content')
    <div style="background-color: var(--slate-950); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-6) var(--space-4); margin-top: -20px;">
        <div style="width: 100%; max-width: 520px;">

            {{-- Header --}}
            <div style="text-align: center; margin-bottom: var(--space-8);">
                <div style="width: 80px; height: 80px; background-color: var(--success-border, #22c55e); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-4) auto; border: 1px solid var(--slate-700); box-shadow: 0 0 0 6px rgba(34,197,94,0.12);">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="display" style="color: var(--slate-0); margin: 0 0 var(--space-1) 0;">TIKET RESMI</h1>
                <p class="body" style="color: var(--slate-400); margin: 0;">Tunjukkan QR code ini di pintu masuk venue.</p>
            </div>

            {{-- Ticket Card --}}
            <div class="card" style="padding: 0; overflow: hidden; position: relative; border: 2px solid var(--slate-700); box-shadow: 6px 6px 0 var(--slate-700);">

                {{-- Ticket Header (Purple strip) --}}
                <div style="padding: var(--space-6) var(--space-8); background-color: var(--purple-500); border-bottom: 1px dashed var(--slate-700); text-align: center; position: relative;">
                    <p class="caption" style="color: rgba(255,255,255,0.7); margin-bottom: var(--space-1); letter-spacing: 0.1em;">E-TICKET RESMI · AMIKOMEVENTHUB</p>
                    <h2 class="h2" style="color: #ffffff; margin: 0; line-height: 1.2;">{{ $transaction->event->title }}</h2>
                    <p class="body-sm" style="color: rgba(255,255,255,0.8); margin: var(--space-1) 0 0 0; font-weight: 600;">
                        {{ \Carbon\Carbon::parse($transaction->event->date)->translatedFormat('d F Y, H:i') }} WIB
                    </p>

                    {{-- Ticket notch decorations --}}
                    <div style="position: absolute; left: -17px; bottom: -17px; width: 34px; height: 34px; background-color: var(--slate-950); border-radius: 50%; border: 2px solid var(--slate-700);"></div>
                    <div style="position: absolute; right: -17px; bottom: -17px; width: 34px; height: 34px; background-color: var(--slate-950); border-radius: 50%; border: 2px solid var(--slate-700);"></div>
                </div>

                {{-- Ticket Body --}}
                <div style="padding: var(--space-6) var(--space-8); display: flex; flex-direction: column; gap: var(--space-6);">

                    {{-- Info grid --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: 4px; letter-spacing: 0.06em;">NAMA PEMBELI</p>
                            <p class="body" style="font-weight: 700; color: var(--slate-0); margin: 0;">{{ $transaction->customer_name }}</p>
                        </div>
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: 4px; letter-spacing: 0.06em;">LOKASI</p>
                            <p class="body" style="font-weight: 700; color: var(--slate-0); margin: 0;">{{ $transaction->event->location }}</p>
                        </div>
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: 4px; letter-spacing: 0.06em;">ORDER ID</p>
                            <p style="font-family: 'IBM Plex Mono', monospace; font-weight: 700; color: var(--slate-0); margin: 0; font-size: 13px;">{{ $transaction->order_id }}</p>
                        </div>
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: 4px; letter-spacing: 0.06em;">KATEGORI</p>
                            <p class="body" style="font-weight: 700; color: var(--slate-0); margin: 0;">{{ $transaction->event->category->name ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- QR Code Section --}}
                    <div style="background-color: var(--slate-800); border: 1.5px solid var(--slate-700); padding: var(--space-6); display: flex; flex-direction: column; align-items: center;">
                        <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-4); letter-spacing: 0.08em;">SCAN QR UNTUK CHECK-IN</p>

                        {{-- QR Code (inline SVG pattern, uniquely generated from order_id) --}}
                        <div style="width: 192px; height: 192px; background-color: #ffffff; padding: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--slate-700);">
                            {{-- We generate a deterministic QR-like visual using the order_id seed --}}
                            <div style="width: 100%; height: 100%; position: relative;">
                                @php
                                    // Generate a deterministic 8×8 grid pattern from the order_id hash
                                    $hash  = md5($transaction->order_id);
                                    $cells = [];
                                    for ($i = 0; $i < 64; $i++) {
                                        $cells[] = hexdec($hash[$i % strlen($hash)]) > 7;
                                    }
                                @endphp
                                <div style="display: grid; grid-template-columns: repeat(8, 1fr); grid-template-rows: repeat(8, 1fr); width: 100%; height: 100%; gap: 1px;">
                                    @foreach($cells as $filled)
                                        <div style="background-color: {{ $filled ? '#0f172a' : '#ffffff' }};"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Ticket code --}}
                        <p style="margin-top: var(--space-4); font-family: 'IBM Plex Mono', monospace; font-weight: 700; color: var(--slate-0); font-size: 15px; letter-spacing: 0.08em;">
                            {{ strtoupper(substr($transaction->order_id, 0, 18)) }}
                        </p>

                        {{-- Status badge --}}
                        <span style="
                            display: inline-block;
                            margin-top: 6px;
                            padding: 3px 12px;
                            font-family: 'IBM Plex Mono', monospace;
                            font-size: 11px;
                            font-weight: 700;
                            letter-spacing: 0.06em;
                            background-color: #dcfce7;
                            color: #15803d;
                            border: 1.5px solid #22c55e;
                        ">
                            ✓ LUNAS
                        </span>
                    </div>

                    {{-- Price summary --}}
                    <div style="border-top: 1px dashed var(--slate-700); padding-top: var(--space-4); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin: 0;">TOTAL PEMBAYARAN</p>
                            <p class="h4" style="color: var(--purple-500); margin: 4px 0 0 0;">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div style="text-align: right;">
                            <p class="caption" style="color: var(--slate-400); margin: 0;">DIKIRIM KE</p>
                            <p style="font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; color: var(--slate-0); margin: 4px 0 0 0; word-break: break-all;">{{ $transaction->customer_email }}</p>
                        </div>
                    </div>

                </div>

                {{-- Ticket Footer --}}
                <div style="padding: var(--space-4) var(--space-8); background-color: var(--slate-800); border-top: 1px solid var(--slate-700);">
                    <div style="display: flex; flex-direction: column; gap: var(--space-3);">

                        {{-- Review CTA --}}
                        @if($canReview)
                            <a href="{{ route('events.show', $transaction->event) }}#review-list"
                               style="width: 100%; padding: var(--space-3); font-size: 15px; display: flex; align-items: center; justify-content: center; gap: var(--space-2); background-color: #f59e0b; color: #0a0a0a; border: 2px solid #d97706; font-family: 'Space Grotesk', sans-serif; font-weight: 700; text-decoration: none; letter-spacing: 0.03em; box-sizing: border-box; text-align: center;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="#d97706" stroke="#0a0a0a" stroke-width="1.5">
                                    <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                </svg>
                                TULIS ULASAN EVENT INI
                            </a>
                        @elseif($userReview)
                            <a href="{{ route('events.show', $transaction->event) }}#review-list"
                               style="width: 100%; padding: var(--space-3); font-size: 14px; display: flex; align-items: center; justify-content: center; gap: var(--space-2); background-color: var(--slate-700); color: var(--slate-200); border: 2px solid var(--slate-600); font-family: 'Space Grotesk', sans-serif; font-weight: 700; text-decoration: none; box-sizing: border-box;">
                                ✓ LIHAT ULASAN ANDA
                            </a>
                        @elseif($transaction->event->isFinished() && !$transaction->event->isReviewable())
                            <div style="text-align: center; padding: var(--space-2);">
                                <p class="caption" style="color: var(--slate-400); margin: 0;">
                                    Ulasan tersedia mulai <strong style="color: var(--slate-0);">{{ $reviewableAfter->translatedFormat('d F Y') }}</strong>
                                </p>
                            </div>
                        @endif

                        <button onclick="window.print()" class="btn btn-primary" style="width: 100%; padding: var(--space-3); font-size: 15px; display: flex; align-items: center; justify-content: center; gap: var(--space-2);">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                                <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            CETAK / SIMPAN PDF
                        </button>
                        <a href="{{ route('my-tickets') }}" style="display: block; text-align: center; color: var(--slate-400); font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; text-decoration: none; letter-spacing: 0.04em;"
                           onmouseover="this.style.color='var(--slate-0)';"
                           onmouseout="this.style.color='var(--slate-400)';">
                            ← SEMUA TIKET SAYA
                        </a>
                    </div>
                </div>

            </div>

            {{-- Warning note --}}
            <p class="caption" style="text-align: center; color: var(--slate-400); margin-top: var(--space-4); line-height: 1.6;">
                Tiket ini bersifat personal. Jangan bagikan kepada orang lain.<br>
                Pembelian tiket bersifat final dan tidak dapat di-refund.
            </p>
        </div>
    </div>

    <style>
        @media print {
            nav, footer, button, .btn { display: none !important; }
            body { background: white; }
        }
    </style>
@endsection
