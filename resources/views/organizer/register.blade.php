@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: var(--space-10) var(--space-4);">
    
    <div style="text-align: center; margin-bottom: var(--space-8);">
        <h1 class="h1">Daftar Menjadi Penyelenggara</h1>
        <p class="body-lg" style="color: var(--slate-400);">Bergabunglah dengan AmikomHub dan buat event Anda menjangkau audiens yang lebih luas. Isi form di bawah ini untuk memulai.</p>
    </div>

    <div class="card">
        <form action="{{ route('organizer.register.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Info -->
            <div class="card-header">
                <h3 class="h3" style="margin: 0;">Informasi Organisasi</h3>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-4);">
                <div class="form-group">
                    <label class="label">Nama Organisasi / Perusahaan <span style="color: var(--error-border);">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Contoh: Amikom Events">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label">Email Organisasi <span style="color: var(--error-border);">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="organisasi@email.com">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label class="label">Deskripsi <span style="color: var(--error-border);">*</span></label>
                <textarea name="description" required minlength="50"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Ceritakan tentang organisasi Anda secara detail... (Minimal 50 karakter)">{{ old('description') }}</textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-4);">
                <div class="form-group">
                    <label class="label">Nomor Telepon / WhatsApp <span style="color: var(--error-border);">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="08123456789">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label">Website (Opsional)</label>
                    <input type="url" name="website" value="{{ old('website') }}"
                        class="form-control @error('website') is-invalid @enderror"
                        placeholder="https://website.com">
                    @error('website') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: var(--space-8);">
                <label class="label">Alamat Lengkap <span style="color: var(--error-border);">*</span></label>
                <textarea name="address" required
                    class="form-control @error('address') is-invalid @enderror"
                    placeholder="Alamat kantor atau operasional Anda...">{{ old('address') }}</textarea>
                @error('address') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <!-- Documents -->
            <div class="card-header">
                <h3 class="h3" style="margin: 0;">Dokumen Verifikasi</h3>
            </div>
            
            <div class="alert alert-info">
                <p class="body-sm" style="margin: 0; color: var(--purple-700);">
                    <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Dokumen ini dibutuhkan untuk verifikasi keamanan platform. Data Anda akan disimpan dengan aman dan tidak dipublikasikan.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-8);">
                <div class="form-group">
                    <label class="label">KTP Penanggung Jawab <span style="color: var(--error-border);">*</span></label>
                    <input type="file" name="ktp_document" accept=".pdf,.jpg,.jpeg,.png" required
                        class="form-control @error('ktp_document') is-invalid @enderror" style="padding-top: 6px;">
                    <p class="caption" style="margin-top: 4px;">Format: JPG, PNG, PDF (Max: 5MB)</p>
                    @error('ktp_document') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label">Dokumen Legalitas (Opsional)</label>
                    <input type="file" name="legal_document" accept=".pdf"
                        class="form-control @error('legal_document') is-invalid @enderror" style="padding-top: 6px;">
                    <p class="caption" style="margin-top: 4px;">NIB / SIUP / NPWP Perusahaan. Format: PDF (Max: 10MB)</p>
                    @error('legal_document') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit -->
            <div style="border-top: var(--border-width-divider) solid var(--slate-800); padding-top: var(--space-4); display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="height: 48px; padding: 0 var(--space-6);">
                    Kirim Pendaftaran
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
