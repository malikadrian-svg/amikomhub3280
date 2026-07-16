@extends('layouts.admin')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-8); flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h2 class="h2" style="margin-bottom: var(--space-2);">MANAJEMEN PARTNER</h2>
            <p class="body" style="color: var(--slate-400);">Kelola daftar pihak partner dengan mudah.</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: var(--space-2);">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            TAMBAH PARTNER
        </a>
    </div>

    @if(session('success'))
        <div id="flash-success" style="background-color: var(--feedback-success); color: var(--slate-0); padding: var(--space-4); border: 1px solid var(--slate-700); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3); box-shadow: var(--shadow-hard-sm);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span style="font-weight: 700;">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: var(--space-6); border-bottom: 1px solid var(--slate-700); background-color: var(--purple-500);">
            <form method="GET" action="{{ route('admin.partners.index') }}" style="display: flex; gap: var(--space-4); flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 250px; position: relative;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--slate-400);">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="CARI NAMA PARTNER..." class="input" style="padding-left: 48px;">
                </div>
                <button type="submit" class="btn btn-primary" style="background-color: #ffffff; color: var(--slate-0);">
                    CARI
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.partners.index') }}" class="btn" style="background-color: var(--slate-0); color: #ffffff;">
                        RESET
                    </a>
                @endif
            </form>
            @if(request('search'))
                <p class="caption" style="margin-top: var(--space-4); font-weight: 700; color: #ffffff;">MENAMPILKAN HASIL UNTUK: <span style="background-color: var(--slate-0); padding: 2px 8px; border: 1px solid var(--slate-700);">{{ request('search') }}</span> — {{ $partners->count() }} DATA</p>
            @endif
        </div>

        <div style="overflow-x: auto;">
            <table class="table" style="margin: 0; border: none; box-shadow: none;">
                <thead>
                    <tr>
                        <th style="border-left: none; width: 64px; text-align: center;">NO</th>
                        <th style="width: 80px; text-align: center;">LOGO</th>
                        <th>NAMA PARTNER</th>
                        <th>TANGGAL DITAMBAHKAN</th>
                        <th style="border-right: none; text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $index => $partner)
                    <tr>
                        <td style="border-left: none; font-weight: 700; color: var(--slate-400); text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">
                            <div style="width: 48px; height: 48px; border: 2px solid var(--slate-600); box-shadow: var(--shadow-hard-sm); background-color: var(--slate-800); overflow: hidden; display: inline-block;">
                                <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </td>
                        <td>
                            <p class="body" style="font-weight: 700; margin: 0; text-transform: uppercase;">{{ $partner->name }}</p>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--slate-200); font-weight: 500; font-size: 14px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $partner->created_at ? $partner->created_at->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: var(--space-2);">
                                <a href="{{ route('admin.partners.edit', $partner->id) }}" class="btn" style="padding: var(--space-2); background-color: var(--slate-700); color: var(--slate-0); border: 2px solid var(--slate-600);">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Anda yakin ingin menghapus data partner \'{{ addslashes($partner->name) }}\' secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="padding: var(--space-2); background-color: transparent; border: 2px solid var(--error-border); color: var(--error-border);">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-10); border: none;">
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <div style="width: 80px; height: 80px; border: 4px solid var(--slate-600); background-color: #ffffff; display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4); box-shadow: var(--shadow-hard-sm);">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="color: var(--slate-400);">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <p class="h4" style="margin-bottom: var(--space-2);">BELUM ADA PARTNER</p>
                                <p class="body" style="color: var(--slate-400);">
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

        <div style="padding: var(--space-4) var(--space-6); border-top: var(--border-width-default) solid var(--slate-600); background-color: var(--slate-800);">
            <p class="caption" style="font-weight: 700; color: var(--slate-200);">TOTAL: {{ $partners->count() }} PARTNER</p>
        </div>
    </div>
</div>

<script>
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
