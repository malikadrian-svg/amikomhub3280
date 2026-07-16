@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="page-container d-flex align-center gap-4" style="padding-top: var(--space-10); padding-bottom: var(--space-10); flex-wrap: wrap;">
        <div style="flex: 1 1 500px; padding-right: var(--space-4);">
            <div class="badge" style="margin-bottom: var(--space-3);">#1 EVENT PLATFORM</div>
            <h1 class="display" style="margin-bottom: var(--space-4);">
                TEMUKAN & PESAN <br>
                <span style="color: var(--purple-500);">TIKET EVENT</span> IMPIANMU.
            </h1>
            <p class="body-lg" style="color: var(--slate-200); margin-bottom: var(--space-5); max-width: 500px;">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="d-flex gap-3" style="flex-wrap: wrap;">
                <a href="#events" class="btn btn-primary" style="padding: var(--space-3) var(--space-5);">Mulai Jelajah</a>
                <a href="#categories" class="btn btn-secondary" style="padding: var(--space-3) var(--space-5);">Lihat Kategori</a>
            </div>
        </div>
        <div style="flex: 1 1 400px; position: relative;">
            <div style="border: 4px solid var(--slate-600); border-radius: var(--radius-xl); box-shadow: var(--shadow-hard-modal); overflow: hidden; background-color: var(--slate-800);">
                <img src="assets/concert.png" alt="Concert" style="width: 100%; aspect-ratio: 4/5; object-fit: cover; display: block;" onerror="this.src='https://placehold.co/800x1000/141414/f5f5f0?text=CONCERT+IMAGE'">
            </div>
            
            <div class="alert alert-info" style="position: absolute; bottom: -20px; left: -20px; box-shadow: var(--shadow-hard-modal); border-color: var(--slate-600); background-color: #ffffff; padding: var(--space-2) var(--space-3); border-radius: var(--radius-md);">
                <div class="d-flex align-center gap-3">
                    <div style="width: 24px; height: 24px; background-color: var(--purple-500); border-radius: var(--radius-xs); display: flex; align-items: center; justify-content: center; color: #ffffff;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="square" stroke-linejoin="miter" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="caption" style="color: var(--slate-400); margin:0;">TERVERIFIKASI</p>
                        <p class="label" style="color: var(--slate-0); margin:0;">Pembayaran via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr style="border: 0; border-top: var(--border-width-default) solid var(--slate-600); margin: var(--space-8) 0;">

    <!-- Kategori Section -->
    <section id="categories" class="page-container">
        <div style="text-center; margin-bottom: var(--space-8);">
            <div class="badge" style="margin-bottom: var(--space-2);">KATEGORI</div>
            <h2 class="h2">JELAJAHI KATEGORI</h2>
            <p class="body" style="color: var(--slate-200); max-width: 600px; margin: 0 auto;">Temukan event yang sesuai minatmu dari berbagai kategori yang tersedia di AmikomEventHub.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-4);">
            @foreach($categories as $cat)
            @php
                $iconPath = match(strtolower($cat->slug)) {
                    'seminar-it' => '<path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.25" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />',
                    'entertainment' => '<path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.25" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />',
                    'workshop' => '<path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.25" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.25" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
                    'e-sport' => '<path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.25" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.25" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    default => '<path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.25" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />'
                };
            @endphp
            <a href="/?category={{ $cat->slug }}#events" class="card" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: var(--space-5) var(--space-3); color: var(--slate-0);">
                <div style="width: 48px; height: 48px; background-color: var(--purple-500); color: #ffffff; margin-bottom: var(--space-3); border-radius: var(--radius-xs); display: flex; align-items: center; justify-content: center; border: 1px solid var(--slate-700);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $iconPath !!}
                    </svg>
                </div>
                <h3 class="h4" style="margin-bottom: var(--space-1);">{{ $cat->name }}</h3>
                @if(isset($cat->events_count))
                <div class="caption" style="color: var(--slate-400);">{{ $cat->events_count }} EVENT</div>
                @endif
            </a>
            @endforeach
        </div>
    </section>

    <hr style="border: 0; border-top: var(--border-width-default) solid var(--slate-600); margin: var(--space-8) 0;">

    <!-- Events Section -->
    <section id="events" class="page-container" style="padding-bottom: var(--space-10);">
        <div style="text-center; margin-bottom: var(--space-6);">
            <div class="badge" style="margin-bottom: var(--space-2);">TERBARU</div>
            <h2 class="h2">JANGAN LEWATKAN ACARA SERU</h2>
        </div>

        <div class="tabs" style="justify-content: center; margin-bottom: var(--space-6); flex-wrap: wrap;">
            <a href="/#events" class="tab {{ request('category') == null ? 'active' : '' }}" style="text-decoration: none; padding: var(--space-2) var(--space-3);">SEMUA KATEGORI</a>
            @foreach($categories as $cat)
            <a href="/?category={{ $cat->slug }}#events" class="tab {{ request('category') == $cat->slug ? 'active' : '' }}" style="text-decoration: none; padding: var(--space-2) var(--space-3);">{{ strtoupper($cat->name) }}</a>
            @endforeach
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: var(--space-5);">
            @foreach($events as $event)
            <div class="card" style="padding: 0; display: flex; flex-direction: column; overflow: hidden;">
                <div style="position: relative; aspect-ratio: 16/9; border-bottom: 2px solid var(--slate-600);">
                    <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                         ? asset('storage/' . $event->poster_path)
                         : 'https://placehold.co/600x400/141414/f5f5f0?text=EVENT+POSTER' }}" alt="{{ $event->title }}"
                         style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    <div style="position: absolute; top: var(--space-2); left: var(--space-2); background: var(--purple-500); color: #ffffff; padding: 4px 8px; font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; border: 1px solid var(--slate-700);">
                        {{ strtoupper($event->category->name) }}
                    </div>
                </div>
                
                <div style="padding: var(--space-4); display: flex; flex-direction: column; flex: 1;">
                    <h3 class="h3" style="margin-bottom: var(--space-3);">{{ $event->title }}</h3>
                    
                    <div style="margin-bottom: var(--space-4); flex: 1;">
                        <div class="d-flex align-center gap-2 mb-2">
                            <svg width="16" height="16" fill="none" stroke="var(--slate-400)" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="body-sm" style="color: var(--slate-200);">{{ \Carbon\Carbon::parse($event->date)->format('d M Y • H:i') }}</span>
                        </div>
                        <div class="d-flex align-center gap-2">
                            <svg width="16" height="16" fill="none" stroke="var(--slate-400)" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="body-sm" style="color: var(--slate-200);">TBA / Lihat Detail</span>
                        </div>
                    </div>

                    <div class="d-flex align-center justify-between" style="border-top: 1px solid var(--slate-600); padding-top: var(--space-4); margin-top: auto; flex-wrap: wrap; gap: var(--space-2);">
                        <div>
                            <p class="caption" style="color: var(--slate-400); margin-bottom: 2px;">MULAI DARI</p>
                            <span class="h4" style="color: var(--purple-500); margin:0;">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            {{-- Star rating (only shown when reviews exist) --}}
                            @if($event->approved_reviews_count > 0)
                                <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5">
                                        <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                    </svg>
                                    <span style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; color: #f59e0b;">
                                        {{ number_format($event->approved_reviews_avg_rating, 1) }}
                                    </span>
                                    <span class="caption" style="color: var(--slate-400);">({{ $event->approved_reviews_count }})</span>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary">DETAIL</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($events->isEmpty())
        <div style="padding: var(--space-8); border: 2px solid var(--slate-600); background-color: var(--slate-800); text-align: center; margin-top: var(--space-6);">
            <div style="width: 48px; height: 48px; border: 2px solid var(--slate-600); margin: 0 auto var(--space-4) auto; display: flex; align-items: center; justify-content: center;">
                <svg width="24" height="24" fill="none" stroke="var(--slate-400)" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="h3" style="color: var(--slate-0); margin-bottom: var(--space-2);">BELUM ADA EVENT</h3>
            <p class="body" style="color: var(--slate-200);">Nantikan event seru berikutnya dari AmikomEventHub.</p>
        </div>
        @endif
    </section>

    @if($partners->isNotEmpty())
    <hr style="border: 0; border-top: var(--border-width-default) solid var(--slate-600); margin: var(--space-8) 0;">
    <section id="partners" class="page-container" style="padding-bottom: var(--space-10);">
        <div style="text-center; margin-bottom: var(--space-8);">
            <div class="badge" style="margin-bottom: var(--space-2);">KOLABORASI</div>
            <h2 class="h2">PARTNER & PENDUKUNG</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: var(--space-4);">
            @foreach($partners as $partner)
            <div style="padding: var(--space-4); border: 2px solid var(--slate-600); display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: var(--slate-800);">
                <div style="width: 64px; height: 64px; border: 2px solid var(--slate-600); margin-bottom: var(--space-3); display: flex; align-items: center; justify-content: center; overflow: hidden; background-color: #ffffff;">
                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" style="max-width: 100%; max-height: 100%; filter: grayscale(100%) contrast(1.2);" onerror="this.parentElement.innerHTML='<span class=\'h4\' style=\'color: var(--slate-400);\'>{{ strtoupper(substr($partner->name, 0, 2)) }}</span>'">
                </div>
                <p class="label" style="text-align: center; color: var(--slate-0);">{{ $partner->name }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif
@endsection
