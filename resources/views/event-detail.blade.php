@extends('layouts.app')

@section('content')
    <main class="page-container" style="padding-top: var(--space-8); padding-bottom: var(--space-12);">
        <div style="display: flex; flex-wrap: wrap; gap: var(--space-8); align-items: flex-start;">
            
            <!-- Left Side: Poster & Organizer -->
            <div style="flex: 1 1 300px; max-width: 400px; position: sticky; top: 100px;">
                <div style="border: 4px solid var(--slate-600); border-radius: var(--radius-xl); box-shadow: var(--shadow-hard-modal); overflow: hidden; background-color: var(--slate-800); margin-bottom: var(--space-6);">
                    <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                         ? asset('storage/' . $event->poster_path)
                         : 'https://placehold.co/400x600/141414/f5f5f0?text=POSTER' }}" alt="{{ $event->title }}" style="width: 100%; aspect-ratio: 3/4; object-fit: cover; display: block;">
                </div>
                
                <div class="card">
                    <h4 class="h5" style="margin-bottom: var(--space-4);">PENYELENGGARA</h4>
                    <div class="d-flex align-center gap-3">
                        <div style="width: 48px; height: 48px; background-color: var(--purple-500); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 18px; border: 1px solid var(--slate-700);">
                            AB
                        </div>
                        <div>
                            <p class="h6" style="margin: 0; color: var(--slate-0);">ABP Productions</p>
                            <p class="caption" style="margin: 0; color: var(--slate-400);">VERIFIED ORGANIZER</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Details -->
            <div style="flex: 2 1 600px;">
                <div style="margin-bottom: var(--space-8);">
                    <span class="badge" style="margin-bottom: var(--space-3);">{{ strtoupper($event->category->name) }}</span>
                    <h1 class="display" style="margin-bottom: var(--space-4); line-height: 1.1;">{{ $event->title }}</h1>
                    
                    <div class="d-flex align-center gap-4" style="flex-wrap: wrap; margin-bottom: var(--space-6);">
                        <div class="d-flex align-center gap-2">
                            <svg width="24" height="24" fill="none" stroke="var(--purple-500)" stroke-width="2.25" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="h6" style="color: var(--slate-200); margin:0;">{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="d-flex align-center gap-2">
                            <svg width="24" height="24" fill="none" stroke="var(--purple-500)" stroke-width="2.25" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="h6" style="color: var(--slate-200); margin:0;">{{ $event->location }}</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: var(--space-10);">
                    <h3 class="h3" style="margin-bottom: var(--space-4);">DESKRIPSI EVENT</h3>
                    <div class="body-lg" style="color: var(--slate-200); line-height: 1.8;">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                <!-- Ticket Booking Box -->
                <div style="background-color: var(--purple-500); padding: var(--space-8); border: 1px solid var(--slate-700); box-shadow: var(--shadow-hard-sm); margin-bottom: var(--space-10); position: relative;">
                    <div class="d-flex align-center justify-between gap-6" style="flex-wrap: wrap;">
                        <div>
                            <p class="caption" style="color: #ffffff; margin-bottom: var(--space-1); font-weight: 700;">HARGA TIKET</p>
                            <h2 class="display" style="color: #ffffff; margin: 0; line-height: 1;">Rp {{ number_format($event->price, 0, ',', '.') }} <span class="h5" style="color: var(--slate-800);">/ org</span></h2>
                            
                            <p class="body" style="color: #ffffff; font-weight: 600; margin-top: var(--space-3); display: flex; align-items: center; gap: var(--space-2);">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24">
                                    <path stroke-linecap="square" stroke-linejoin="miter" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Sisa stok: <span style="text-decoration: underline;">{{ $event->stock }} TIKET!</span>
                            </p>
                        </div>
                        <div>
                            @if($event->stock > 0)
                            <a href="{{ url('checkout/' . $event->id) }}" class="btn" style="background-color: #ffffff; color: var(--purple-500); padding: var(--space-4) var(--space-6); font-size: 18px; border: 1px solid var(--slate-700);">
                                PESAN SEKARANG
                            </a>
                            @else
                            <button class="btn" disabled style="background-color: var(--slate-700); color: var(--slate-400); padding: var(--space-4) var(--space-6); font-size: 18px; cursor: not-allowed; border: 1px solid var(--slate-700);">
                                TIKET HABIS
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Policies -->
                <div>
                    <h3 class="h4" style="margin-bottom: var(--space-4);">KEBIJAKAN TIKET</h3>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="display: flex; align-items: flex-start; gap: var(--space-3); margin-bottom: var(--space-3);">
                            <svg width="24" height="24" fill="none" stroke="var(--feedback-success)" stroke-width="2.25" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="body" style="color: var(--slate-200);">E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: var(--space-3); margin-bottom: var(--space-3);">
                            <svg width="24" height="24" fill="none" stroke="var(--feedback-success)" stroke-width="2.25" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="body" style="color: var(--slate-200);">Tiket dapat discan di pintu masuk (Check-in).</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: var(--space-3); margin-bottom: var(--space-3);">
                            <svg width="24" height="24" fill="none" stroke="var(--feedback-error)" stroke-width="2.25" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
                                <path stroke-linecap="square" stroke-linejoin="miter" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="body" style="color: var(--feedback-error); font-weight: 500;">Tiket yang sudah dibeli tidak dapat direfund.</span>
                        </li>
                    </ul>
                </div>
                
            </div>
        </div>
    </main>
@endsection
