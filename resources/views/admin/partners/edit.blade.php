@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.partners.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm hover:shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Form Edit Partner</h2>
            <p class="text-sm text-slate-500 mt-1">Ubah rincian informasi partner di bawah ini.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl mb-6 border border-rose-200 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-7">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200 bg-white" onerror="this.src='https://placehold.co/200x200?text=N/A'">
                    <div>
                        <p class="font-bold text-slate-900">{{ $partner->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Ditambahkan: {{ $partner->created_at ? $partner->created_at->format('d M Y, H:i') : '-' }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Partner <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-all placeholder-slate-400 font-medium @error('name') border-red-400 bg-red-50 @enderror" placeholder="Contoh: Amikom University" required>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Logo URL <span class="text-red-500">*</span></label>
                    <input type="url" name="logo_url" id="logo_url_input" value="{{ old('logo_url', $partner->logo_url) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 block p-3.5 transition-all placeholder-slate-400 font-medium @error('logo_url') border-red-400 bg-red-50 @enderror" placeholder="https://placehold.co/200x200" required>
                    @error('logo_url')
                        <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 mt-1.5">Masukkan URL valid yang mengarah ke gambar logo.</p>

                    <div id="logo-preview-container" class="mt-4">
                        <p class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wide">Preview Logo Baru:</p>
                        <div class="w-20 h-20 rounded-xl border-2 border-dashed border-slate-200 overflow-hidden bg-slate-50 flex items-center justify-center">
                            <img id="logo-preview" src="{{ old('logo_url', $partner->logo_url) }}" alt="Preview" class="w-full h-full object-cover" onerror="this.style.display='none'" onload="this.style.display='block'">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-2xl">
                <a href="{{ route('admin.partners.index') }}" class="px-6 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const logoInput = document.getElementById('logo_url_input');
    const logoPreview = document.getElementById('logo-preview');

    logoInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            logoPreview.src = url;
        }
    });
</script>
@endsection
