<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <!-- Ganti Tailwind dengan Neo-Brutalism CSS -->
    <link rel="stylesheet" href="{{ asset('css/neo-brutalism.css') }}">
    <style>
        /* Utility classes khusus untuk spacing layout jika diperlukan di tingkat atas */
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-4);
        }
        .footer-section {
            background-color: #ffffff;
            border-top: 2px solid var(--slate-600);
            padding: var(--space-8) var(--space-4);
            margin-top: var(--space-12);
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: var(--space-6);
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-bottom {
            border-top: 1px solid var(--slate-600);
            padding-top: var(--space-4);
            margin-top: var(--space-6);
            text-align: center;
        }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .gap-2 { gap: var(--space-2); }
        .gap-3 { gap: var(--space-3); }
        .gap-4 { gap: var(--space-4); }
        .justify-between { justify-content: space-between; }
        .mt-4 { margin-top: var(--space-4); }
        .mb-2 { margin-bottom: var(--space-2); }
        .mb-4 { margin-bottom: var(--space-4); }
        .text-center { text-align: center; }
        .list-unstyled { list-style: none; padding: 0; margin: 0; }
        .list-unstyled li { margin-bottom: var(--space-2); }
        .list-unstyled a { color: var(--slate-200); text-decoration: none; font-family: 'IBM Plex Mono', monospace; font-size: 13px; }
        .list-unstyled a:hover { color: var(--slate-0); text-decoration: underline; }
    </style>
</head>

<body>

    <nav class="navbar" style="position: sticky; top: 0; z-index: 50;">
        <div class="d-flex align-center gap-2">
            <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                <div style="width: 32px; height: 32px; background-color: var(--purple-500); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700;">AH</div>
                <span class="h3" style="margin:0; letter-spacing: 0.02em; color: var(--slate-0);">AMIKOMEVENTHUB</span>
            </a>
        </div>

        <div class="nav-links" style="display: flex; align-items: center; gap: var(--space-1);">
            <a href="{{ url('/') }}" class="sidebar-item {{ request()->is('/') ? 'active' : '' }}" style="border-left: none; border-bottom: 2px solid {{ request()->is('/') ? 'var(--purple-500)' : 'transparent' }}; padding: var(--space-3) var(--space-2);">Home</a>
            <a href="{{ url('/#events') }}" class="sidebar-item" style="border-left: none; border-bottom: 2px solid transparent; padding: var(--space-3) var(--space-2);">Event</a>
            <a href="{{ url('/#categories') }}" class="sidebar-item" style="border-left: none; border-bottom: 2px solid transparent; padding: var(--space-3) var(--space-2);">Kategori</a>

            {{-- ── Auth State ─────────────────────────────────────────────── --}}
            @auth
                {{-- Tiket Saya link --}}
                <a href="{{ route('my-tickets') }}"
                   class="sidebar-item {{ request()->is('my-tickets') ? 'active' : '' }}"
                   style="border-left: none; border-bottom: 2px solid {{ request()->is('my-tickets') ? 'var(--purple-500)' : 'transparent' }}; padding: var(--space-3) var(--space-2); display: flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    Tiket Saya
                </a>

                {{-- User avatar + name dropdown trigger --}}
                <div style="position: relative; margin-left: var(--space-2);" id="user-menu-wrapper">
                    <button
                        id="user-menu-btn"
                        onclick="toggleUserMenu()"
                        style="display: flex; align-items: center; gap: 8px; background: none; border: 1.5px solid var(--slate-700); padding: 6px 12px 6px 6px; cursor: pointer; font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; color: var(--slate-0); transition: border-color 0.15s;"
                        onmouseover="this.style.borderColor='var(--purple-500)';"
                        onmouseout="this.style.borderColor='var(--slate-700)';"
                    >
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt=""
                                 style="width: 24px; height: 24px; border-radius: 50%; border: 1.5px solid var(--purple-500); object-fit: cover;">
                        @else
                            <div style="width: 24px; height: 24px; border-radius: 50%; background-color: var(--purple-500); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ explode(' ', Auth::user()->name)[0] }}
                        </span>
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24">
                            <path d="M6 9l6 6 6-6"></path>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div id="user-menu-dropdown"
                         style="display: none; position: absolute; top: calc(100% + 4px); right: 0; min-width: 180px; background-color: var(--slate-900); border: 2px solid var(--slate-700); box-shadow: 4px 4px 0 var(--slate-700); z-index: 100;">
                        <div style="padding: 10px 16px; border-bottom: 1px solid var(--slate-700);">
                            <p style="margin: 0; font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 700; color: var(--slate-0); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ Auth::user()->name }}
                            </p>
                            <p style="margin: 0; font-family: 'IBM Plex Mono', monospace; font-size: 11px; color: var(--slate-400); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <a href="{{ route('my-tickets') }}"
                           style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; text-decoration: none; font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; color: var(--slate-0); border-bottom: 1px solid var(--slate-700);"
                           onmouseover="this.style.backgroundColor='var(--slate-800)';"
                           onmouseout="this.style.backgroundColor='transparent';">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            TIKET SAYA
                        </a>
                        <form action="{{ route('user.logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit"
                                    style="display: flex; align-items: center; gap: 8px; width: 100%; padding: 10px 16px; background: none; border: none; text-align: left; font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; color: var(--error-text); cursor: pointer;"
                                    onmouseover="this.style.backgroundColor='var(--error-bg)';"
                                    onmouseout="this.style.backgroundColor='transparent';">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                KELUAR
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Guest: show login button --}}
                <a href="{{ route('google.login') }}"
                   style="display: inline-flex; align-items: center; gap: 6px; margin-left: var(--space-2); padding: 8px 16px; background-color: var(--purple-500); color: #ffffff; border: 1.5px solid var(--purple-700); font-family: 'IBM Plex Mono', monospace; font-size: 12px; font-weight: 700; text-decoration: none; letter-spacing: 0.03em; transition: background-color 0.15s, box-shadow 0.15s; box-shadow: 2px 2px 0 var(--purple-700);"
                   onmouseover="this.style.backgroundColor='var(--purple-600)'; this.style.boxShadow='3px 3px 0 var(--purple-700)';"
                   onmouseout="this.style.backgroundColor='var(--purple-500)'; this.style.boxShadow='2px 2px 0 var(--purple-700)';">
                    <svg width="12" height="12" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#fff" opacity="0.9" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                        <path fill="#fff" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#fff" opacity="0.8" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#fff" opacity="0.9" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    </svg>
                    MASUK
                </a>
            @endauth
        </div>
    </nav>

    <script>
        function toggleUserMenu() {
            const dd = document.getElementById('user-menu-dropdown');
            dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
        }
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('user-menu-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const dd = document.getElementById('user-menu-dropdown');
                if (dd) dd.style.display = 'none';
            }
        });
    </script>

    @yield('content')

    <footer class="footer-section">
        <div class="footer-grid">
            <div>
                <div class="d-flex align-center gap-2 mb-4">
                    <div style="width: 32px; height: 32px; background-color: var(--slate-0); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700;">AH</div>
                    <span class="h3" style="margin:0; letter-spacing: 0.02em;">AMIKOMEVENTHUB</span>
                </div>
                <p class="body" style="color: var(--slate-200); max-width: 300px;">Platform reservasi tiket event online bergaya brutalist. Pemesanan cepat, tegas, tanpa kompromi.</p>
            </div>
            <div>
                <h4 class="h5" style="margin-bottom: var(--space-3); color: var(--slate-400);">NAVIGASI</h4>
                <ul class="list-unstyled">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Semua Event</a></li>
                    <li><a href="#">Cara Bayar</a></li>
                </ul>
            </div>
            <div>
                <h4 class="h5" style="margin-bottom: var(--space-3); color: var(--slate-400);">HUBUNGI KAMI</h4>
                <ul class="list-unstyled">
                    <li><a href="mailto:support@eventtiket.com">support@eventtiket.com</a></li>
                    <li><a href="tel:+6281234567890">+62 812 3456 7890</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="caption" style="color: var(--slate-400);">&copy; 2024 AMIKOMEVENTHUB. BUILT WITH NEO-BRUTALISM.</p>
        </div>
    </footer>

</body>

</html>