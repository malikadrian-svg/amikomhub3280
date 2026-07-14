@extends('layouts.app')

@section('content')
<div class="page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: var(--space-8) var(--space-4);">
    <div style="width: 100%; max-width: 480px;">
        <div class="card" style="padding: var(--space-8);">
            <div style="text-align: center; margin-bottom: var(--space-8);">
                <div style="width: 64px; height: 64px; background-color: var(--amber-500); color: var(--ink-950); border: 4px solid var(--ink-950); display: inline-flex; align-items: center; justify-content: center; margin-bottom: var(--space-4); box-shadow: 4px 4px 0 var(--ink-950);">
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="h2" style="margin-bottom: var(--space-2);">SELAMAT DATANG!</h2>
                <p class="body" style="color: var(--ink-400);">Silakan login ke akun Anda</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" style="display: flex; flex-direction: column; gap: var(--space-6);">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="label">ALAMAT EMAIL</label>
                    <input id="email" name="email" type="email" required class="input" placeholder="contoh@email.com">
                </div>

                <div class="form-group">
                    <label for="password" class="label">PASSWORD</label>
                    <input id="password" name="password" type="password" required class="input" placeholder="Masukkan password Anda">
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: var(--space-2);">
                        <input id="remember-me" name="remember-me" type="checkbox" style="width: 24px; height: 24px; border: 2px solid var(--ink-950); border-radius: 0; appearance: none; background-color: var(--ink-0); cursor: pointer;" onclick="this.style.backgroundColor = this.checked ? 'var(--amber-500)' : 'var(--ink-0)';">
                        <label for="remember-me" class="body" style="cursor: pointer; font-weight: 700;">Ingat saya</label>
                    </div>
                    <div>
                        <a href="#" class="body" style="color: var(--amber-600); font-weight: 700; text-decoration: underline;">Lupa password?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="padding: var(--space-4); font-size: 18px; margin-top: var(--space-4);">
                    MASUK
                </button>
            </form>

            <div style="margin-top: var(--space-8); text-align: center; border-top: 2px solid var(--ink-700); padding-top: var(--space-6);">
                <span class="body" style="color: var(--ink-200);">Belum punya akun?</span> 
                <a href="#" class="body" style="color: var(--ink-0); font-weight: 700; text-decoration: underline;">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
