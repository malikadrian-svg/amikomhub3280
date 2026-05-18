@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Partner</h2>
            <p class="text-slate-500 mt-1 text-sm">Kelola daftar pihak partner dengan mudah.</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="group flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Partner
        </a>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 border border-emerald-200 flex items-center gap-3 shadow-sm" id="flash-success">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Search Bar -->
        <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200">
            <form method="GET" action="{{ route('admin.partners.index') }}" class="flex gap-3 items-center">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama partner..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-medium placeholder-slate-400">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all text-sm shadow-sm">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.partners.index') }}"
                        class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-100 transition-all text-sm">
                        Reset
                    </a>
                @endif
            </form>
            @if(request('search'))
                <p class="text-xs text-slate-500 mt-2">Menampilkan hasil pencarian untuk: <span class="font-semibold text-indigo-600">"{{ request('search') }}"</span> — {{ $partners->count() }} data ditemukan</p>
            @endif
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide w-16">No</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide">Logo</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide">Nama Partner</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide">Tanggal Ditambahkan</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($partners as $index => $partner)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="p-5 font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-5">
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 bg-slate-50">
                        </td>
                        <td class="p-5">
                            <p class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $partner->name }}</p>
                        </td>
                        <td class="p-5">
                            <div class="flex items-center text-slate-600 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $partner->created_at ? $partner->created_at->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="p-5">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus data partner \'{{ addslashes($partner->name) }}\' secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-slate-700 mb-1">Belum ada partner</p>
                                <p class="text-sm text-slate-400">
                                    @if(request('search'))
                                        Tidak ditemukan partner dengan kata kunci "{{ request('search') }}".
                                    @else
                                        Mulai dengan menambahkan partner pertama.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="px-6 py-3 bg-slate-50/60 border-t border-slate-200">
            <p class="text-xs text-slate-400 font-medium">Total: {{ $partners->count() }} partner terdaftar</p>
        </div>
    </div>
</div>

<script>
    // Auto-hide flash message setelah 4 detik
    const flash = document.getElementById('flash-success');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.5s ease';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }, 4000);
    }
</script>
@endsection
