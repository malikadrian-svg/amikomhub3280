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
            background-color: #ffffff;
            color: var(--slate-0);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 280px;
            background-color: #ffffff;
            border-right: 1px solid var(--slate-200);
            display: flex;
            flex-direction: column;
            padding: var(--space-6);
            position: sticky;
            top: 0;
            height: 100vh;
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
            gap: var(--space-2);
        }

        .admin-sidebar-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--slate-500);
            margin-bottom: var(--space-2);
            padding: 0 var(--space-2);
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-4);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            color: var(--slate-600);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .admin-nav-link:hover {
            color: var(--purple-600);
            background-color: var(--purple-50);
        }

        .admin-nav-link.active {
            background-color: var(--purple-600);
            color: #ffffff;
        }
        
        .admin-nav-link.active svg {
            color: #ffffff;
            stroke: currentColor;
        }

        .admin-logout {
            margin-top: auto;
            border-top: 1px solid var(--slate-200);
            padding-top: var(--space-4);
        }

        .admin-logout button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3) var(--space-4);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            color: var(--feedback-error);
            background: none;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-logout button:hover {
            background-color: rgba(220, 38, 38, 0.05);
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
        <div class="admin-sidebar-brand flex flex-col items-start gap-2">
            <div class="flex items-center gap-3">
                <div class="logo">AH</div>
                <span class="h4" style="color: var(--slate-900); margin: 0;">Organizer</span>
            </div>
            <div class="text-sm font-semibold text-purple-700 bg-purple-50 px-3 py-1 rounded-md w-full truncate border border-purple-100">
                {{ app(\App\Services\TenantContext::class)->get()->name ?? 'Penyelenggara' }}
            </div>
        </div>

        <nav class="admin-sidebar-menu">
            <p class="admin-sidebar-label">MENU PENYELENGGARA</p>
            <a href="{{ route('organizer.dashboard', request()->route('organization')) }}" class="admin-nav-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>
            
            @if(auth()->user()->hasPermission('events.view'))
            <a href="{{ route('organizer.events.index', request()->route('organization')) }}" class="admin-nav-link {{ request()->routeIs('organizer.events.*') ? 'active' : '' }}">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Manajemen Event
            </a>
            @endif

            @if(auth()->user()->hasPermission('orders.view'))
            <a href="#" onclick="alert('Fitur Pesanan & Tiket akan segera hadir di pembaruan selanjutnya!'); return false;" class="admin-nav-link">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Pesanan & Tiket
            </a>
            @endif

            @if(auth()->user()->hasPermission('organization.settings'))
            <a href="#" onclick="alert('Fitur Pengaturan akan segera hadir di pembaruan selanjutnya!'); return false;" class="admin-nav-link mt-4" style="border-top: 1px solid var(--slate-200); padding-top: 16px;">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="square" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan
            </a>
            @endif
        </nav>

        <div class="admin-logout">
            <form method="POST" action="{{ route('user.logout') }}">
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
