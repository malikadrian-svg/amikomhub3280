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
            <div style="width: 32px; height: 32px; background-color: var(--purple-500); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-weight: 700;">AH</div>
            <span class="h3" style="margin:0; letter-spacing: 0.02em;">AMIKOMEVENTHUB</span>
        </div>
        <div class="nav-links">
            <a href="{{ url('/') }}" class="sidebar-item {{ request()->is('/') ? 'active' : '' }}" style="border-left: none; border-bottom: 2px solid {{ request()->is('/') ? 'var(--purple-500)' : 'transparent' }}; padding: var(--space-3) var(--space-2);">Home</a>
            <a href="{{ url('/#events') }}" class="sidebar-item" style="border-left: none; border-bottom: 2px solid transparent; padding: var(--space-3) var(--space-2);">Event</a>
            <a href="{{ url('/#categories') }}" class="sidebar-item" style="border-left: none; border-bottom: 2px solid transparent; padding: var(--space-3) var(--space-2);">Kategori</a>
        </div>
    </nav>

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