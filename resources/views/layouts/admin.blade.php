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
    
    <!-- Neo-Brutalism CSS -->
    <link rel="stylesheet" href="{{ asset('css/neo-brutalism.css') }}">
    
    <style>
        body {
            background-color: #ffffff;
            color: var(--slate-0);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling (Neo-Brutalist) */
        .admin-sidebar {
            width: 280px;
            background-color: var(--slate-800);
            border-right: 1px solid var(--slate-700);
            display: flex;
            flex-direction: column;
            padding: var(--space-6);
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow: 4px 0 0 var(--slate-900);
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
            background-color: var(--purple-500);
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--slate-700);
            box-shadow: var(--shadow-hard-sm);
        }

        .admin-sidebar-menu {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: var(--space-2);
        }

        .admin-sidebar-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--slate-400);
            margin-bottom: var(--space-2);
            padding: 0 var(--space-2);
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-4);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--slate-200);
            text-decoration: none;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .admin-nav-link:hover {
            color: var(--slate-0);
            background-color: var(--slate-700);
            border-color: var(--slate-600);
        }

        .admin-nav-link.active {
            background-color: var(--purple-500);
            color: #ffffff;
            border-color: #ffffff;
            box-shadow: var(--shadow-hard-sm);
        }
        
        .admin-nav-link.active svg {
            color: #ffffff;
            stroke: var(--slate-900);
        }

        .admin-logout {
            margin-top: auto;
            border-top: 2px solid var(--slate-700);
            padding-top: var(--space-4);
        }

        .admin-logout button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-4);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--feedback-error);
            background: none;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-logout button:hover {
            background-color: rgba(220, 38, 38, 0.1);
            border-color: var(--feedback-error);
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
            <span class="h4" style="color: var(--slate-0); margin: 0;">AmikomEventHub</span>
        </div>

        <nav class="admin-sidebar-menu">
            <p class="admin-sidebar-label">MAIN MENU</p>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>
            <a href="{{ url('/admin/events') }}" class="admin-nav-link {{ request()->is('admin/events*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Kelola Event
            </a>
            <a href="{{ url('/admin/transactions') }}" class="admin-nav-link {{ request()->is('admin/transactions*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Laporan Transaksi
            </a>
            <a href="{{ url('/admin/categories') }}" class="admin-nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Kategori
            </a>
            <a href="{{ route('admin.organizations.index') }}" class="admin-nav-link {{ request()->is('admin/organizations*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Penyelenggara
            </a>
            <a href="{{ route('admin.event-approvals.index') }}" class="admin-nav-link {{ request()->is('admin/event-approvals*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Persetujuan Event
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                </svg>
                Ulasan
            </a>
            <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Pengguna
            </a>
        </nav>

        <div class="admin-logout">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    KELUAR
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main" style="display: flex; flex-direction: column;">
        <!-- Top Nav for Notifications -->
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: var(--space-6); border-bottom: 1px solid var(--slate-800); padding-bottom: var(--space-4);">
            <div class="relative" style="position: relative;">
                <button id="notificationBtn" style="background: none; border: 1px solid var(--slate-700); background-color: var(--slate-900); padding: var(--space-2); cursor: pointer; color: var(--slate-200); display: flex; align-items: center; justify-content: center; position: relative;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span style="position: absolute; top: -5px; right: -5px; background-color: var(--feedback-error); color: var(--slate-0); font-size: 10px; font-weight: 700; padding: 2px 6px; border: 1px solid var(--slate-700);">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>
                <div id="notificationDropdown" style="display: none; position: absolute; right: 0; top: 100%; margin-top: var(--space-2); width: 320px; background-color: var(--slate-900); border: 2px solid var(--slate-700); box-shadow: 4px 4px 0 var(--slate-800); z-index: 50;">
                    <div style="padding: var(--space-3); border-bottom: 1px solid var(--slate-700); display: flex; justify-content: space-between; align-items: center;">
                        <h4 class="h5" style="margin: 0;">Notifikasi</h4>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: var(--purple-400); font-size: 12px; cursor: pointer; font-weight: 700;">Tandai Semua Dibaca</button>
                            </form>
                        @endif
                    </div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                            <a href="{{ $notification->data['action_url'] ?? '#' }}" style="display: block; padding: var(--space-3); border-bottom: 1px solid var(--slate-800); text-decoration: none; background-color: {{ $notification->read_at ? 'transparent' : 'var(--slate-800)' }};">
                                <p style="margin: 0; font-weight: 700; font-size: 13px; color: var(--slate-0);">{{ $notification->data['title'] }}</p>
                                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--slate-300);">{{ $notification->data['message'] }}</p>
                                <span style="font-size: 10px; color: var(--slate-500); margin-top: 4px; display: block;">{{ $notification->created_at->diffForHumans() }}</span>
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

    <script>
        document.getElementById('notificationBtn').addEventListener('click', function() {
            var dropdown = document.getElementById('notificationDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>
</html>
