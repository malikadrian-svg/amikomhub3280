@extends('layouts.admin')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: var(--space-4); margin-bottom: var(--space-8);">
        <a href="{{ route('admin.events.index') }}" class="btn" style="width: 48px; height: 48px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: var(--slate-0); color: #ffffff;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="h2" style="margin-bottom: var(--space-1);">FORM TAMBAH EVENT</h2>
            <p class="body" style="color: var(--slate-400);">Lengkapi form di bawah ini untuk membuat acara baru.</p>
        </div>
    </div>

    @if($errors->any())
        <div style="background-color: var(--feedback-error); color: var(--slate-0); padding: var(--space-6); border: 1px solid var(--slate-700); margin-bottom: var(--space-8); box-shadow: var(--shadow-hard-sm);">
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
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">  
            @csrf
            
            <div style="padding: var(--space-8); display: flex; flex-direction: column; gap: var(--space-6);">
                <div class="form-group">
                    <label class="label">JUDUL EVENT <span style="color: var(--feedback-error);">*</span></label>
                    <input type="text" name="title" class="input" placeholder="Contoh: Tech Seminar 2024" required>
                </div>

                <div class="form-group">
                    <label class="label">KATEGORI EVENT <span style="color: var(--feedback-error);">*</span></label>
                    <div style="position: relative;">
                        <select name="category_id" class="input" style="appearance: none;" required>
                            <option value="" disabled selected>Pilih Kategori...</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">PENYELENGGARA / PARTNER <span class="caption" style="color: var(--slate-400);">(Opsional)</span></label>
                    <div style="position: relative;">
                        <select name="partner_id" class="input" style="appearance: none;">
                            <option value="">— Tidak Ada / Pilih Nanti —</option>
                            @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                {{ $partner->name }}
                            </option>
                            @endforeach
                        </select>
                        <div style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <p class="caption" style="color: var(--slate-400); margin-top: var(--space-1);">Partner yang dipilih akan tampil di halaman detail event.</p>
                </div>

                <div class="form-group">
                    <label class="label">DESKRIPSI PENDEK <span style="color: var(--feedback-error);">*</span></label>
                    <textarea name="description" rows="4" class="input" placeholder="Tuliskan deskripsi singkat tentang acara ini..." required></textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-6);">
                    <div class="form-group">
                        <label class="label">TANGGAL & WAKTU <span style="color: var(--feedback-error);">*</span></label>
                        <input type="datetime-local" name="date" class="input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="label">HARGA TIKET (RP) <span style="color: var(--feedback-error);">*</span></label>
                        <div style="position: relative;">
                            <span class="body" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-weight: 700; color: var(--slate-400);">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" class="input @error('price') border-red-500 @enderror" style="padding-left: 48px;" placeholder="0" required>
                        </div>
                        @error('price')
                            <p class="caption" style="color: var(--feedback-error); margin-top: var(--space-1); font-weight: 700;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="label">KAPASITAS STOK <span style="color: var(--feedback-error);">*</span></label>
                        <input type="number" name="stock" class="input" placeholder="100" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">LOKASI / GEDUNG <span style="color: var(--feedback-error);">*</span></label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--slate-400);">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </span>
                        <input type="text" name="location" class="input" style="padding-left: 56px;" placeholder="Contoh: Gedung Rektorat, Ruang Rapat 1" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="label">POSTER EVENT (OPSIONAL)</label>
                    <input type="file" name="poster" accept="image/*" class="input" style="padding: var(--space-2);">
                </div>
            </div>

            <div style="padding: var(--space-6) var(--space-8); border-top: 1px solid var(--slate-700); display: flex; justify-content: flex-end; gap: var(--space-4); background-color: var(--purple-500);">
                <a href="{{ route('admin.events.index') }}" class="btn" style="background-color: var(--slate-0); color: #ffffff;">BATAL</a>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: var(--space-2);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    SIMPAN DATA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection