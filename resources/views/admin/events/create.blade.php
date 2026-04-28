@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.events.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm hover:shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Form Tambah Event</h2>
            <p class="text-sm text-slate-500 mt-1">Lengkapi form di bawah ini untuk membuat acara baru.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf
            
            <div class="p-8 space-y-6">
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Event <span class="text-red-500">*</span></label>
                    <input type="text" name="title" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-all placeholder-slate-400 font-medium" placeholder="Contoh: Tech Seminar 2024" required>
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori Event <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 appearance-none transition-all font-medium" required>
                            <option value="" disabled selected>Pilih Kategori...</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Pendek <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-all placeholder-slate-400 resize-none font-medium" placeholder="Tuliskan deskripsi singkat tentang acara ini..." required></textarea>
                </div>

                <!-- Grid 3 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal & Waktu <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="date" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-all font-medium" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Tiket (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-bold">Rp</span>
                            <input type="number" name="price" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 pl-11 transition-all placeholder-slate-400 font-medium" placeholder="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kapasitas Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-all placeholder-slate-400 font-medium" placeholder="100" required>
                    </div>
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi / Gedung <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </span>
                        <input type="text" name="location" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 pl-11 transition-all placeholder-slate-400 font-medium" placeholder="Contoh: Gedung Rektorat, Ruang Rapat 1" required>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="p-6 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-2xl">
                <a href="{{ route('admin.events.index') }}" class="px-6 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection