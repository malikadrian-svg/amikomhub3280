@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12 animate-fade-in-up">
        <div class="flex-1 space-y-8">
            <span
                class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1
                Event Platform</span>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan
                Midtrans.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="#events"
                    class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform hover:bg-indigo-700">
                    Mulai Jelajah
                </a>
                <a href="#categories"
                    class="px-8 py-4 bg-white border-2 border-slate-200 rounded-2xl font-bold text-lg shadow-sm hover:border-indigo-600 hover:text-indigo-600 transition hover:shadow-xl hover:shadow-indigo-100">
                    Lihat Kategori
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div
                class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            
            <div class="relative z-10 group">
                <img src="assets/concert.png" alt="Concert"
                class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">
                
            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                    <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 font-extrabold uppercase tracking-widest">Terverifikasi</p>
                            <p class="font-bold text-sm text-slate-800">Pembayaran Aman via Midtrans</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr class="border-t border-slate-100 max-w-7xl mx-auto">

    <!-- ============================================ -->
    <!-- Soal 4: Section Kategori Platform -->
    <!-- ============================================ -->
    <section id="categories" class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center mb-16 animate-fade-in-up">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">Kategori</span>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Jelajahi Berdasarkan Kategori</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">Temukan event yang sesuai minatmu dari berbagai kategori yang tersedia di AmikomEventHub.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($categories as $cat)
            @php
                $iconPath = match(strtolower($cat->slug)) {
                    'seminar-it' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />',
                    'entertainment' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />',
                    'workshop' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
                    'e-sport' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />'
                };
            @endphp
            <a href="/?category={{ $cat->slug }}#events"
                class="group relative bg-white rounded-3xl border border-slate-100 p-8 text-center shadow-sm hover:shadow-xl hover:border-indigo-200 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <!-- Decorative gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $iconPath !!}
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-lg mb-2 group-hover:text-indigo-700 transition-colors">{{ $cat->name }}</h3>
                    @if(isset($cat->events_count))
                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold uppercase tracking-widest group-hover:bg-white/80 group-hover:text-indigo-600 transition-colors">{{ $cat->events_count }} Event</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <hr class="border-t border-slate-100 max-w-7xl mx-auto">

    <!-- Events Grid -->
    <section id="events" class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center mb-16 animate-fade-in-up">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">🔥 Event Terbaru</span>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Jangan Lewatkan Acara Seru</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">Pilih event terdekat dan segera amankan tiketmu sebelum kehabisan.</p>
        </div>

        <!-- Blok Navigasi Filter Kategori -->
        <div class="mb-12 flex gap-3 justify-center flex-wrap">
            <!-- Rujukan awal navigasi bebas bawaan -->
            <a href="/#events" 
                class="px-6 py-3 rounded-full font-bold text-sm transition-all duration-300 {{ request('category') == null ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 scale-105' : 'bg-white border-2 border-slate-200 text-slate-500 hover:border-indigo-400 hover:text-indigo-600 hover:shadow-md' }}">
                Semua Kategori
            </a>

            <!-- Melakukan iterasi nama Tab Kategori dinamis saat jumlah data bertambah -->
            @foreach($categories as $cat)
            <a href="/?category={{ $cat->slug }}#events"
                class="px-6 py-3 rounded-full font-bold text-sm transition-all duration-300 {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 scale-105' : 'bg-white border-2 border-slate-200 text-slate-500 hover:border-indigo-400 hover:text-indigo-600 hover:shadow-md' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>

        <!-- Zona Menampilkan Grid List Event -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
            <div class="group flex flex-col bg-white rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:border-indigo-100 transition-all duration-300 overflow-hidden">
                <div class="relative overflow-hidden aspect-video">
                    <img src="https://placehold.co/600x340/e2e8f0/475569?text={{ urlencode($event->title) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 px-3 py-1.5 bg-white/95 backdrop-blur rounded-lg text-xs font-extrabold uppercase tracking-widest text-indigo-600 shadow-sm">
                        {{ $event->category->name }}
                    </div>
                </div>
                <div class="p-6 md:p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-extrabold mb-3 group-hover:text-indigo-600 transition-colors leading-snug">{{ $event->title }}</h3>
                    
                    <div class="space-y-2 mb-6 flex-1">
                        <div class="flex items-center gap-3 text-slate-500 text-sm font-medium">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y • H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-500 text-sm font-medium">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span>TBA / Lihat Detail</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Mulai Dari</p>
                            <span class="text-xl font-black text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{url('event/' . $event->id)}}" class="px-6 py-3 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white hover:shadow-lg hover:shadow-indigo-200 transition-all">Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($events->isEmpty())
        <div class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-200 mt-8">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-xl font-extrabold text-slate-700 mb-2">Belum ada event mendatang</p>
            <p class="text-slate-500">Nantikan event seru berikutnya dari AmikomEventHub!</p>
        </div>
        @endif
    </section>

    <!-- ============================================ -->
    <!-- Soal 4: Section Partner Kami -->
    <!-- ============================================ -->
    @if($partners->isNotEmpty())
    <hr class="border-t border-slate-100 max-w-7xl mx-auto">
    <section id="partners" class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center mb-16 animate-fade-in-up">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">Kolaborasi</span>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Partner & Pendukung Kami</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">Terima kasih kepada para partner yang telah mendukung ekosistem AmikomEventHub.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($partners as $partner)
            <div class="group flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 hover:-translate-y-1 transition-all duration-300 cursor-default">
                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-50 flex items-center justify-center mb-4 transition-all duration-500">
                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-500" onerror="this.parentElement.innerHTML='<span class=\'text-2xl font-black text-indigo-300\'>{{ strtoupper(substr($partner->name, 0, 2)) }}</span>'">
                </div>
                <p class="text-sm font-bold text-slate-600 text-center group-hover:text-indigo-600 transition-colors leading-tight">{{ $partner->name }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif
@endsection
