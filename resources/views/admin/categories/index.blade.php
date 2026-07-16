@extends('layouts.admin')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-8); flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h2 class="h2" style="margin-bottom: var(--space-2);">MANAJEMEN KATEGORI</h2>
            <p class="body" style="color: var(--slate-400);">Atur kategori event yang tersedia di platform.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah').style.display='flex'" class="btn btn-primary" style="display: flex; align-items: center; gap: var(--space-2);">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"></path>
            </svg>
            TAMBAH KATEGORI
        </button>
    </div>

    @if(session('success'))
        <div id="flash-success" style="background-color: var(--feedback-success); color: var(--slate-0); padding: var(--space-4); border: 1px solid var(--slate-700); margin-bottom: var(--space-6); display: flex; align-items: center; gap: var(--space-3); box-shadow: var(--shadow-hard-sm);">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span style="font-weight: 700;">{{ session('success') }}</span>
        </div>
    @endif

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
        <div style="padding: var(--space-6); border-bottom: 1px solid var(--slate-700); background-color: var(--purple-500);">
            <form method="GET" action="{{ route('admin.categories.index') }}" style="display: flex; gap: var(--space-4); flex-wrap: wrap; align-items: center;">
                <div style="flex: 1; min-width: 250px; position: relative;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--slate-400);">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="CARI NAMA KATEGORI..." class="input" style="padding-left: 48px;">
                </div>
                <button type="submit" class="btn btn-primary" style="background-color: #ffffff; color: var(--slate-0);">
                    CARI
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}" class="btn" style="background-color: var(--slate-0); color: #ffffff;">
                        RESET
                    </a>
                @endif
            </form>
            @if(request('search'))
                <p class="caption" style="margin-top: var(--space-4); font-weight: 700; color: #ffffff;">MENAMPILKAN HASIL UNTUK: <span style="background-color: var(--slate-0); padding: 2px 8px; border: 1px solid var(--slate-700);">{{ request('search') }}</span> — {{ $categories->count() }} DATA</p>
            @endif
        </div>

        <div style="overflow-x: auto;">
            <table class="table" style="margin: 0; border: none; box-shadow: none;">
                <thead>
                    <tr>
                        <th style="border-left: none; width: 64px; text-align: center;">NO</th>
                        <th>NAMA KATEGORI</th>
                        <th>JUMLAH EVENT</th>
                        <th>DIBUAT PADA</th>
                        <th style="border-right: none; text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td style="border-left: none; font-weight: 700; color: var(--slate-400); text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--space-4);">
                                <div style="width: 48px; height: 48px; border: 2px solid var(--slate-600); background-color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-hard-sm);">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24" style="color: var(--slate-400);">
                                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="body" style="font-weight: 700; margin: 0; text-transform: uppercase;">{{ $category->name }}</p>
                                    <p class="caption" style="color: var(--slate-400); margin: 0;">{{ $category->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: #ffffff; color: var(--slate-0); border-color: var(--slate-600);">
                                {{ $category->events_count }} EVENT
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--space-2); color: var(--slate-200); font-weight: 500; font-size: 14px;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $category->created_at ? $category->created_at->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td style="border-right: none; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: var(--space-2);">
                                <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}')" class="btn" style="padding: var(--space-2); background-color: var(--slate-700); color: var(--slate-0); border: 2px solid var(--slate-600);">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Anda yakin ingin menghapus kategori \'{{ addslashes($category->name) }}\' secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="padding: var(--space-2); background-color: transparent; border: 2px solid var(--error-border); color: var(--error-border);">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: var(--space-10); border: none;">
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <div style="width: 80px; height: 80px; border: 4px solid var(--slate-600); background-color: #ffffff; display: flex; align-items: center; justify-content: center; margin-bottom: var(--space-4); box-shadow: var(--shadow-hard-sm);">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="color: var(--slate-400);">
                                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <p class="h4" style="margin-bottom: var(--space-2);">BELUM ADA KATEGORI</p>
                                <p class="body" style="color: var(--slate-400);">
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

        <div style="padding: var(--space-4) var(--space-6); border-top: var(--border-width-default) solid var(--slate-600); background-color: var(--slate-800);">
            <p class="caption" style="font-weight: 700; color: var(--slate-200);">TOTAL: {{ $categories->count() }} KATEGORI</p>
        </div>
    </div>
