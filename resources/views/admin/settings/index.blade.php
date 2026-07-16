@extends('layouts.admin')

@section('content')
<div style="margin-bottom: var(--space-8);">
    <h1 class="display" style="margin-bottom: var(--space-2);">PENGATURAN PLATFORM</h1>
    <p class="body-lg" style="color: var(--slate-400);">Konfigurasi pengaturan aplikasi global dan biaya platform.</p>
</div>

@if (session('success'))
    <div class="alert alert-success" style="display: flex; align-items: center; gap: var(--space-3);">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span style="font-weight: 500;">{{ session('success') }}</span>
    </div>
@endif

<div class="card">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6); margin-bottom: var(--space-8);">
            <!-- Default Commission Rate -->
            <div class="form-group">
                <label class="label">Default Commission Rate (%)</label>
                <div style="position: relative;">
                    <input type="number" step="0.01" name="default_commission_rate" value="{{ old('default_commission_rate', $settings['default_commission_rate']) }}" 
                           class="form-control @error('default_commission_rate') is-invalid @enderror" style="width: 100%; padding-right: 32px;">
                    <div style="position: absolute; top: 0; bottom: 0; right: 0; padding-right: 12px; display: flex; align-items: center; pointer-events: none; color: var(--slate-400); font-weight: 500;">%</div>
                </div>
                <p class="caption" style="margin-top: 4px;">Persentase yang dipotong dari pendapatan kotor penyelenggara per pesanan.</p>
                @error('default_commission_rate')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Platform Fee -->
            <div class="form-group">
                <label class="label">Flat Platform Fee (Rp)</label>
                <div style="position: relative;">
                    <div style="position: absolute; top: 0; bottom: 0; left: 0; padding-left: 12px; display: flex; align-items: center; pointer-events: none; color: var(--slate-400); font-weight: 500;">Rp</div>
                    <input type="number" name="platform_fee" value="{{ old('platform_fee', $settings['platform_fee']) }}" 
                           class="form-control @error('platform_fee') is-invalid @enderror" style="width: 100%; padding-left: 40px;">
                </div>
                <p class="caption" style="margin-top: 4px;">Biaya tetap yang dikenakan kepada pembeli per checkout pesanan.</p>
                @error('platform_fee')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="border-top: 1px solid var(--slate-800); padding-top: var(--space-6); display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="height: 48px; padding: 0 var(--space-6);">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
