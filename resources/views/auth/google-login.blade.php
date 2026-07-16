@extends('layouts.app')

@section('title', 'Masuk ke AmikomEventHub')

@section('content')
<main style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: var(--space-6) var(--space-4);">
    <div style="width: 100%; max-width: 420px;">

        {{-- Logo / Brand --}}
        <div style="text-align: center; margin-bottom: var(--space-8);">
            <div style="width: 64px; height: 64px; background-color: var(--purple-500); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 24px; margin: 0 auto var(--space-4) auto; box-shadow: 4px 4px 0 var(--purple-700);">
                AH
            </div>
            <h1 class="h2" style="margin: 0 0 var(--space-1) 0;">MASUK KE AKUN</h1>
            <p class="body" style="color: var(--slate-400); margin: 0;">Gunakan akun Google Anda untuk melanjutkan.</p>
        </div>

        {{-- Card --}}
        <div class="card" style="padding: var(--space-6); text-align: center;">

            {{-- Error Message --}}
            @if(session('error'))
                <div style="
                    background-color: var(--error-bg);
                    border: 2px solid var(--error-border);
                    color: var(--error-text);
                    padding: var(--space-2) var(--space-3);
                    margin-bottom: var(--space-4);
                    display: flex;
                    align-items: flex-start;
                    gap: var(--space-2);
                    text-align: left;
                    font-family: 'IBM Plex Mono', monospace;
                    font-size: 13px;
                    font-weight: 500;
                ">
                    <svg style="flex-shrink: 0; margin-top: 2px;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Descriptive text --}}
            <p class="body" style="color: var(--slate-200); margin-bottom: var(--space-6); line-height: 1.7;">
                Untuk membeli tiket, Anda perlu masuk terlebih dahulu.
                Proses ini cepat, aman, dan tidak memerlukan password baru.
            </p>

            {{-- Continue with Google Button --}}
            <a
                href="{{ route('google.redirect') }}"
                id="google-login-btn"
                style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: var(--space-2);
                    width: 100%;
                    padding: 14px var(--space-4);
                    background-color: #ffffff;
                    color: var(--slate-0);
                    border: 2px solid var(--slate-700);
                    font-family: 'Space Grotesk', sans-serif;
                    font-weight: 700;
                    font-size: 15px;
                    letter-spacing: 0.03em;
                    text-decoration: none;
                    transition: background-color 0.15s, box-shadow 0.15s, transform 0.1s;
                    box-shadow: 3px 3px 0 var(--slate-700);
                    cursor: pointer;
                "
                onmouseover="this.style.backgroundColor='#f8fafc'; this.style.boxShadow='4px 4px 0 var(--slate-700)'; this.style.transform='translate(-1px,-1px)';"
                onmouseout="this.style.backgroundColor='#ffffff'; this.style.boxShadow='3px 3px 0 var(--slate-700)'; this.style.transform='translate(0,0)';"
                onclick="handleGoogleLogin(event)"
            >
                {{-- Official Google Icon SVG --}}
                <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                LANJUTKAN DENGAN GOOGLE
            </a>

            {{-- Loading state --}}
            <div id="google-loading" style="display: none; margin-top: var(--space-3);">
                <p class="caption" style="color: var(--slate-400); display: flex; align-items: center; justify-content: center; gap: var(--space-1);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" style="animation: spin 1s linear infinite;">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                    Menghubungi Google...
                </p>
            </div>

            {{-- Divider --}}
            <div style="border-top: 1px solid var(--slate-700); margin: var(--space-4) 0;"></div>

            {{-- Security notice --}}
            <p class="caption" style="color: var(--slate-400); display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                Kami tidak menyimpan password Google Anda. Proses aman dan terenkripsi.
            </p>
        </div>

        {{-- Back link --}}
        <div style="text-align: center; margin-top: var(--space-4);">
            <a href="{{ route('home') }}" class="btn-text" style="color: var(--slate-400); font-size: 13px;">
                ← Kembali ke beranda
            </a>
        </div>

    </div>
</main>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
</style>

<script>
    function handleGoogleLogin(e) {
        const btn = document.getElementById('google-login-btn');
        const loading = document.getElementById('google-loading');

        // Show loading state for user feedback
        btn.style.opacity = '0.7';
        btn.style.pointerEvents = 'none';
        loading.style.display = 'block';

        // Allow the href navigation to proceed naturally
    }
</script>
@endsection
