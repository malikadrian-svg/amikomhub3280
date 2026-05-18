@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span
                class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">#1
                Event Platform</span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan
                Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events"
                    class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#categories"
                    class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
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
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- Soal 4: Section Kategori Platform -->
    <!-- ============================================ -->
    <section id="categories" class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">Kategori</span>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3">Jelajahi Berdasarkan Kategori</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">Temukan event yang sesuai minatmu dari berbagai kategori yang tersedia di AmikomEventHub.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
            <a href="/?category={{ $cat->slug }}"
                class="group relative bg-white rounded-2xl border border-slate-100 p-6 text-center shadow-sm hover:shadow-xl hover:border-indigo-200 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <!-- Decorative gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 mx-auto bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-110 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-indigo-600 transition-colors">{{ $cat->name }}</h3>
                    @if(isset($cat->events_count))
                    <span class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">{{ $cat->events_count }} Event</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Events Grid -->
    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Event Terdekat</h2>
                <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
        </div>

        <!-- Blok Navigasi Filter Kategori -->
        <div class="mb-10 flex gap-3 justify-center flex-wrap">
            <!-- Rujukan awal navigasi bebas bawaan -->
            <a href="/" 
                class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 {{ request('category') == null ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 scale-105' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                Semua Kategori
            </a>

            <!-- Melakukan iterasi nama Tab Kategori dinamis saat jumlah data bertambah -->
            @foreach($categories as $cat)
            <a href="/?category={{ $cat->slug }}"
                class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 scale-105' : 'bg-white border border-slate-200 text-slate-600 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>

        <!-- Zona Menampilkan Grid List Event -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
            <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="relative overflow-hidden aspect-[3/4]">
                    <img src="https://placehold.co/200x600" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                        {{ $event->category->name }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                    <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t">
                        <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                        <a href="{{url('event/' . $event->id)}}" class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($events->isEmpty())
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-lg font-bold text-slate-700 mb-1">Belum ada event mendatang</p>
            <p class="text-sm text-slate-400">Nantikan event seru berikutnya dari AmikomEventHub!</p>
        </div>
        @endif
    </section>

    <!-- ============================================ -->
    <!-- Soal 4: Section Partner Kami -->
    <!-- ============================================ -->
    @if($partners->isNotEmpty())
    <section id="partners" class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider mb-4">Kolaborasi</span>
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3">Partner & Pendukung Kami</h2>
            <p class="text-slate-500 font-medium max-w-xl mx-auto">Terima kasih kepada para partner yang telah mendukung ekosistem AmikomEventHub.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 md:p-12">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($partners as $partner)
                <div class="group flex flex-col items-center justify-center p-6 rounded-2xl hover:bg-indigo-50 hover:shadow-md transition-all duration-300 cursor-default">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-slate-100 bg-slate-50 flex items-center justify-center mb-4 group-hover:border-indigo-200 group-hover:scale-110 transition-all duration-300 shadow-sm">
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<span class=\'text-2xl font-black text-indigo-300\'>{{ strtoupper(substr($partner->name, 0, 2)) }}</span>'">
                    </div>
                    <p class="text-sm font-bold text-slate-700 text-center group-hover:text-indigo-600 transition-colors leading-tight">{{ $partner->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
