@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Kategori</h2>
            <p class="text-slate-500 mt-1 text-sm">Atur kategori event yang tersedia di platform.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')"
            class="group flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori
        </button>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 border border-emerald-200 flex items-center gap-3 shadow-sm" id="flash-success">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

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
        <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex gap-3 items-center">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama kategori..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-medium placeholder-slate-400">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all text-sm shadow-sm">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-100 transition-all text-sm">
                        Reset
                    </a>
                @endif
            </form>
            @if(request('search'))
                <p class="text-xs text-slate-500 mt-2">Menampilkan hasil pencarian untuk: <span class="font-semibold text-indigo-600">"{{ request('search') }}"</span> — {{ $categories->count() }} data ditemukan</p>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200">
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide w-16">No</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide">Nama Kategori</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide">Jumlah Event</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide">Dibuat Pada</th>
                        <th class="p-5 font-semibold text-slate-600 text-sm tracking-wide text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $index => $category)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="p-5 font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $category->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium">{{ $category->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold {{ $category->events_count > 0 ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $category->events_count }} Event
                            </span>
                        </td>
                        <td class="p-5">
                            <div class="flex items-center text-slate-600 text-sm font-medium">
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="p-5">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit Kategori">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus kategori \'{{ addslashes($category->name) }}\' secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Kategori">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-slate-700 mb-1">Belum ada kategori</p>
                                <p class="text-sm text-slate-400">
                                    @if(request('search'))
                                        Tidak ditemukan kategori dengan kata kunci "{{ request('search') }}".
                                    @else
                                        Mulai dengan menambahkan kategori pertama.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3 bg-slate-50/60 border-t border-slate-200">
            <p class="text-xs text-slate-400 font-medium">Total: {{ $categories->count() }} kategori terdaftar</p>
        </div>
    </div>
</div>

<div id="modal-tambah" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6" onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl transform transition-all">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tambah Kategori Baru</h3>
                        <p class="text-slate-500 text-sm">Masukkan nama kategori untuk event.</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Olahraga"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition font-medium text-sm placeholder-slate-400" required autofocus>
                    <p class="text-xs text-slate-400 mt-1.5">Slug akan digenerate otomatis dari nama kategori.</p>
                </div>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')"
                    class="flex-1 py-2.5 border border-slate-200 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-sm hover:bg-indigo-700 transition text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6" onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl transform transition-all">
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Edit Kategori</h3>
                        <p class="text-slate-500 text-sm">Ubah nama kategori yang dipilih.</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit-name" placeholder="Nama kategori"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition font-medium text-sm placeholder-slate-400" required>
                    <p class="text-xs text-slate-400 mt-1.5">Slug akan diperbarui otomatis sesuai nama baru.</p>
                </div>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="flex-1 py-2.5 border border-slate-200 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-sm hover:bg-indigo-700 transition text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name) {
        document.getElementById('edit-name').value = name;
        document.getElementById('form-edit').action = '/admin/categories/' + id;
        document.getElementById('modal-edit').classList.remove('hidden');
    }

    const flash = document.getElementById('flash-success');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.5s ease';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }, 4000);
    }

    @if($errors->any() && old('_method') === null)
        document.getElementById('modal-tambah').classList.remove('hidden');
    @endif
</script>
@endsection
