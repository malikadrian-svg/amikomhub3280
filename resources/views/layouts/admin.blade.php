<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - AmikomEventHub</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- PWA Manifest & Theme Color -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#7c3aed">

    <!-- Neo-Brutalism CSS -->
    <link rel="stylesheet" href="{{ asset('css/neo-brutalism.css') }}">
    
    <style>
        body {
            background-color: #f8fafc;
            color: #0f172a;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 280px;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: var(--space-6);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 10;
        }

        .admin-sidebar-brand {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            margin-bottom: var(--space-8);
        }

        .admin-sidebar-brand .logo {
            width: 48px;
            height: 48px;
            background-color: var(--purple-600);
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
        }

        .admin-sidebar-menu {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .admin-sidebar-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6b7280;
            margin-bottom: 8px;
            margin-top: 8px;
            padding: 0 12px;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .admin-nav-link svg {
            flex-shrink: 0;
            color: #6b7280;
        }

        .admin-nav-link:hover {
            color: var(--purple-600);
            background-color: #f5f3ff;
        }

        .admin-nav-link:hover svg {
            color: var(--purple-600);
        }

        .admin-nav-link.active {
            background-color: var(--purple-600);
            color: #ffffff;
        }

        .admin-nav-link.active svg {
            color: #ffffff;
        }

        .admin-logout {
            margin-top: auto;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
        }

        .admin-logout button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #dc2626;
            background: none;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-logout button:hover {
            background-color: rgba(220, 38, 38, 0.06);
        }

        /* Main Content Area */
        .admin-main {
            flex: 1;
            padding: var(--space-8) var(--space-10);
            overflow-y: auto;
        }
    </style>
</head>

<body>

    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">
            <div class="logo">AH</div>
            <span style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 18px; color: #1e293b; margin: 0;">AmikomHub</span>
        </div>

        <nav class="admin-sidebar-menu">
            <p class="admin-sidebar-label">MAIN MENU</p>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                    <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                    <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                    <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                </svg>
                Dashboard
            </a>
            <a href="{{ url('/admin/events') }}" class="admin-nav-link {{ request()->is('admin/events*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Kelola Event
            </a>
            <a href="{{ url('/admin/transactions') }}" class="admin-nav-link {{ request()->is('admin/transactions*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Laporan Transaksi
            </a>
            <a href="{{ url('/admin/categories') }}" class="admin-nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
                Kategori
            </a>
            <a href="{{ route('admin.organizations.index') }}" class="admin-nav-link {{ request()->is('admin/organizations*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Penyelenggara
            </a>
            <a href="{{ route('admin.event-approvals.index') }}" class="admin-nav-link {{ request()->is('admin/event-approvals*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Persetujuan Event
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Ulasan
            </a>
            <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                Pengaturan
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Pengguna
            </a>
        </nav>

        <div class="admin-logout">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    KELUAR
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main" style="display: flex; flex-direction: column;">
        <!-- Top Nav for Notifications -->
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 32px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px;">
            <div class="relative" style="position: relative;">
                <button id="notificationBtn" style="background: none; border: 1px solid #e2e8f0; background-color: #f8fafc; padding: 8px; cursor: pointer; color: #374151; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; position: relative;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span style="position: absolute; top: -5px; right: -5px; background-color: var(--feedback-error); color: #ffffff; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 50%;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>
                <div id="notificationDropdown" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 8px; width: 320px; background-color: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-radius: var(--radius-lg); z-index: 50;">
                    <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #1e293b;">Notifikasi</h4>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: var(--purple-600); font-size: 12px; cursor: pointer; font-weight: 700;">Tandai Semua Dibaca</button>
                            </form>
                        @endif
                    </div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                            <a href="{{ $notification->data['action_url'] ?? '#' }}" style="display: block; padding: 16px 24px; border-bottom: 1px solid #f1f5f9; text-decoration: none; background-color: {{ $notification->read_at ? 'transparent' : '#f5f3ff' }}; transition: background-color 0.2s;">
                                <p style="margin: 0; font-weight: 700; font-size: 13px; color: #1e293b;">{{ $notification->data['title'] }}</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: #475569;">{{ $notification->data['message'] }}</p>
                                <span style="font-size: 10px; color: #94a3b8; margin-top: 4px; display: block;">{{ $notification->created_at->diffForHumans() }}</span>
                            </a>
                        @empty
                            <div style="padding: var(--space-4); text-align: center; color: var(--slate-400); font-size: 13px;">
                                Belum ada notifikasi.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div style="flex: 1;">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.getElementById('notificationBtn').addEventListener('click', function() {
            var dropdown = document.getElementById('notificationDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
    </script>
    @stack('scripts')

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>