</div>

<div id="modal-tambah" style="position: fixed; inset: 0; background-color: rgba(0,0,0,0.8); z-index: 50; display: none; align-items: center; justify-content: center; padding: var(--space-6);" onclick="if(event.target===this) this.style.display='none'">
    <div class="card" style="width: 100%; max-width: 500px; padding: 0; overflow: hidden; background-color: var(--slate-800);">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div style="padding: var(--space-6); border-bottom: 1px solid var(--slate-700); background-color: var(--purple-500);">
                <div style="display: flex; align-items: center; gap: var(--space-4);">
                    <div style="width: 48px; height: 48px; border: 1px solid var(--slate-700); background-color: var(--slate-0); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-hard-sm);">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="color: #ffffff;">
                            <path d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="h3" style="margin: 0; color: #ffffff;">TAMBAH KATEGORI</h3>
                    </div>
                </div>
            </div>
            
            <div style="padding: var(--space-6);">
                <div class="form-group">
                    <label class="label" style="color: var(--slate-200);">NAMA KATEGORI <span style="color: var(--feedback-error);">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Olahraga" class="form-control" required autofocus>
                    <p class="caption" style="margin-top: var(--space-2); font-weight: 700;">Slug akan digenerate otomatis.</p>
                </div>
            </div>
            
            <div style="padding: var(--space-6); border-top: 1px solid var(--slate-700); display: flex; gap: var(--space-4);">
                <button type="button" onclick="document.getElementById('modal-tambah').style.display='none'" class="btn" style="flex: 1; background-color: var(--slate-700); color: var(--slate-0); border: 2px solid var(--slate-600);">BATAL</button>
                <button type="submit" class="btn btn-primary" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: var(--space-2);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    SIMPAN
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit" style="position: fixed; inset: 0; background-color: rgba(0,0,0,0.8); z-index: 50; display: none; align-items: center; justify-content: center; padding: var(--space-6);" onclick="if(event.target===this) this.style.display='none'">
    <div class="card" style="width: 100%; max-width: 500px; padding: 0; overflow: hidden; background-color: var(--slate-800);">
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div style="padding: var(--space-6); border-bottom: 1px solid var(--slate-700); background-color: var(--purple-500);">
                <div style="display: flex; align-items: center; gap: var(--space-4);">
                    <div style="width: 48px; height: 48px; border: 1px solid var(--slate-700); background-color: var(--slate-0); display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-hard-sm);">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24" style="color: #ffffff;">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="h3" style="margin: 0; color: #ffffff;">EDIT KATEGORI</h3>
                    </div>
                </div>
            </div>
            
            <div style="padding: var(--space-6);">
                <div class="form-group">
                    <label class="label" style="color: var(--slate-200);">NAMA KATEGORI <span style="color: var(--feedback-error);">*</span></label>
                    <input type="text" name="name" id="edit-name" placeholder="Nama kategori" class="form-control" required>
                    <p class="caption" style="margin-top: var(--space-2); font-weight: 700;">Slug akan diperbarui otomatis.</p>
                </div>
            </div>
            
            <div style="padding: var(--space-6); border-top: 1px solid var(--slate-700); display: flex; gap: var(--space-4);">
                <button type="button" onclick="document.getElementById('modal-edit').style.display='none'" class="btn" style="flex: 1; background-color: var(--slate-700); color: var(--slate-0); border: 2px solid var(--slate-600);">BATAL</button>
                <button type="submit" class="btn btn-primary" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: var(--space-2);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name) {
        document.getElementById('edit-name').value = name;
        document.getElementById('form-edit').action = '/admin/categories/' + id;
        document.getElementById('modal-edit').style.display = 'flex';
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
        document.getElementById('modal-tambah').style.display = 'flex';
    @endif
</script>
@endsection
