<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Taxi Portal')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600,700" rel="stylesheet" />

    <style>
        :root {
            --bg: #f5f7fb;
            --bg-accent: #eef2ff;
            --ink: #101828;
            --muted: #5b6473;
            --brand: #0f6bb6;
            --brand-2: #0a4f86;
            --brand-light: #eaf3ff;
            --line: #e3e8f0;
            --card: #ffffff;
            --chip: #e8f1fb;
            --success: #10b981;
            --success-bg: #dcfce7;
            --warning: #b97700;
            --warning-bg: #fef9c3;
            --danger: #b91c1c;
            --danger-bg: #fee2e2;
            --container-max-width: 1440px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            font-family: "DM Sans", "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            font-size: 13px;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        body::-webkit-scrollbar { display: none; }

        .page {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* SideBar */
        .sidebar {
            background: linear-gradient(180deg, #0b5fa5 0%, #0f6bb6 50%, #1b7dc8 100%);
            color: #fff;
            width: 240px;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 30px rgba(15, 107, 182, 0.2);
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 1000;
            flex-shrink: 0;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed { width: 70px; }

        .sidebar-header {
            padding: 20px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 64px;
            overflow: hidden;
        }

        .brand-badge {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 4px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            transition: opacity 0.2s;
            white-space: nowrap;
        }

        .sidebar.collapsed .brand-text { opacity: 0; pointer-events: none; }

        .brand-title { font-size: 13px; font-weight: 700; letter-spacing: -0.3px; }
        .brand-subtitle { font-size: 9px; opacity: 0.7; }

        .sidebar-nav {
            flex: 1;
            padding: 12px 0 60px 0;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin: 4px 10px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .nav-item:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }
        .nav-item.active { background: rgba(255, 255, 255, 0.15); color: #fff; font-weight: 700; }

        .nav-label { transition: opacity 0.2s; white-space: nowrap; }
        .sidebar.collapsed .nav-label { opacity: 0; pointer-events: none; }

        .nav-icon { width: 22px; height: 22px; flex-shrink: 0; stroke-width: 2; }

        /* Toggle Button */
        .toggle-btn {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 100;
            color: white;
        }

        .toggle-btn:hover { background: rgba(255, 255, 255, 0.25); transform: translateX(-50%) scale(1.05); }

        .main-content { flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* TopBar */
        .topbar {
            background: linear-gradient(90deg, #0b5fa5 0%, #0f6bb6 50%, #0b5fa5 100%);
            color: white;
            padding: 0 24px;
            min-height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 4px 20px rgba(15, 107, 182, 0.1);
        }

        .topbar-center { position: absolute; left: 50%; transform: translateX(-50%); pointer-events: none; }
        .topbar-badge { background: rgba(255,255,255,0.12); padding: 6px 20px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }

        .user-pill { display: flex; align-items: center; gap: 10px; padding: 4px 12px; border-radius: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); }
        .avatar { width: 30px; height: 30px; background: rgba(255,255,255,0.3); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; }

        .btn-topbar { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 10px; font-weight: 700; font-size: 12px; cursor: pointer; text-decoration: none; text-transform: uppercase; transition: all 0.2s; }
        .btn-topbar:hover { background: #ef4444; border-color: #ef4444; transform: translateY(-1px); }

        .content { padding: 24px; flex: 1; max-width: var(--container-max-width); margin: 0 auto; width: 100%; }

        /* Generic Styles */
        .card { background: white; border: 1px solid var(--line); border-radius: 20px; box-shadow: 0 10px 40px rgba(16, 24, 40, 0.05); padding: 24px; margin-bottom: 24px; overflow: hidden; }

        /* Table Styles */
        .table-container { width: 100%; overflow-x: auto; border-radius: 12px; background: white; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #f8fafc; padding: 14px 16px; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--muted); border-bottom: 2px solid var(--line); letter-spacing: 0.5px; white-space: nowrap; }
        td { padding: 16px; border-bottom: 1px solid #f1f5f9; color: var(--ink); font-size: 13px; vertical-align: middle; }

        /* Button Styles */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; border-radius: 12px; border: 1.5px solid transparent; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s ease; text-decoration: none; font-family: inherit; }
        .btn-primary { background: var(--brand); color: white; box-shadow: 0 4px 12px rgba(15, 107, 182, 0.2); }
        .btn-primary:hover { background: var(--brand-2); transform: translateY(-1px); }
        .btn-outline { background: white; border-color: var(--line); color: var(--muted); }
        .btn-outline:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-light); }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }

        /* Status Badges */
        .badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
        .badge-success { background: var(--success-bg); color: var(--success); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); }
        .badge-brand { background: var(--brand-light); color: var(--brand); }

        @media (max-width: 1024px) { .sidebar { position: fixed; left: -240px; height: 100vh; } .sidebar.active { left: 0; } }
    </style>
    @yield('styles')
</head>
<body>
    <div class="page">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="brand-badge">
                    <img src="{{ asset('images/logo-fundacion.png') }}" alt="Logo" style="height: 100%; width: 100%; object-fit: contain;">
                </div>
                <div class="brand-text">
                    <span class="brand-title">TAXI PREMIUM</span>
                    <span class="brand-subtitle">FUNDACIÓN SOFOFA</span>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('taxi.index') }}" class="nav-item {{ request()->routeIs('taxi.index') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    <span class="nav-label">Solicitar Taxi</span>
                </a>
                <a href="{{ route('taxi.history') }}" class="nav-item {{ request()->routeIs('taxi.history') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="nav-label">Mis Viajes</span>
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('taxi.gestor') }}" class="nav-item {{ request()->routeIs('taxi.gestor') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="nav-label">Gestor de Viajes</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="nav-label">Usuarios</span>
                </a>
                @endif
            </nav>
            <div class="toggle-btn" onclick="toggleSidebar()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div style="font-weight: 800; font-family: 'Space Grotesk'; font-size: 18px; color: white;">Dashboard</div>
                <div class="topbar-center">
                    <div class="topbar-badge">Portal de Gestión Corporativa</div>
                </div>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div class="user-pill">
                        <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 700; font-size: 11px;">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-topbar">Salir</button>
                    </form>
                </div>
            </header>
            <div class="content">@yield('content')</div>
        </main>
    </div>
    <script>
        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            s.classList.toggle('collapsed');
            localStorage.setItem('sidebar-collapsed', s.classList.contains('collapsed'));
        }
        if (localStorage.getItem('sidebar-collapsed') === 'true') document.getElementById('sidebar').classList.add('collapsed');
    </script>
    @yield('scripts')
</body>
</html>
