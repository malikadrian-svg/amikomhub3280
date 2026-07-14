@extends('layouts.admin')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: var(--space-4); margin-bottom: var(--space-8);">
        <a href="{{ route('admin.categories.index') }}" class="btn" style="width: 48px; height: 48px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: var(--ink-0); color: var(--ink-950);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="h2" style="margin-bottom: var(--space-1);">FORM TAMBAH KATEGORI</h2>
            <p class="body" style="color: var(--ink-400);">Masukkan nama kategori baru untuk event.</p>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div style="padding: var(--space-8); display: flex; flex-direction: column; gap: var(--space-6);">
                <div class="form-group">
                    <label class="label">NAMA KATEGORI <span style="color: var(--feedback-error);">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input @error('name') border-red-500 @enderror" placeholder="Contoh: Olahraga" required>
                    @error('name')
                        <p class="caption" style="color: var(--feedback-error); margin-top: var(--space-1); font-weight: 700;">{{ $message }}</p>
                    @enderror
                    <p class="caption" style="color: var(--ink-400); margin-top: var(--space-2); font-weight: 700;">Slug akan digenerate otomatis dari nama kategori.</p>
                </div>
            </div>

            <div style="padding: var(--space-6) var(--space-8); border-top: 4px solid var(--ink-950); display: flex; justify-content: flex-end; gap: var(--space-4); background-color: var(--amber-500);">
                <a href="{{ route('admin.categories.index') }}" class="btn" style="background-color: var(--ink-0); color: var(--ink-950);">BATAL</a>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: var(--space-2);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    SIMPAN DATA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
