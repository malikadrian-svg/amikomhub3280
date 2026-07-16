@extends('layouts.admin')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; align-items: center; gap: var(--space-4); margin-bottom: var(--space-8);">
        <a href="{{ route('admin.partners.index') }}" class="btn" style="width: 48px; height: 48px; padding: 0; display: flex; align-items: center; justify-content: center; background-color: var(--slate-0); color: #ffffff;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="h2" style="margin-bottom: var(--space-1);">FORM TAMBAH PARTNER</h2>
            <p class="body" style="color: var(--slate-400);">Lengkapi form di bawah ini untuk mendaftarkan partner baru.</p>
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
        <form action="{{ route('admin.partners.store') }}" method="POST">
            @csrf
            
            <div style="padding: var(--space-8); display: flex; flex-direction: column; gap: var(--space-6);">
                <div class="form-group">
                    <label class="label">NAMA PARTNER <span style="color: var(--feedback-error);">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input @error('name') border-red-500 @enderror" placeholder="Contoh: Amikom University" required>
                    @error('name')
                        <p class="caption" style="color: var(--feedback-error); margin-top: var(--space-1); font-weight: 700;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="label">LOGO URL <span style="color: var(--feedback-error);">*</span></label>
                    <input type="url" name="logo_url" id="logo_url_input" value="{{ old('logo_url') }}" class="input @error('logo_url') border-red-500 @enderror" placeholder="https://placehold.co/200x200" required>
                    @error('logo_url')
                        <p class="caption" style="color: var(--feedback-error); margin-top: var(--space-1); font-weight: 700;">{{ $message }}</p>
                    @enderror
                    <p class="caption" style="color: var(--slate-400); margin-top: var(--space-2); font-weight: 700;">Masukkan URL valid yang mengarah ke gambar logo.</p>

                    <div id="logo-preview-container" style="margin-top: var(--space-4); {{ old('logo_url') ? '' : 'display: none;' }}">
                        <p class="caption" style="margin-bottom: var(--space-2); font-weight: 700;">PREVIEW LOGO:</p>
                        <div style="width: 80px; height: 80px; border: 2px dashed var(--slate-900); background-color: var(--slate-0); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img id="logo-preview" src="{{ old('logo_url', '') }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'" onload="this.style.display='block'">
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding: var(--space-6) var(--space-8); border-top: 1px solid var(--slate-700); display: flex; justify-content: flex-end; gap: var(--space-4); background-color: var(--purple-500);">
                <a href="{{ route('admin.partners.index') }}" class="btn" style="background-color: var(--slate-0); color: #ffffff;">BATAL</a>
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: var(--space-2);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    SIMPAN DATA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const logoInput = document.getElementById('logo_url_input');
    const logoPreview = document.getElementById('logo-preview');
    const previewContainer = document.getElementById('logo-preview-container');

    logoInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            logoPreview.src = url;
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
@endsection
