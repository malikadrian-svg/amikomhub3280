@extends('layouts.organizer')

@section('content')
<div style="margin-bottom: var(--space-8); display: flex; align-items: center; gap: var(--space-4);">
    <a href="{{ route('organizer.events.index', request()->route('organization')) }}" style="width: 40px; height: 40px; border-radius: var(--radius-md); background: #f1f5f9; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #6b7280; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--purple-50)';this.style.color='var(--purple-600)'" onmouseout="this.style.background='#f1f5f9';this.style.color='var(--slate-500)'">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="display" style="margin-bottom: var(--space-1); color: #1e293b;">Buat Event Baru</h1>
        <p class="body-sm" style="color: #6b7280; margin: 0;">Event akan disimpan sebagai draf terlebih dahulu dan perlu diajukan untuk ditayangkan.</p>
    </div>
</div>

@if ($errors->any())
    <div style="background: rgba(220,38,38,0.07); border: 1px solid rgba(220,38,38,0.2); border-radius: var(--radius-md); padding: var(--space-4) var(--space-5); margin-bottom: var(--space-6);">
        <p style="font-weight: 600; color: var(--feedback-error); margin: 0 0 var(--space-2) 0;">Terdapat kesalahan pada form:</p>
        <ul style="margin: 0; padding-left: var(--space-5); color: var(--feedback-error);">
            @foreach ($errors->all() as $error)
                <li style="font-size: 13px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card" style="max-width: 860px; padding: var(--space-8);">
    <form action="{{ route('organizer.events.store', request()->route('organization')) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6); margin-bottom: var(--space-6);">

            {{-- Title (full width) --}}
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Judul Event <span style="color: var(--feedback-error);">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="Contoh: Konser Amal 2026">
                @error('title') <p style="color: var(--feedback-error); font-size: 12px; margin: var(--space-1) 0 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Kategori <span style="color: var(--feedback-error);">*</span></label>
                <select name="category_id" required class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">Pilih Kategori...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p style="color: var(--feedback-error); font-size: 12px; margin: var(--space-1) 0 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- Location --}}
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Lokasi <span style="color: var(--feedback-error);">*</span></label>
                <input type="text" name="location" value="{{ old('location') }}" required
                    class="form-control @error('location') is-invalid @enderror"
                    placeholder="Contoh: Stadion Utama, Online, dll">
                @error('location') <p style="color: var(--feedback-error); font-size: 12px; margin: var(--space-1) 0 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- Start Date --}}
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Tanggal Mulai <span style="color: var(--feedback-error);">*</span></label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                    class="form-control @error('start_date') is-invalid @enderror">
                @error('start_date') <p style="color: var(--feedback-error); font-size: 12px; margin: var(--space-1) 0 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- End Date --}}
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Tanggal Selesai <span style="color: var(--feedback-error);">*</span></label>
                <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required
                    class="form-control @error('end_date') is-invalid @enderror">
                @error('end_date') <p style="color: var(--feedback-error); font-size: 12px; margin: var(--space-1) 0 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- Image (full width) --}}
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Banner / Poster Event <span style="color: var(--feedback-error);">*</span></label>
                <input type="file" name="image" accept="image/*" required
                    class="form-control @error('image') is-invalid @enderror"
                    style="padding: var(--space-2);">
                <p class="caption" style="color: var(--slate-400); margin: var(--space-2) 0 0 0;">Format: JPG, PNG, WEBP. Maksimal: 5MB.</p>
                @error('image') <p style="color: var(--feedback-error); font-size: 12px; margin: var(--space-1) 0 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- Description (full width) --}}
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: var(--space-2);">Deskripsi Lengkap <span style="color: var(--feedback-error);">*</span></label>
                <textarea name="description" rows="6" required minlength="50"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Ceritakan detail event Anda (Syarat & Ketentuan, Pengisi Acara, dll)...">{{ old('description') }}</textarea>
                @error('description') <p style="color: var(--feedback-error); font-size: 12px; margin: var(--space-1) 0 0 0;">{{ $message }}</p> @enderror
            </div>
        </div>

        <div style="border-top: 1px solid #f1f5f9; padding-top: var(--space-6); display: flex; justify-content: flex-end; gap: var(--space-3);">
            <a href="{{ route('organizer.events.index', request()->route('organization')) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: var(--space-2);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Sebagai Draf
            </button>
        </div>
    </form>
</div>
@endsection
