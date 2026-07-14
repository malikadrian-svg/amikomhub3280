@extends('layouts.app')

@section('content')
    <main style="max-width: 900px; margin: 0 auto; padding: var(--space-8) var(--space-4);">
        <div style="margin-bottom: var(--space-6);">
            <a href="{{ url('/event/1') }}" class="btn-text" style="display: inline-flex; align-items: center; gap: var(--space-2); margin-bottom: var(--space-4);">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"></path>
                </svg>
                KEMBALI KE EVENT
            </a>
            <h1 class="display" style="margin-bottom: var(--space-2);">SELESAIKAN PESANAN</h1>
            <p class="body-lg" style="color: var(--ink-400);">Hanya selangkah lagi untuk mendapatkan tiketmu.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-6);">
            
            <!-- Form Data Pemesan -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: var(--space-4) var(--space-6); border-bottom: 4px solid var(--ink-950); background-color: var(--ink-900); display: flex; align-items: center; gap: var(--space-3);">
                    <div style="width: 32px; height: 32px; background-color: var(--amber-500); border: 2px solid var(--ink-950); display: flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--ink-950);">
                        <svg width="16" height="16" fill="none" stroke="var(--ink-950)" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="h3" style="margin: 0; font-size: 18px;">DATA PEMESAN</h3>
                        <p class="caption" style="color: var(--ink-400); margin: 0;">Isi detail di bawah ini tanpa perlu login.</p>
                    </div>
                </div>
                
                <form class="space-y-6" style="padding: var(--space-6);">
                    <div class="form-group">
                        <label class="label">NAMA LENGKAP</label>
                        <input type="text" placeholder="Sesuai kartu identitas" class="form-control" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-4);">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="label">EMAIL AKTIF</label>
                            <input type="email" placeholder="email@anda.com" class="form-control" required>
                            <p class="caption" style="color: var(--amber-500); margin-top: var(--space-1); display: flex; align-items: center; gap: 4px;">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                E-TICKET DIKIRIM KE SINI
                            </p>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="label">NO. WHATSAPP</label>
                            <input type="tel" placeholder="08xxxxxxxxxxx" class="form-control" required>
                        </div>
                    </div>

                    <div style="margin-top: var(--space-6); padding-top: var(--space-6); border-top: var(--border-width-default) solid var(--ink-700);">
                        <button type="button" onclick="showMidtrans()" class="btn btn-primary" style="width: 100%; height: 56px; font-size: 16px;">
                            LANJUT PEMBAYARAN
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="margin-left: var(--space-2);">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <p class="caption" style="text-align: center; color: var(--ink-400); margin-top: var(--space-3); display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            PEMBAYARAN DIJAMIN AMAN & TERENKRIPSI
                        </p>
                    </div>
                </form>
            </div>
            
            <!-- Ringkasan Pesanan -->
            <div class="card" style="padding: 0; overflow: hidden; margin-top: var(--space-4);">
                <div style="padding: var(--space-4) var(--space-6); border-bottom: 4px solid var(--ink-950); background-color: var(--ink-900);">
                    <h3 class="h3" style="margin: 0; font-size: 18px;">RINGKASAN PESANAN</h3>
                </div>
                <div style="padding: var(--space-6);">
                    <div style="display: flex; gap: var(--space-4); align-items: flex-start; margin-bottom: var(--space-6);">
                        <div style="width: 100px; height: 100px; border: 2px solid var(--ink-950); box-shadow: 2px 2px 0 var(--ink-950); background-color: var(--ink-800); flex-shrink: 0;">
                            <img src="{{ asset('assets/concert.png') }}" alt="Event" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <span class="badge" style="margin-bottom: var(--space-2);">TIKET EVENT</span>
                            <h4 class="h4" style="margin-bottom: var(--space-1);">Film Festival Mahasiswa</h4>
                            <div style="display: flex; flex-direction: column; gap: var(--space-1); margin-top: var(--space-2);">
                                <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--ink-200);">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="body-sm" style="font-weight: 700;">05 October 2026, 09:00 WIB</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--ink-200);">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="body-sm" style="font-weight: 700;">Auditorium Amikom</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px dashed var(--ink-700); padding-top: var(--space-4); display: flex; flex-direction: column; gap: var(--space-2);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="body" style="color: var(--ink-200);">Harga Tiket (1x)</span>
                            <span class="body" style="font-weight: 700;">Rp 15.000</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="body" style="color: var(--ink-200);">Biaya Layanan</span>
                            <span class="body" style="font-weight: 700;">Rp 5.000</span>
                        </div>
                    </div>
                </div>
                <div style="padding: var(--space-4) var(--space-6); background-color: var(--ink-950); border-top: 4px solid var(--ink-950); display: flex; justify-content: space-between; align-items: center;">
                    <span class="h4" style="margin: 0; color: var(--ink-0);">TOTAL</span>
                    <span class="h3" style="margin: 0; color: var(--amber-500);">Rp 20.000</span>
                </div>
            </div>

        </div>
    </main>

    <!-- Overlay Midtrans Dummy -->
    <div id="midtrans-overlay" class="modal-backdrop" style="display: none;">
        <div class="modal" style="padding: 0; overflow: hidden; background-color: var(--ink-900);">
            <div style="background-color: var(--amber-500); padding: var(--space-4) var(--space-6); display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--ink-950);">
                <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--ink-950);">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M21 4H3a1 1 0 00-1 1v14a1 1 0 001 1h18a1 1 0 001-1V5a1 1 0 00-1-1zm-1 14H4V6h16v12z"/><path d="M5 9h14v2H5zM5 13h8v2H5z"/></svg>
                    <span class="h4" style="margin: 0;">PEMBAYARAN</span>
                </div>
                <button onclick="hideMidtrans()" class="btn" style="padding: var(--space-1); background-color: transparent; color: var(--ink-950); border: none;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l18 18"></path>
                    </svg>
                </button>
            </div>
            
            <div style="padding: var(--space-6); text-align: center;">
                <p class="label" style="color: var(--ink-400); margin-bottom: var(--space-1);">TOTAL TAGIHAN</p>
                <h2 class="display" style="font-size: 36px; color: var(--amber-500); margin-bottom: var(--space-2);">Rp 20.000</h2>
                <p class="caption" style="color: var(--ink-400);">ORDER ID: #TRX-99210</p>

                <div style="margin-top: var(--space-6); display: flex; flex-direction: column; gap: var(--space-3);">
                    <button onclick="window.location.href='{{ url('/my-ticket/1') }}'" class="btn" style="width: 100%; height: 56px; justify-content: space-between; background-color: var(--ink-950); color: var(--ink-0); border-color: var(--ink-700);">
                        <span>GOPAY / QRIS</span>
                        <span>→</span>
                    </button>
                    <button class="btn" disabled style="width: 100%; height: 56px; justify-content: space-between;">
                        <span>VIRTUAL ACCOUNT (BNI, BRI)</span>
                        <span>→</span>
                    </button>
                    <button class="btn" disabled style="width: 100%; height: 56px; justify-content: space-between;">
                        <span>KARTU DEBIT/KREDIT</span>
                        <span>→</span>
                    </button>
                </div>

                <div style="margin-top: var(--space-6); display: flex; items-center; justify-content: center; gap: var(--space-2); color: var(--ink-400);">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="caption">SECURE CHECKOUT BY MIDTRANS</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showMidtrans() {
            document.getElementById('midtrans-overlay').style.display = 'flex';
        }
        function hideMidtrans() {
            document.getElementById('midtrans-overlay').style.display = 'none';
        }
    </script>
@endsection
