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
            background-color: var(--ink-950);
            color: var(--ink-0);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling (Neo-Brutalist) */
        .admin-sidebar {
            width: 280px;
            background-color: var(--ink-900);
            border-right: 4px solid var(--ink-950);
            display: flex;
            flex-direction: column;
            padding: var(--space-6);
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow: 4px 0 0 var(--ink-950);
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
            background-color: var(--amber-500);
            color: var(--ink-950);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--ink-950);
            box-shadow: 2px 2px 0 var(--ink-950);
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
            color: var(--ink-400);
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
            color: var(--ink-200);
            text-decoration: none;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .admin-nav-link:hover {
            color: var(--ink-0);
            background-color: var(--ink-800);
            border-color: var(--ink-700);
        }

        .admin-nav-link.active {
            background-color: var(--amber-500);
            color: var(--ink-950);
            border-color: var(--ink-950);
            box-shadow: 2px 2px 0 var(--ink-950);
        }
        
        .admin-nav-link.active svg {
            color: var(--ink-950);
            stroke: var(--ink-950);
        }

        .admin-logout {
            margin-top: auto;
            border-top: 2px solid var(--ink-800);
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
            <span class="h4" style="color: var(--ink-0); margin: 0;">AmikomEventHub</span>
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
            <a href="{{ url('/admin/partners') }}" class="admin-nav-link {{ request()->is('admin/partners*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Partner
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

    <main class="admin-main">
        @yield('content')
    </main>

</body>
</html>
