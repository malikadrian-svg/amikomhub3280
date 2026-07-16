@extends('layouts.admin')

@section('content')
<div style="margin-bottom: var(--space-8);">
    <h1 class="display" style="margin-bottom: var(--space-2);">MANAJEMEN PENGGUNA</h1>
    <p class="body-lg" style="color: var(--slate-400);">Kelola role dan akses seluruh pengguna platform.</p>
</div>

@if (session('success'))
    <div class="alert alert-success" style="display: flex; align-items: center; gap: var(--space-3);">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span style="font-weight: 500;">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error" style="display: flex; align-items: center; gap: var(--space-3);">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span style="font-weight: 500;">{{ session('error') }}</span>
    </div>
@endif

<!-- Search Bar -->
<div style="margin-bottom: var(--space-6);">
    <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: var(--space-4);">
        <div style="flex: 1; position: relative;">
            <div style="position: absolute; top: 0; bottom: 0; left: 0; padding-left: 16px; display: flex; align-items: center; pointer-events: none; color: var(--slate-500);">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama atau email..." 
                   class="form-control" style="width: 100%; padding-left: 48px; height: 48px;">
        </div>
        <button type="submit" class="btn btn-secondary" style="height: 48px; padding: 0 var(--space-6);">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost" style="height: 48px; width: 48px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Reset Pencarian">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        @endif
    </form>
</div>

<!-- Users Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="table" style="margin: 0; border: none; box-shadow: none;">
            <thead>
                <tr>
                    <th style="border-left: none;">Pengguna</th>
                    <th>Provider</th>
                    <th>Role Global</th>
                    <th>Bergabung</th>
                    <th style="border-right: none; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td style="border-left: none;">
                            <div style="display: flex; align-items: center; gap: var(--space-3);">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--slate-700); object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--purple-100); border: 1px solid var(--purple-300); color: var(--purple-700); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p style="font-weight: 600; color: var(--slate-0); margin: 0;">{{ $user->name }}</p>
                                    <p class="caption" style="margin: 0;">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->provider === 'google')
                                <span class="badge" style="background-color: var(--slate-800); color: var(--slate-200); border-color: var(--slate-700); gap: 6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 15.02 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                    Google
                                </span>
                            @else
                                <span class="badge" style="background-color: var(--slate-800); color: var(--slate-200); border-color: var(--slate-700);">
                                    Lokal
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->hasRole('super_admin'))
                                <span class="badge" style="background-color: var(--purple-100); color: var(--purple-700); border-color: var(--purple-300);">
                                    Super Admin
                                </span>
                            @else
                                <span class="badge" style="background-color: var(--slate-800); color: var(--slate-200); border-color: var(--slate-700);">
                                    Customer
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="caption">{{ $user->created_at->format('d M Y') }}</span>
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <form action="{{ route('admin.users.update-role', $user) }}" method="POST" style="display: flex; justify-content: flex-end; align-items: center; gap: var(--space-2);">
                                @csrf
                                @method('PATCH')
                                <select name="role_id" class="form-control" style="height: 32px; padding: 4px 8px; font-size: 13px; width: auto;">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->hasRole($role->slug) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-secondary" style="height: 32px; padding: 0 var(--space-3); font-size: 12px;">
                                    Ubah
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: var(--space-8); text-align: center; border: none;">
                            <p class="body" style="color: var(--slate-400); margin: 0;">Tidak ada data pengguna yang ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($users->hasPages())
<div style="margin-top: var(--space-6);">
    {{ $users->links() }}
</div>
@endif

@endsection
