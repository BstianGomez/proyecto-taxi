@php
    $pendingOCsCount = \Illuminate\Support\Facades\DB::table('oc_solicitudes')->where('estado', 'Solicitada')->count();
    $rol = auth()->user()->role ?? 'usuario';
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-badge">
            <img src="{{ asset('images/Logos sofofa (1) (1).png') }}" alt="Logo">
        </div>
        <div class="brand-text">
            <span class="brand-title">Fundación SOFOFA</span>
            <span class="brand-subtitle">Capital Humano</span>
        </div>
        <button class="mobile-close-btn" onclick="toggleMobileSidebar()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        {{-- Inicio --}}
        <a href="{{ auth()->user()->hasRole('usuario') ? route('oc.user.home') : (auth()->user()->isCliente() ? route('oc.home') : route('oc.index')) }}" 
           class="nav-item {{ (request()->routeIs('oc.index') || request()->routeIs('oc.user.home') || request()->routeIs('oc.home')) ? 'active' : '' }}"
           title="Inicio">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="nav-label">Inicio</span>
        </a>

        @if(auth()->user()->isCliente())
            <a href="{{ route('oc.enviadas') }}" class="nav-item {{ request()->routeIs('oc.enviadas') ? 'active' : '' }}" title="Ver Enviadas">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="nav-label">Ver Enviadas</span>
            </a>
        @endif

        <a href="{{ route('oc.cliente') }}" class="nav-item {{ request()->routeIs('oc.cliente') ? 'active' : '' }}" title="OC Cliente">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="nav-label">OC Cliente</span>
        </a>

        <a href="{{ route('oc.interna') }}" class="nav-item {{ request()->routeIs('oc.interna') ? 'active' : '' }}" title="OC Interna">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="nav-label">OC Interna</span>
        </a>

        <a href="{{ route('oc.negocio') }}" class="nav-item {{ request()->routeIs('oc.negocio') ? 'active' : '' }}" title="OC Negocio">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="nav-label">OC Negocio</span>
        </a>

        @if(!auth()->user()->isCliente())
            <a href="{{ route('oc.enviadas') }}" class="nav-item {{ request()->routeIs('oc.enviadas') ? 'active' : '' }}" title="Ver Enviadas">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="nav-label">Ver Enviadas</span>
            </a>
        @endif

        @if(in_array($rol, ['super_admin', 'admin']))
            <a href="{{ route('oc.gestor') }}" class="nav-item {{ request()->routeIs('oc.gestor') ? 'active' : '' }}" title="Gestión y Facturación">
                <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="nav-label">
                    Gestión y Facturación 
                    @if($pendingOCsCount > 0)
                        <span style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: bold; margin-left: 8px;">{{ $pendingOCsCount }}</span>
                    @endif
                </span>
            </a>
        @endif

        <a href="{{ route('oc.dashboard') }}" class="nav-item {{ request()->routeIs('oc.dashboard') ? 'active' : '' }}" title="Dashboard">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="nav-label">Dashboard</span>
        </a>

        @if(in_array($rol, ['super_admin', 'admin', 'gestor']))
            <a href="{{ route('proveedores.index') }}" class="nav-item {{ request()->routeIs('proveedores.*') ? 'active' : '' }}" title="Proveedores">
                <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <polyline points="17 11 19 13 23 9"></polyline>
                </svg>
                <span class="nav-label">Proveedores</span>
            </a>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}" title="Usuarios">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6h-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2h5m10-10a4 4 0 10-8 0 4 4 0 008 0z"/>
                </svg>
                <span class="nav-label">Usuarios</span>
            </a>
            <a href="{{ route('oc.config') }}" class="nav-item {{ request()->routeIs('oc.config') ? 'active' : '' }}" title="Configuración Proyectos">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="nav-label">Configuración Proyectos</span>
            </a>
        @endif
    </nav>

    <button class="toggle-btn" onclick="toggleSidebar()" title="Expandir/Contraer">
        <span class="material-icons toggle-icon">chevron_left</span>
    </button>
</aside>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
    .mobile-close-btn {
        display: none;
        background: transparent;
        border: none;
        color: white;
        cursor: pointer;
        padding: 8px;
        margin-left: auto;
    }
    @media (max-width: 1024px) {
        .mobile-close-btn {
            display: block;
        }
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.querySelector('.toggle-icon');
        const isCollapsed = sidebar.classList.toggle('collapsed');
        
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        if (icon) {
            icon.textContent = isCollapsed ? 'chevron_right' : 'chevron_left';
        }
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    (function() {
        const sidebar = document.getElementById('sidebar');
        const icon = document.querySelector('.toggle-icon');
        if (!sidebar) return;

        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            if (icon) icon.textContent = 'chevron_right';
        }
    })();
</script>
