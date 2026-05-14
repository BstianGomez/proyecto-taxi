<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal de Viajes')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        :root {
            --bg: #f5f7fb;
            --bg-accent: #eef2ff;
            --ink: #101828;
            --muted: #5b6473;
            --brand: #0f6bb6;
            --brand-2: #0a4f86;
            --line: #e3e8f0;
            --card: #ffffff;
            --chip: #e8f1fb;
            --success: #0f7a3e;
            --warning: #b97700;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Transiciones suaves */
        .sidebar, .sidebar *, .nav-label, .brand-text {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-no-transition, .sidebar-no-transition * {
            transition: none !important;
        }

        /* SideBar Base */
        .sidebar {
            background: linear-gradient(180deg, #0b5fa5 0%, #0f6bb6 50%, #1b7dc8 100%);
            color: #fff;
            width: 220px;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 30px rgba(15, 107, 182, 0.2);
            position: relative;
            z-index: 100;
            min-height: 100vh;
        }

        .sidebar.collapsed {
            width: 64px;
        }
        
        /* Direct class for SSR/Initial Load */
        html.is-collapsed .sidebar { width: 64px; }
        html.is-collapsed .brand-text { opacity: 0; visibility: hidden; position: absolute; }
        html.is-collapsed .nav-label { opacity: 0; visibility: hidden; position: absolute; }
        html.is-collapsed .nav-item { justify-content: center; padding: 10px; margin: 2px 8px; }
        html.is-collapsed .toggle-icon { transform: rotate(180deg); }

        /* Sidebar Header & Brand */
        .sidebar-header {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 62px;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 14px 8px;
            justify-content: center;
        }

        .brand-badge {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 4px;
        }

        .brand-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .brand-subtitle {
            font-size: 10px;
            opacity: 0.7;
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        /* Nav Items */
        .sidebar-nav {
            flex: 1;
            padding: 12px 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            margin: 2px 8px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.15); 
            color: #fff;
            font-weight: 700;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: #fff;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .sidebar.collapsed .nav-label {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 10px;
            margin: 2px 8px;
        }

        .sidebar.collapsed .nav-item.active::before {
            left: -14px;
        }

        .nav-icon {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            stroke-width: 2px;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            bottom: 24px;
            left: 20px; /* Posición inferior izquierda */
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            display: grid;
            place-items: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.collapsed .toggle-btn {
            left: 20px; /* Se mantiene en la misma esquina */
            background: rgba(15, 107, 182, 0.6);
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.1);
        }

        .sidebar.collapsed .toggle-icon {
            transform: rotate(180deg);
        }

        /* Layout Base */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
        }

        body {
            background-color: var(--bg);
            font-family: "DM Sans", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
        }

        .page {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--bg);
            min-height: 100vh;
            height: 100vh;
            overflow-y: auto;
        }

        .content {
            padding: 24px 40px;
            max-width: 1500px;
            margin: 0 auto;
            width: 100%;
            flex: 1;
        }

        @media (max-width: 768px) {
            .content { padding: 20px; }
            .sidebar {
                position: fixed;
                left: -220px;
                height: 100vh;
                z-index: 1000;
            }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0 !important; }
        }

        /* TopBar from legacy */
        .topbar {
            background: linear-gradient(to right, #0a5494 0%, #0f6bb6 35%, #1470c2 50%, #0f6bb6 65%, #0a5494 100%);
            color: white;
            padding: 8px 24px;
            box-shadow: 0 4px 20px rgba(15, 107, 182, 0.15);
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            min-height: 54px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            backdrop-filter: blur(10px);
        }

        .topbar-inner {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar .brand-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .topbar .brand-subtitle {
            font-size: 11px;
            opacity: 0.8;
            display: block;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-logout {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 7px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
            text-decoration: none;
        }

        .btn-logout:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }

        /* Banner/Header style from legacy */
        .banner {
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            color: white;
            padding: 32px 40px;
            border-radius: 24px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(15, 107, 182, 0.15);
        }

        .banner::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .banner h1 {
            font-family: "Space Grotesk", sans-serif;
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .banner p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        /* Card and Forms from legacy */
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(16, 24, 40, 0.05);
            padding: 40px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 8px;
            display: block;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.2s;
            background-color: #fff;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
        }

        .mb-3 { margin-bottom: 20px; }

        .btn-primary {
            background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 107, 182, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }

        /* Tables from legacy */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 16px;
            font-size: 12px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 700;
            border-bottom: 1px solid var(--line);
        }

        td {
            padding: 16px;
            font-size: 14px;
            border-bottom: 1px solid var(--line);
        }

        tr:last-child td { border-bottom: none; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .content { padding: 20px; }
        }
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }

        .toast-item {
            min-width: 300px;
            max-width: 400px;
            background: white;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            pointer-events: auto;
            transform: translateX(120%);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 6px solid #cbd5e1;
        }

        .toast-item.active { transform: translateX(0); }
        .toast-item.success { border-left-color: #10b981; }
        .toast-item.error { border-left-color: #ef4444; }
        .toast-item.info { border-left-color: #3b82f6; }
        .toast-item.warning { border-left-color: #f59e0b; }

        .toast-icon {
            width: 32px; height: 32px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .success .toast-icon { background: #ecfdf5; color: #10b981; }
        .error .toast-icon { background: #fef2f2; color: #ef4444; }
        .info .toast-icon { background: #eff6ff; color: #3b82f6; }
        .warning .toast-icon { background: #fffbeb; color: #f59e0b; }

        .toast-content { flex: 1; }
        .toast-title { font-weight: 700; font-size: 14px; color: #1e293b; margin-bottom: 2px; }
        .toast-msg { font-size: 13px; color: #64748b; line-height: 1.4; }

        .toast-close {
            color: #94a3b8; cursor: pointer; padding: 4px; border-radius: 6px; transition: all 0.2s;
        }
        .toast-close:hover { background: #f1f5f9; color: #64748b; }
    </style>
    @stack('styles')
</head>
<body>
<script>
    // Immediate sidebar state restoration to prevent 'jump'
    (function() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            document.body.classList.add('sidebar-no-transition');
            document.documentElement.classList.add('is-collapsed');
        }
    })();
</script>
<div class="page">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-badge" style="background: white; padding: 4px; display: flex; align-items: center; justify-content: center;">
                <img src="/img/sofofa-full.png" alt="Fundación SOFOFA" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
            <div class="brand-text">
                <span class="brand-title">Fundación SOFOFA</span>
                <span class="brand-subtitle">Capital Humano</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            @php $rol = Auth::user()->rol ?? 'usuario'; @endphp

            {{-- Mis Solicitudes primero (ícono casa) --}}
            <a href="/mis-solicitudes" class="nav-item @if(request()->is('mis-solicitudes')) active @endif" title="Mis Solicitudes">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="nav-label">{{ in_array($rol, ['admin', 'super_admin', 'aprobador', 'gestor']) ? 'Todas las Solicitudes' : 'Mis Solicitudes' }}</span>
            </a>

            {{-- Solicitar Viaje (ícono portapapeles) --}}
            <a href="/solicitudes" class="nav-item @if(request()->is('solicitudes')) active @endif" title="Solicitar Viaje">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="nav-label">Solicitar Viaje</span>
            </a>

            {{-- Estadísticas (Disponible para todos) --}}
            <a href="/reportes" class="nav-item @if(request()->is('reportes')) active @endif" title="Estadísticas">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="nav-label">Estadísticas</span>
            </a>

            {{-- Aprobador, Admin, SuperAdmin --}}
            @if(in_array($rol, ['aprobador', 'admin', 'super_admin']))
            <a href="/aprobador" class="nav-item @if(request()->is('aprobador')) active @endif" title="Aprobaciones">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="nav-label">Aprobaciones</span>
            </a>
            @endif

            {{-- Solo Gestor, Admin, SuperAdmin --}}
            @if(in_array($rol, ['gestor', 'admin', 'super_admin']))
            <a href="/gestion" class="nav-item @if(request()->is('gestion')) active @endif" title="Gestión">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="nav-label">Gestión</span>
            </a>
            @endif

            {{-- Usuarios al final (solo Admin y SuperAdmin) --}}
            @if(in_array($rol, ['admin', 'super_admin']))
            <a href="/usuarios" class="nav-item @if(request()->is('usuarios')) active @endif" title="Usuarios">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="nav-label">Usuarios</span>
            </a>
            @endif
        </nav>

        <button class="toggle-btn" onclick="toggleSidebar()">
            <span class="material-icons toggle-icon">chevron_left</span>
        </button>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-inner">
                <div class="brand">
                    <div class="brand-text">
                        <span class="brand-title">@yield('title', 'Portal de Viajes')</span>
                        <span class="brand-subtitle">@yield('subtitle', 'Sistema de Gestión')</span>
                    </div>
                </div>
                
                <div class="topbar-center" style="position: absolute; left: 50%; transform: translateX(-50%); text-align: center; pointer-events: none;">
                    <div style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.12); padding: 8px 26px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                        <img src="/img/sofofa-icon.png" alt="Icon" style="width: 28px; height: 28px; object-fit: contain; filter: brightness(0) invert(1); opacity: 0.95;">
                        <span style="font-weight: 600; font-size: 18px; letter-spacing: -0.2px; color: white; white-space: nowrap;">
                            Fundación <span style="font-weight: 900; letter-spacing: 0;">SOFOFA</span>
                        </span>
                    </div>
                </div>

                <div class="toolbar-actions">
                    @auth
                    <!-- Info del usuario -->
                    <div style="display: flex; align-items: center; gap: 10px; padding: 6px 14px; background: rgba(255,255,255,0.15); border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: white; flex-shrink: 0;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div style="line-height: 1.3;">
                            <div style="font-size: 13px; font-weight: 600; color: white;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 11px; color: rgba(255,255,255,0.7); text-transform: capitalize;">{{ str_replace('_', ' ', Auth::user()->rol ?? 'usuario') }}</div>
                        </div>
                    </div>
                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Salir
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </header>
        <main class="content">
            @yield('header')
            @yield('content')
        </main>
    </div>
    <div id="toast-container" class="toast-container"></div>
</div>

<script>
    const Toast = {
        show(title, message, type = 'success') {
            const container = document.getElementById('toast-container');
            const id = 'toast-' + Math.random().toString(36).substr(2, 9);
            
            const icons = {
                success: '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                error: '<path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                info: '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                warning: '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
            };

            const html = `
                <div id="${id}" class="toast-item ${type}">
                    <div class="toast-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icons[type] || icons.success}"/>
                        </svg>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${title}</div>
                        <div class="toast-msg">${message}</div>
                    </div>
                    <div class="toast-close" onclick="Toast.hide('${id}')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            
            // Animate in
            setTimeout(() => {
                document.getElementById(id).classList.add('active');
            }, 10);

            // Auto hide
            setTimeout(() => {
                Toast.hide(id);
            }, 5000);
        },

        hide(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('active');
                setTimeout(() => el.remove(), 400);
            }
        }
    };

    // Listen to Laravel sessions
    @if(session('success'))
        Toast.show('¡Éxito!', "{{ session('success') }}", 'success');
    @endif
    @if(session('error'))
        Toast.show('Error', "{{ session('error') }}", 'error');
    @endif
    @if(session('info'))
        Toast.show('Información', "{{ session('info') }}", 'info');
    @endif

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const isCurrentlyCollapsed = sidebar.classList.contains('collapsed') || document.documentElement.classList.contains('is-collapsed');
        
        // Clean up initial state class if exists
        document.documentElement.classList.remove('is-collapsed');
        
        if (isCurrentlyCollapsed) {
            sidebar.classList.remove('collapsed');
            localStorage.setItem('sidebarCollapsed', 'false');
            document.querySelector('.toggle-icon').textContent = 'chevron_left';
        } else {
            sidebar.classList.add('collapsed');
            localStorage.setItem('sidebarCollapsed', 'true');
            document.querySelector('.toggle-icon').textContent = 'chevron_right';
        }
    }

    // Clean up no-transition class
    document.addEventListener('DOMContentLoaded', function() {
        // Apply final class to sidebar if initial state was collapsed
        if (document.documentElement.classList.contains('is-collapsed')) {
            document.getElementById('sidebar').classList.add('collapsed');
            const icon = document.querySelector('.toggle-icon');
            if (icon) icon.textContent = 'chevron_right';
        }
        
        // Re-enable transitions after a tiny delay
        setTimeout(() => {
            document.body.classList.remove('sidebar-no-transition');
        }, 100);
    });
</script>
@stack('scripts')
</body>
</html>

