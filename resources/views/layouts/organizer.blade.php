<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Portal - AmikomEventHub</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
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
            flex-shrink: 0;
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
        <div class="admin-sidebar-brand" style="flex-direction: column; align-items: flex-start; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="logo">AH</div>
                <span style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 18px; color: #1e293b; margin: 0;">Organizer</span>
            </div>
            <div style="font-size: 12px; font-weight: 600; color: #7c3aed; background: #f5f3ff; padding: 4px 12px; border-radius: 6px; width: 100%; box-sizing: border-box; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border: 1px solid #ede9fe;">
                {{ app(\App\Services\TenantContext::class)->get()->name ?? 'Penyelenggara' }}
            </div>
        </div>

        <nav class="admin-sidebar-menu">
            <p class="admin-sidebar-label">MENU PENYELENGGARA</p>
            <a href="{{ route('organizer.dashboard', request()->route('organization')) }}" class="admin-nav-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                    <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                    <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                    <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                </svg>
                Dashboard
            </a>

            @if(auth()->user()->hasPermission('events.view'))
            <a href="{{ route('organizer.events.index', request()->route('organization')) }}" class="admin-nav-link {{ request()->routeIs('organizer.events.*') ? 'active' : '' }}">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Manajemen Event
            </a>
            @endif

            @if(auth()->user()->hasPermission('orders.view'))
            <a href="#" onclick="alert('Fitur Pesanan & Tiket akan segera hadir!'); return false;" class="admin-nav-link">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path>
                    <rect x="9" y="3" width="6" height="4" rx="1"></rect>
                    <line x1="9" y1="12" x2="15" y2="12"></line>
                    <line x1="9" y1="16" x2="13" y2="16"></line>
                </svg>
                Pesanan & Tiket
            </a>
            @endif

            @if(auth()->user()->hasPermission('organization.settings'))
            <a href="#" onclick="alert('Fitur Pengaturan akan segera hadir!'); return false;" class="admin-nav-link" style="margin-top: 8px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06-.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                Pengaturan
            </a>
            @endif
        </nav>

        <div class="admin-logout">
            <form method="POST" action="{{ route('user.logout') }}">
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

    <main class="admin-main">
        @yield('content')
    </main>

</body>
</html>
