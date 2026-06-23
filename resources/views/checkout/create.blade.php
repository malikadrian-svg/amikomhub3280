@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
<!-- Decorative Blobs -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-[-1]">
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-fuchsia-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
</div>

<main class="max-w-6xl mx-auto px-6 py-12 lg:py-20 animate-fade-in-up">
    <div class="mb-10">
        <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:text-indigo-800 transition-colors bg-white/50 px-4 py-2 rounded-full backdrop-blur-sm shadow-sm border border-white mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">Selesaikan Pesanan</h1>
        <p class="text-slate-500 mt-3 text-lg font-medium">Hanya selangkah lagi untuk mendapatkan tiketmu.</p>
    </div>

    @if(session('error'))
        <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Left Column: Form -->
        <div class="flex-1 order-2 lg:order-1">
            <div class="glass rounded-[2.5rem] p-8 md:p-10 border border-white/40 shadow-xl shadow-indigo-900/5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 to-fuchsia-500"></div>
                
                <h3 class="text-2xl font-bold mb-2 flex items-center gap-3 text-slate-800">
                    <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </span>
                    Data Pemesan
                </h3>
                <p class="text-slate-500 mb-8 font-medium ml-13 pl-13">Isi detail di bawah ini tanpa perlu login.</p>

                <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="group">
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest group-focus-within:text-indigo-600 transition-colors">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input type="text" name="customer_name" autocomplete="name" placeholder="Sesuai kartu identitas" class="w-full pl-12 pr-5 py-4 bg-white/80 border-2 border-slate-200/60 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium backdrop-blur-md hover:bg-white" required value="{{ old('customer_name') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest group-focus-within:text-indigo-600 transition-colors">Email Aktif</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="email" name="customer_email" inputmode="email" autocomplete="email" placeholder="email@anda.com" class="w-full pl-12 pr-5 py-4 bg-white/80 border-2 border-slate-200/60 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium backdrop-blur-md hover:bg-white" required value="{{ old('customer_email') }}">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2 font-bold flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg> E-Ticket dikirim ke sini</p>
                        </div>
                        
                        <div class="group">
                            <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest group-focus-within:text-indigo-600 transition-colors">No. WhatsApp</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <input type="tel" name="customer_phone" inputmode="tel" autocomplete="tel" placeholder="08xxxxxxxxxx" class="w-full pl-12 pr-5 py-4 bg-white/80 border-2 border-slate-200/60 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium backdrop-blur-md hover:bg-white" required value="{{ old('customer_phone') }}">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-xl font-black rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 shadow-xl shadow-indigo-600/30 overflow-hidden transition-all hover:scale-[1.01] active:scale-[0.99]">
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-500 via-fuchsia-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-[length:200%_auto] animate-gradient"></div>
                            <span class="relative flex items-center gap-2">
                                Lanjut Pembayaran
                                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </span>
                        </button>
                        <p class="text-center text-xs text-slate-400 mt-4 font-medium flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Pembayaran dijamin aman & terenkripsi
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:w-96 order-1 lg:order-2">
            <div class="sticky top-32 glass rounded-[2.5rem] border border-white/40 shadow-xl shadow-indigo-900/5 overflow-hidden">
                <div class="relative h-48 bg-slate-200">
                    <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/800x400' }}" alt="Event Poster" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-6 right-6">
                        <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-lg mb-2 border border-white/30 uppercase tracking-wide">Tiket Event</span>
                        <h4 class="font-black text-xl text-white leading-tight drop-shadow-md">{{ $event->title }}</h4>
                    </div>
                </div>
                
                <div class="p-6 md:p-8">
                    <div class="space-y-4 mb-6 text-sm font-medium text-slate-600">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y') }}<br><span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($event->date)->format('H:i') }} WIB</span></span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-200/60 space-y-3">
                        <div class="flex justify-between text-slate-500 font-medium">
                            <span>Harga Tiket (1x)</span>
                            <span class="text-slate-800">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 font-medium">
                            <span>Biaya Layanan</span>
                            <span class="text-slate-800">Rp 5.000</span>
                        </div>
                        <div class="flex justify-between text-2xl font-black mt-6 pt-6 border-t border-slate-200/60">
                            <span>Total</span>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-fuchsia-600">
                                Rp {{ number_format($event->price + 5000, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<style>
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradient {
        animation: gradient 3s ease infinite;
    }
</style>
@endsection