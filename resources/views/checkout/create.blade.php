@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
<main style="max-width: 900px; margin: 0 auto; padding: var(--space-8) var(--space-4);">
    <div style="margin-bottom: var(--space-6);">
        <a href="{{ route('events.show', $event->id) }}" class="btn-text" style="display: inline-flex; align-items: center; gap: var(--space-2); margin-bottom: var(--space-4);">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7"></path>
            </svg>
            KEMBALI KE EVENT
        </a>
        <h1 class="display" style="margin-bottom: var(--space-2);">SELESAIKAN PESANAN</h1>
        <p class="body-lg" style="color: var(--slate-400);">Hanya selangkah lagi untuk mendapatkan tiketmu.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom: var(--space-6);">
            <div style="display: flex; align-items: center; gap: var(--space-3);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="body">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-6);">
        <!-- Form Data Pemesan -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: var(--space-4) var(--space-6); border-bottom: 1px solid var(--slate-700); background-color: var(--slate-800); display: flex; align-items: center; gap: var(--space-3);">
                <div style="width: 32px; height: 32px; background-color: var(--purple-500); border: 1px solid var(--slate-700); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-hard-sm);">
                    <svg width="16" height="16" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="h3" style="margin: 0; font-size: 18px;">DATA PEMESAN</h3>
                    {{-- Show user's Google account info if authenticated --}}
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                        @if($authUser->avatar)
                            <img src="{{ $authUser->avatar }}" alt="" style="width: 20px; height: 20px; border-radius: 50%; border: 1.5px solid var(--purple-500);">
                        @endif
                        <p class="caption" style="color: var(--purple-500); margin: 0; font-weight: 700;">
                            ✓ AKUN GOOGLE TERHUBUNG — {{ $authUser->email }}
                        </p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6" style="padding: var(--space-6);">
                @csrf
                <div class="form-group">
                    <label class="label">NAMA LENGKAP</label>
                    <input
                        type="text"
                        name="customer_name"
                        autocomplete="name"
                        placeholder="Sesuai kartu identitas"
                        class="form-control"
                        required
                        value="{{ old('customer_name', $authUser->name) }}"
                    >
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-4);">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="label">EMAIL AKTIF</label>
                        {{-- Email is pre-filled and readonly: verified by Google, used for e-ticket delivery --}}
                        <input
                            type="email"
                            name="customer_email"
                            inputmode="email"
                            autocomplete="email"
                            class="form-control"
                            required
                            readonly
                            value="{{ $authUser->email }}"
                            style="background-color: var(--slate-800); color: var(--slate-400); cursor: not-allowed; opacity: 0.85;"
                        >
                        <p class="caption" style="color: var(--purple-500); margin-top: var(--space-1); display: flex; align-items: center; gap: 4px;">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            E-TICKET DIKIRIM KE SINI
                        </p>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="label">NO. WHATSAPP</label>
                        <input
                            type="tel"
                            name="customer_phone"
                            inputmode="tel"
                            autocomplete="tel"
                            placeholder="08xxxxxxxxxxx"
                            class="form-control"
                            required
                            value="{{ old('customer_phone', $authUser->phone) }}"
                        >
                    </div>
                </div>

                <div style="margin-top: var(--space-6); padding-top: var(--space-6); border-top: var(--border-width-default) solid var(--slate-600);">
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 56px; font-size: 16px;">
                        LANJUT PEMBAYARAN
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="margin-left: var(--space-2);">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <p class="caption" style="text-align: center; color: var(--slate-400); margin-top: var(--space-3); display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        PEMBAYARAN DIJAMIN AMAN & TERENKRIPSI
                    </p>
                </div>
            </form>
        </div>
        
        <!-- Ringkasan Pesanan -->
        <div class="card" style="padding: 0; overflow: hidden; margin-top: var(--space-4);">
            <div style="padding: var(--space-4) var(--space-6); border-bottom: 1px solid var(--slate-700); background-color: var(--slate-800);">
                <h3 class="h3" style="margin: 0; font-size: 18px;">RINGKASAN PESANAN</h3>
            </div>
            <div style="padding: var(--space-6);">
                <div style="display: flex; gap: var(--space-4); align-items: flex-start; margin-bottom: var(--space-6);">
                    <div style="width: 100px; height: 100px; border: 1px solid var(--slate-700); box-shadow: var(--shadow-hard-sm); background-color: var(--slate-700); flex-shrink: 0;">
                        <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/800x400/141414/f5f5f0?text=POSTER' }}" alt="Event Poster" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div>
                        <span class="badge" style="margin-bottom: var(--space-2);">TIKET EVENT</span>
                        <h4 class="h4" style="margin-bottom: var(--space-1);">{{ $event->title }}</h4>
                        <div style="display: flex; flex-direction: column; gap: var(--space-1); margin-top: var(--space-2);">
                            <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--slate-200);">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="body-sm" style="font-weight: 700;">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y, H:i') }} WIB</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--slate-200);">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="body-sm" style="font-weight: 700;">{{ $event->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px dashed var(--slate-600); padding-top: var(--space-4); display: flex; flex-direction: column; gap: var(--space-2);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="body" style="color: var(--slate-200);">Harga Tiket (1x)</span>
                        <span class="body" style="font-weight: 700;">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="body" style="color: var(--slate-200);">Biaya Layanan</span>
                        <span class="body" style="font-weight: 700;">Rp 5.000</span>
                    </div>
                </div>
            </div>
            <div style="padding: var(--space-4) var(--space-6); background-color: #ffffff; border-top: 1px solid var(--slate-700); display: flex; justify-content: space-between; align-items: center;">
                <span class="h4" style="margin: 0; color: var(--slate-0);">TOTAL</span>
                <span class="h3" style="margin: 0; color: var(--purple-500);">Rp {{ number_format($event->price + 5000, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>
</main>
@endsection