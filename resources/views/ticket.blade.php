@extends('layouts.app')

@section('content')
    <div style="background-color: #ffffff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: var(--space-6); margin-top: -20px;">
        <div style="width: 100%; max-width: 480px;">
            <div style="text-align: center; margin-bottom: var(--space-8);">
                <div style="width: 80px; height: 80px; background-color: var(--feedback-success); color: var(--slate-0); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-4) auto; border: 1px solid var(--slate-700);">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="display" style="color: var(--slate-0);">PEMBAYARAN BERHASIL!</h1>
                <p class="body-lg" style="color: var(--slate-400); margin-top: var(--space-2);">Tiket Anda telah terbit dan siap digunakan.</p>
            </div>

            <div class="card" style="padding: 0; overflow: hidden; position: relative;">
                <div style="padding: var(--space-8); background-color: var(--purple-500); border-bottom: 1px dashed var(--slate-700); text-align: center; position: relative;">
                    <p class="caption" style="color: #ffffff; margin-bottom: var(--space-2);">E-TICKET RESMI</p>
                    <h2 class="h2" style="color: #ffffff; margin: 0;">Jazz Night 2024: A Celebration</h2>

                    <div style="position: absolute; left: -16px; bottom: -16px; width: 32px; height: 32px; background-color: #ffffff; border-radius: 50%; border: 1px solid var(--slate-700);"></div>
                    <div style="position: absolute; right: -16px; bottom: -16px; width: 32px; height: 32px; background-color: #ffffff; border-radius: 50%; border: 1px solid var(--slate-700);"></div>
                </div>

                <div style="padding: var(--space-8); display: flex; flex-direction: column; gap: var(--space-8);">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6);">
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">NAMA PEMBELI</p>
                            <p class="body" style="font-weight: 700; color: var(--slate-0); margin: 0;">Donni Prabowo</p>
                        </div>
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">TANGGAL & WAKTU</p>
                            <p class="body" style="font-weight: 700; color: var(--slate-0); margin: 0;">16 Nov, 19:30</p>
                        </div>
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">ORDER ID</p>
                            <p class="body" style="font-weight: 700; color: var(--slate-0); margin: 0;">TRX-99210</p>
                        </div>
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">LOKASI</p>
                            <p class="body" style="font-weight: 700; color: var(--slate-0); margin: 0;">Blue Note Lounge</p>
                        </div>
                    </div>

                    <div style="background-color: var(--slate-600); padding: var(--space-6); border: 2px solid var(--slate-600); display: flex; flex-direction: column; align-items: center;">
                        <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-4);">SCAN QR UNTUK CHECK-IN</p>
                        <div style="width: 192px; height: 192px; background-color: var(--slate-0); padding: var(--space-4); box-shadow: inset 4px 4px 0 rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center;">
                            <div style="width: 100%; height: 100%; border: 1px solid var(--slate-700); display: flex; flex-wrap: wrap; padding: 4px;">
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                                <div style="width: 25%; height: 25%; background-color: var(--slate-0);"></div>
                                <div style="width: 25%; height: 25%; background-color: #ffffff;"></div>
                            </div>
                        </div>
                        <p style="margin-top: var(--space-4); font-family: monospace; font-weight: 700; color: var(--slate-0); font-size: 16px;">TKT-001293848</p>
                    </div>
                </div>

                <div style="padding: 0 var(--space-8) var(--space-8) var(--space-8);">
                    <button onclick="window.print()" class="btn btn-primary w-100" style="padding: var(--space-4); font-size: 16px;">
                        CETAK / SIMPAN PDF
                    </button>
                    <a href="{{ url('/') }}" style="display: block; text-align: center; margin-top: var(--space-4); color: var(--slate-400); font-weight: 700; text-decoration: none;">KEMBALI KE BERANDA</a>
                </div>
            </div>
        </div>
    </div>
@endsection
