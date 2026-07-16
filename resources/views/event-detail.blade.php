@extends('layouts.app')

@section('title', $event->title . ' — AmikomEventHub')

@section('content')
    <main class="page-container" style="padding-top: var(--space-8); padding-bottom: var(--space-12);">
        <div style="display: flex; flex-wrap: wrap; gap: var(--space-8); align-items: flex-start;">
            
            <!-- Left Side: Poster & Organizer -->
            <div style="flex: 1 1 300px; max-width: 400px; position: sticky; top: 100px;">
                <div style="border: 4px solid var(--slate-600); border-radius: var(--radius-xl); box-shadow: var(--shadow-hard-modal); overflow: hidden; background-color: var(--slate-800); margin-bottom: var(--space-6);">
                    <img src="{{ ($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path))
                         ? asset('storage/' . $event->poster_path)
                         : 'https://placehold.co/400x600/141414/f5f5f0?text=POSTER' }}" alt="{{ $event->title }}" style="width: 100%; aspect-ratio: 3/4; object-fit: cover; display: block;">
                </div>
                
                {{-- Organizer / Partner Card --}}
                <div class="card">
                    <h4 class="h5" style="margin-bottom: var(--space-4);">PENYELENGGARA</h4>
                    @if($event->partner)
                        <a href="{{ route('partners.show', $event->partner) }}"
                           style="display: flex; align-items: center; gap: var(--space-3); text-decoration: none; transition: opacity 0.15s;"
                           onmouseover="this.style.opacity='0.8';" onmouseout="this.style.opacity='1';">
                            <div style="width: 48px; height: 48px; background-color: #ffffff; border: 2px solid var(--slate-600); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                <img src="{{ $event->partner->logo_url }}" alt="{{ $event->partner->name }}"
                                     style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                     onerror="this.parentElement.innerHTML='<span style=\'font-family:Space Grotesk,sans-serif;font-weight:700;font-size:16px;color:var(--slate-0);\'>{{ strtoupper(substr($event->partner->name, 0, 2)) }}</span>'">
                            </div>
                            <div>
                                <p class="h6" style="margin: 0; color: var(--slate-0);">{{ $event->partner->name }}</p>
                                <p class="caption" style="margin: 0; color: var(--purple-500);">LIHAT PROFIL →</p>
                            </div>
                        </a>
                    @else
                        <div class="d-flex align-center gap-3">
                            <div style="width: 48px; height: 48px; background-color: var(--purple-500); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 18px; border: 1px solid var(--slate-700);">
                                AH
                            </div>
                            <div>
                                <p class="h6" style="margin: 0; color: var(--slate-0);">AmikomEventHub</p>
                                <p class="caption" style="margin: 0; color: var(--slate-400);">VERIFIED ORGANIZER</p>
                            </div>
                        </div>
                    @endif

                    {{-- Mini rating summary in sidebar --}}
                    @if($reviewCount > 0)
                        <div style="margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px solid var(--slate-700);">
                            <div style="display: flex; align-items: center; gap: var(--space-2);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5">
                                    <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                </svg>
                                <span class="h5" style="margin: 0; color: var(--slate-0);">{{ $avgRating }}</span>
                                <span class="caption" style="color: var(--slate-400);">/ 5 · {{ $reviewCount }} ulasan</span>
                            </div>
                        </div>
                    @endif
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
                            @if($event->stock > 0 && !$event->isFinished())
                            <a href="{{ route('checkout.create', $event) }}" class="btn" style="background-color: #ffffff; color: var(--purple-500); padding: var(--space-4) var(--space-6); font-size: 18px; border: 1px solid var(--slate-700);">
                                PESAN SEKARANG
                            </a>
                            @elseif($event->isFinished())
                            <button class="btn" disabled style="background-color: var(--slate-700); color: var(--slate-400); padding: var(--space-4) var(--space-6); font-size: 18px; cursor: not-allowed; border: 1px solid var(--slate-700);">
                                EVENT SELESAI
                            </button>
                            @else
                            <button class="btn" disabled style="background-color: var(--slate-700); color: var(--slate-400); padding: var(--space-4) var(--space-6); font-size: 18px; cursor: not-allowed; border: 1px solid var(--slate-700);">
                                TIKET HABIS
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- RATING & REVIEW SECTION -->
                <!-- ============================================================ -->

                {{-- Flash messages --}}
                @if(session('success'))
                    <div style="background-color: #dcfce7; border: 2px solid #22c55e; padding: var(--space-4); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3);">
                        <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        <span style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 700; color: #15803d;">{{ session('success') }}</span>
                    </div>
                @endif

                @if($reviewCount > 0 || $canReview || $userReview)
                <div style="margin-bottom: var(--space-10);">
                    <div style="display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-6);">
                        <h3 class="h3" style="margin: 0;">ULASAN & RATING</h3>
                        @if($reviewCount > 0)
                            <span style="background-color: var(--purple-500); color: #fff; font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; padding: 3px 10px; border: 1px solid var(--slate-700);">{{ $reviewCount }}</span>
                        @endif
                    </div>

                    {{-- Rating summary --}}
                    @if($reviewCount > 0)
                        @include('partials.rating-summary', [
                            'avgRating'    => $avgRating,
                            'reviewCount'  => $reviewCount,
                            'distribution' => $distribution,
                        ])
                    @endif

                    {{-- ── Submit / Edit Review CTA ────────────────────────── --}}
                    @auth
                        @if($canReview)
                            {{-- User is eligible: show the submit form --}}
                            <div style="background-color: var(--slate-800); border: 2px solid var(--purple-500); padding: var(--space-6); margin-bottom: var(--space-6); box-shadow: 4px 4px 0 var(--purple-700);">
                                <h4 class="h4" style="margin-bottom: var(--space-4); color: var(--slate-0);">✍ TULIS ULASAN ANDA</h4>
                                @include('partials.review-form', [
                                    'event'      => $event,
                                    'userReview' => null,
                                    'formAction' => route('reviews.store', $event),
                                    'method'     => 'POST',
                                    'formId'     => 'store-form',
                                ])
                            </div>

                        @elseif($userReview)
                            {{-- User already reviewed — show their review with edit option --}}
                            <div style="background-color: var(--slate-800); border: 2px solid var(--slate-600); padding: var(--space-5); margin-bottom: var(--space-6);">
                                <p class="caption" style="color: var(--purple-500); font-weight: 700; margin-bottom: var(--space-3); letter-spacing: 0.06em;">ULASAN ANDA</p>
                                @include('partials.review-card', ['review' => $userReview])
                            </div>

                        @elseif($event->isFinished() && !$event->isReviewable())
                            {{-- Event just ended, review window not open yet --}}
                            <div style="background-color: var(--slate-800); border: 2px dashed var(--slate-600); padding: var(--space-5); margin-bottom: var(--space-6); text-align: center;">
                                <p class="body" style="color: var(--slate-400); margin: 0;">
                                    🕐 Ulasan akan dapat dikirim mulai
                                    <strong style="color: var(--slate-0);">{{ $reviewableAfter->translatedFormat('d F Y') }}</strong>
                                </p>
                            </div>

                        @elseif(!$event->isFinished())
                            {{-- Event hasn't happened yet --}}
                            <div style="background-color: var(--slate-800); border: 2px dashed var(--slate-600); padding: var(--space-5); margin-bottom: var(--space-6); text-align: center;">
                                <p class="body" style="color: var(--slate-400); margin: 0;">
                                    Ulasan tersedia setelah event selesai.
                                </p>
                            </div>
                        @else
                            {{-- Not eligible (no ticket or payment pending) --}}
                            <div style="background-color: var(--slate-800); border: 2px dashed var(--slate-600); padding: var(--space-5); margin-bottom: var(--space-6); text-align: center;">
                                <p class="body" style="color: var(--slate-400); margin: 0;">
                                    Hanya peserta yang telah membeli tiket yang dapat memberikan ulasan.
                                </p>
                            </div>
                        @endif
                    @else
                        {{-- Guest user — prompt to login --}}
                        @if($event->isReviewable())
                            <div style="background-color: var(--slate-800); border: 2px solid var(--slate-600); padding: var(--space-5); margin-bottom: var(--space-6); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--space-4);">
                                <p class="body" style="color: var(--slate-200); margin: 0;">
                                    Punya tiket? Masuk untuk memberikan ulasan.
                                </p>
                                <a href="{{ route('google.login') }}" class="btn btn-primary" style="padding: var(--space-2) var(--space-5);">MASUK</a>
                            </div>
                        @endif
                    @endauth

                    {{-- ── Review List ─────────────────────────────────────── --}}
                    @if($reviews->isEmpty())
                        <div style="text-align: center; padding: var(--space-8); border: 2px dashed var(--slate-600);">
                            <svg width="48" height="48" fill="none" stroke="var(--slate-600)" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto var(--space-3) auto; display: block;">
                                <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="body" style="color: var(--slate-400); margin: 0;">Belum ada ulasan untuk event ini. Jadilah yang pertama!</p>
                        </div>
                    @else
                        <div id="review-list">
                            @foreach($reviews as $review)
                                @include('partials.review-card', ['review' => $review])
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if($reviews->hasPages())
                            <div style="margin-top: var(--space-6); display: flex; justify-content: center;">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    @endif
                </div>
                @endif

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

    {{-- ── Edit Review Modal ────────────────────────────────────────────────── --}}
    <div id="edit-review-modal"
         style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 200; align-items: center; justify-content: center; padding: var(--space-4);">
        <div style="background-color: var(--slate-900); border: 2px solid var(--slate-600); padding: var(--space-8); width: 100%; max-width: 540px; box-shadow: 8px 8px 0 var(--slate-700); position: relative; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6);">
                <h4 class="h4" style="margin: 0;">EDIT ULASAN</h4>
                <button onclick="closeEditModal()"
                        style="background: none; border: none; cursor: pointer; color: var(--slate-400); font-size: 24px; line-height: 1; padding: 0;">✕</button>
            </div>
            @include('partials.review-form', [
                'event'      => $event,
                'userReview' => $userReview,
                'formAction' => $userReview ? route('reviews.update', $userReview) : '#',
                'method'     => 'PUT',
                'formId'     => 'edit-form',
            ])
        </div>
    </div>

    {{-- Override form action dynamically from JS --}}
    @if($userReview)
        <script>
            document.getElementById('edit-review-form') &&
                (document.getElementById('edit-review-form').id = 'edit-review-form');
        </script>
    @endif

@endsection
