@extends('layouts.admin')

@section('header', 'Manajemen Penyelenggara')

@section('content')
<div class="card" style="padding: 0; overflow: hidden;">
    
    <div class="card-header" style="margin-bottom: 0; padding: var(--space-6) var(--space-8); display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--slate-700);">
        <div>
            <h2 class="h3" style="margin: 0;">Daftar Organisasi</h2>
            <p class="body-sm" style="margin: 0; color: var(--slate-400);">Verifikasi dan kelola penyelenggara event di platform.</p>
        </div>
        
        <form action="{{ route('admin.organizations.index') }}" method="GET" style="display: flex; gap: var(--space-2);">
            <select name="status" class="form-control" style="width: auto;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Dibekukan</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="form-control" style="width: 200px;">
            <button type="submit" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table class="table" style="margin: 0; border: none; box-shadow: none;">
            <thead>
                <tr>
                    <th style="border-left: none;">Organisasi</th>
                    <th>Pemilik</th>
                    <th>Status</th>
                    <th>Dokumen</th>
                    <th style="border-right: none; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($organizations as $org)
                    <tr>
                        <td style="border-left: none;">
                            <div style="font-weight: 600; color: var(--slate-0);">{{ $org->name }}</div>
                            <div class="caption">{{ $org->email }} • {{ $org->phone }}</div>
                        </td>
                        <td>
                            {{ $org->owner ? $org->owner->name : 'N/A' }}
                        </td>
                        <td>
                            @if ($org->status === 'pending')
                                <span class="badge" style="background-color: var(--feedback-warning); color: #ffffff; border-color: #ffffff;">Menunggu</span>
                            @elseif ($org->status === 'approved')
                                <span class="badge" style="background-color: var(--feedback-success); color: var(--slate-0); border-color: var(--slate-0);">Disetujui</span>
                            @else
                                <span class="badge" style="background-color: var(--feedback-error); color: var(--slate-0); border-color: var(--slate-0);">Dibekukan</span>
                            @endif
                        </td>
                        <td>
                            @if($org->documents->count() > 0)
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    @foreach($org->documents as $doc)
                                        <a href="{{ route('admin.organizations.document.download', $doc) }}" target="_blank" style="color: var(--purple-500); text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            {{ $doc->type }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="caption">Tidak ada</span>
                            @endif
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: var(--space-2);">
                                @if ($org->status === 'pending')
                                    <form action="{{ route('admin.organizations.approve', $org) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-primary" style="height: 32px; padding: 0 var(--space-2); font-size: 13px;" onclick="return confirm('Setujui organisasi ini?')">
                                            Setujui
                                        </button>
                                    </form>
                                @endif
                                
                                @if ($org->status !== 'suspended')
                                    <form action="{{ route('admin.organizations.suspend', $org) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-destructive" style="height: 32px; padding: 0 var(--space-2); font-size: 13px;" onclick="return confirm('Bekukan organisasi ini? Akses penyelenggara akan dicabut.')">
                                            Bekukan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-8); border: none;">
                            <p class="body" style="color: var(--slate-400); margin: 0;">Tidak ada data organisasi ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if ($organizations->hasPages())
        <div style="padding: var(--space-4); border-top: 1px solid var(--slate-700);">
            {{ $organizations->links() }}
        </div>
    @endif
</div>
@endsection
