@extends('layouts.app')

@section('title', $partner->name . ' — Profil Partner AmikomEventHub')

@section('content')
<main class="page-container" style="padding-top: var(--space-8); padding-bottom: var(--space-12);">

    {{-- ── Partner Hero ─────────────────────────────────────────────────────── --}}
    <div style="display: flex; flex-wrap: wrap; gap: var(--space-6); align-items: center; margin-bottom: var(--space-10); padding: var(--space-8); background-color: var(--slate-800); border: 2px solid var(--slate-600); box-shadow: var(--shadow-hard-modal);">
        {{-- Logo --}}
        <div style="width: 96px; height: 96px; background-color: #ffffff; border: 3px solid var(--slate-600); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; box-shadow: var(--shadow-hard-sm);">
            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}"
                 style="max-width: 100%; max-height: 100%; object-fit: contain;"
                 onerror="this.parentElement.innerHTML='<span style=\'font-family:Space Grotesk,sans-serif;font-weight:700;font-size:32px;color:var(--slate-0);\'>{{ strtoupper(substr($partner->name, 0, 2)) }}</span>'">
        </div>
        {{-- Info --}}
        <div style="flex: 1; min-width: 200px;">
            <div class="badge" style="margin-bottom: var(--space-2);">PARTNER TERVERIFIKASI</div>
            <h1 class="display" style="margin: 0 0 var(--space-2) 0; line-height: 1.1;">{{ strtoupper($partner->name) }}</h1>
            <div style="display: flex; flex-wrap: wrap; gap: var(--space-4);">
                <span class="caption" style="color: var(--slate-400);">{{ $totalEvents }} Event Total</span>
                <span class="caption" style="color: var(--slate-400);">·</span>
                <span class="caption" style="color: var(--slate-400);">{{ $totalCompleted }} Selesai</span>
                <span class="caption" style="color: var(--slate-400);">·</span>
                <span class="caption" style="color: var(--slate-400);">{{ $totalUpcoming }} Mendatang</span>
            </div>
        </div>
        {{-- Avg Rating badge --}}
        @if($avgRating)
            <div style="text-align: center; background-color: var(--slate-900); border: 2px solid var(--slate-600); padding: var(--space-4) var(--space-6); flex-shrink: 0;">
                <div style="font-family: 'Space Grotesk', sans-serif; font-size: 42px; font-weight: 700; color: #f59e0b; line-height: 1;">{{ $avgRating }}</div>
                <div style="display: flex; justify-content: center; gap: 2px; margin: 4px 0;">
                    @for ($s = 1; $s <= 5; $s++)
                        <svg width="14" height="14" viewBox="0 0 24 24"
                             fill="{{ $s <= round($avgRating) ? '#f59e0b' : 'none' }}"
                             stroke="{{ $s <= round($avgRating) ? '#f59e0b' : 'var(--slate-600)' }}" stroke-width="1.5">
                            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    @endfor
                </div>
                <p class="caption" style="color: var(--slate-400); margin: 0;">{{ $totalReviews }} ulasan</p>
            </div>
        @endif
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: var(--space-8);">

        {{-- ── Left Column: Rating + Events ─────────────────────────────────── --}}
        <div style="flex: 2 1 500px;">

            {{-- Rating Distribution --}}
            @if($totalReviews > 0)
                <div style="margin-bottom: var(--space-8);">
                    <h2 class="h3" style="margin-bottom: var(--space-4);">DISTRIBUSI RATING</h2>
                    @include('partials.rating-summary', [
                        'avgRating'    => $avgRating,
                        'reviewCount'  => $totalReviews,
                        'distribution' => $distribution,
                    ])
                </div>
            @endif

            {{-- Recent Reviews --}}
            <div style="margin-bottom: var(--space-8);">
                <h2 class="h3" style="margin-bottom: var(--space-4);">ULASAN TERBARU</h2>
                @forelse($reviews as $review)
                    <div style="margin-bottom: var(--space-3);">
                        {{-- Show event title above each review --}}
                        <p class="caption" style="color: var(--purple-500); font-weight: 700; margin: 0 0 var(--space-1) 0; letter-spacing: 0.04em;">
                            ↳ {{ $review->event->title ?? 'Event' }}
                        </p>
                        @include('partials.review-card', ['review' => $review])
                    </div>
                @empty
                    <div style="text-align: center; padding: var(--space-8); border: 2px dashed var(--slate-600);">
                        <p class="body" style="color: var(--slate-400); margin: 0;">Belum ada ulasan untuk partner ini.</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($reviews->hasPages())
                    <div style="margin-top: var(--space-4);">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>

        </div>

        {{-- ── Right Column: Events Sidebar ─────────────────────────────────── --}}
        <div style="flex: 1 1 280px;">

            {{-- Upcoming Events --}}
            @if($upcomingEvents->isNotEmpty())
                <div style="margin-bottom: var(--space-6);">
                    <h3 class="h4" style="margin-bottom: var(--space-4); color: var(--slate-0);">EVENT MENDATANG</h3>
                    <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                        @foreach($upcomingEvents as $ev)
                            <a href="{{ route('events.show', $ev) }}"
                               style="display: block; background-color: var(--slate-800); border: 2px solid var(--purple-500); padding: var(--space-4); text-decoration: none; transition: box-shadow 0.15s;"
                               onmouseover="this.style.boxShadow='4px 4px 0 var(--purple-700)';"
                               onmouseout="this.style.boxShadow='none';">
                                <p style="margin: 0 0 var(--space-1) 0; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 14px; color: var(--slate-0);">{{ $ev->title }}</p>
                                <p class="caption" style="margin: 0; color: var(--slate-400);">{{ \Carbon\Carbon::parse($ev->date)->format('d M Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Completed Events --}}
            @if($completedEvents->isNotEmpty())
                <div>
                    <h3 class="h4" style="margin-bottom: var(--space-4); color: var(--slate-0);">EVENT SELESAI</h3>
                    <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                        @foreach($completedEvents as $ev)
                            <a href="{{ route('events.show', $ev) }}"
                               style="display: block; background-color: var(--slate-800); border: 2px solid var(--slate-600); padding: var(--space-4); text-decoration: none; transition: border-color 0.15s;"
                               onmouseover="this.style.borderColor='var(--slate-400)';"
                               onmouseout="this.style.borderColor='var(--slate-600)';">
                                <p style="margin: 0 0 var(--space-1) 0; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 14px; color: var(--slate-200);">{{ $ev->title }}</p>
                                <div style="display: flex; align-items: center; gap: var(--space-3); flex-wrap: wrap;">
                                    <p class="caption" style="margin: 0; color: var(--slate-400);">{{ \Carbon\Carbon::parse($ev->date)->format('d M Y') }}</p>
                                    @if($ev->approved_reviews_count > 0)
                                        <span class="caption" style="color: #f59e0b; font-weight: 700;">
                                            ★ {{ number_format($ev->approved_reviews_avg_rating, 1) }}
                                            <span style="color: var(--slate-400); font-weight: 400;">({{ $ev->approved_reviews_count }})</span>
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($upcomingEvents->isEmpty() && $completedEvents->isEmpty())
                <div style="padding: var(--space-6); border: 2px dashed var(--slate-600); text-align: center;">
                    <p class="body" style="color: var(--slate-400); margin: 0;">Belum ada event terdaftar.</p>
                </div>
            @endif
        </div>
    </div>

</main>
@endsection
