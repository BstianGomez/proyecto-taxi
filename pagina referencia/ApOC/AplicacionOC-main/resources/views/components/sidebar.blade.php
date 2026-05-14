<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-badge">
            <img src="{{ asset('images/Logos sofofa (2) (1).png') }}" alt="Logo">
        </div>
        <div class="brand-text">
            <div style="font-size: 15px;">Aplicación OC</div>
            <div class="brand-subtitle">Sistema de gestión</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ auth()->user()->hasRole('usuario') ? route('oc.user.home') : (auth()->user()->isCliente() ? route('oc.home') : route('oc.index')) }}" class="nav-item {{ (request()->routeIs('oc.index') || request()->routeIs('oc.user.home') || request()->routeIs('oc.home')) ? 'active' : '' }}" title="Inicio">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="nav-label">Inicio</span>
        </a>

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

        <a href="{{ route('oc.enviadas') }}" class="nav-item {{ request()->routeIs('oc.enviadas') ? 'active' : '' }}" title="Ver Enviadas">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="nav-label">Ver Enviadas</span>
        </a>

        <a href="{{ route('oc.dashboard') }}" class="nav-item {{ request()->routeIs('oc.dashboard') ? 'active' : '' }}" title="Dashboard">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="nav-label">Dashboard</span>
        </a>

        @auth
            @if(auth()->user()->isAdmin() || auth()->user()->hasRole('gestor'))
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}" title="Usuarios">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6h-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2h5m10-10a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                    <span class="nav-label">Usuarios</span>
                </a>
            @endif
        @endauth
    </nav>

    <button class="toggle-btn" onclick="toggleSidebar()" title="Contraer/Expandir menú">
        <svg class="toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
</aside>

<script>
    // Sidebar toggle functionality
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
        
        // Save state to localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }

    // Restore sidebar state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }
    });
</script>
