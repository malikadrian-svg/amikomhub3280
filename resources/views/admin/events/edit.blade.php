@extends('layouts.admin')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: var(--space-4); margin-bottom: var(--space-8);">
        <a href="{{ route('admin.events.index') }}" class="btn" style="width: 48px; height: 48px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: var(--ink-0); color: var(--ink-950);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="h2" style="margin-bottom: var(--space-1);">SUNTING PENGATURAN EVENT</h2>
            <p class="body" style="color: var(--ink-400);">Perbarui informasi event yang telah ada.</p>
        </div>
    </div>

    @if($errors->any())
        <div style="background-color: var(--feedback-error); color: var(--ink-0); padding: var(--space-6); border: 4px solid var(--ink-950); margin-bottom: var(--space-8); box-shadow: 4px 4px 0 var(--ink-950);">
            <div style="display: flex; align-items: center; gap: var(--space-2); margin-bottom: var(--space-2);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="body-lg" style="font-weight: 700;">TERJADI KESALAHAN:</span>
            </div>
            <ul style="list-style-type: square; margin-left: var(--space-6); font-weight: 500;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="padding: var(--space-8); display: flex; flex-direction: column; gap: var(--space-6);">
                <div class="form-group">
                    <label class="label">JUDUL EVENT <span style="color: var(--feedback-error);">*</span></label>
                    <input type="text" name="title" value="{{ $event->title }}" class="input" required>
                </div>

                <div class="form-group">
                    <label class="label">KATEGORI EVENT <span style="color: var(--feedback-error);">*</span></label>
                    <div style="position: relative;">
                        <select name="category_id" class="input" style="appearance: none;" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $event->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">DESKRIPSI PENDEK <span style="color: var(--feedback-error);">*</span></label>
                    <textarea name="description" rows="4" class="input" required>{{ $event->description }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-6);">
                    <div class="form-group">
                        <label class="label">TANGGAL & WAKTU <span style="color: var(--feedback-error);">*</span></label>
                        <input type="datetime-local" name="date" value="{{ $event->date }}" class="input" required>
                    </div>
                    <div class="form-group">
                        <label class="label">RENCANA HARGA MASUK (RP) <span style="color: var(--feedback-error);">*</span></label>
                        <div style="position: relative;">
                            <span class="body" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--ink-400);">Rp</span>
                            <input type="number" name="price" value="{{ old('price', $event->price) }}" class="input @error('price') border-red-500 @enderror" style="padding-left: 48px;" required>
                        </div>
                        @error('price')
                            <p class="caption" style="color: var(--feedback-error); margin-top: var(--space-1); font-weight: 700;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="label">KAPASITAS STOK KUOTA <span style="color: var(--feedback-error);">*</span></label>
                        <input type="number" name="stock" value="{{ $event->stock }}" class="input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">LOKASI / GEDUNG <span style="color: var(--feedback-error);">*</span></label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--ink-400);">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </span>
                        <input type="text" name="location" value="{{ $event->location }}" class="input" style="padding-left: 56px;" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">POSTER EVENT (OPSIONAL)</label>
                    @if($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                        <div style="margin-bottom: var(--space-4);">
                            <p class="caption" style="color: var(--ink-400); margin-bottom: var(--space-2);">POSTER SAAT INI:</p>
                            <div style="width: 120px; border: 2px solid var(--ink-950); box-shadow: 2px 2px 0 var(--ink-950); overflow: hidden; background-color: var(--ink-0);">
                                <img src="{{ asset('storage/' . $event->poster_path) }}" style="width: 100%; height: auto; display: block;">
                            </div>
                        </div>
                    @endif
                    <input type="file" name="poster" accept="image/*" class="input" style="padding: var(--space-2);">
                </div>
            </div>

            <div style="padding: var(--space-6) var(--space-8); border-top: 4px solid var(--ink-950); display: flex; justify-content: flex-end; gap: var(--space-4); background-color: var(--amber-500);">
                <a href="{{ route('admin.events.index') }}" class="btn" style="background-color: var(--ink-0); color: var(--ink-950);">BATAL</a>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: var(--space-2);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection