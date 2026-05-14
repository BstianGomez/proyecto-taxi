<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configuración de Proyectos - Aplicación OC</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />
    <style>
        @include('oc.partials.common_styles')
        
        .content-body {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 28px;
        }
        
        .settings-card {
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .settings-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(15, 107, 182, 0.1);
        }
        
        .card-header {
            padding: 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.02em;
        }
        
        .card-body {
            padding: 24px;
            flex: 1;
        }
        
        .items-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .items-list::-webkit-scrollbar {
            width: 6px;
        }
        .items-list::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }
        
        .item-row:hover {
            background: white;
            border-color: var(--brand-light);
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.08);
            transform: scale(1.01);
        }
        
        .item-name {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .item-name::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--brand);
            border-radius: 50%;
            opacity: 0.5;
        }
        
        .add-form {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            background: #f1f5f9;
            padding: 16px;
            border-radius: 14px;
        }
        
        .btn-delete {
            width: 32px;
            height: 32px;
            color: #94a3b8;
            background: white;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .btn-delete:hover {
            background: #fee2e2;
            color: #ef4444;
            border-color: #fecaca;
            transform: rotate(8deg);
        }

        .empty-state {
            text-align: center;
            padding: 30px 0;
            color: #94a3b8;
            font-size: 14px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'config'])

        <div class="main-content">
            <x-page-header title="" subtitle="" :showLogout="true" />

            <div class="content-body">
                <div class="settings-grid">
                    <!-- Coordinadores -->
                    <div class="settings-card">
                        <div class="card-header">
                            <div class="card-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6h-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2h5m10-10a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                            </div>
                            <h3 class="card-title">Coordinadores de Proyecto</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('oc.config.coordinador.store') }}" method="POST" class="add-form">
                                @csrf
                                <input type="text" name="nombre" class="input" placeholder="Nombre completo..." required style="flex:1;">
                                <button type="submit" class="btn btn-primary" style="height:46px;">Añadir</button>
                            </form>
                            
                            <div class="items-list">
                                @forelse($coordinadores as $item)
                                    <div class="item-row">
                                        <span class="item-name">{{ $item->nombre }}</span>
                                        <form action="{{ route('oc.config.coordinador.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este coordinador?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Eliminar">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="empty-state">No hay coordinadores registrados</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Tipos de Servicio -->
                    <div class="settings-card">
                        <div class="card-header">
                            <div class="card-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="card-title">Tipos de Servicio</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('oc.config.tipo-servicio.store') }}" method="POST" class="add-form">
                                @csrf
                                <input type="text" name="nombre" class="input" placeholder="Nombre del servicio..." required style="flex:1;">
                                <button type="submit" class="btn btn-primary" style="height:46px;">Añadir</button>
                            </form>
                            
                            <div class="items-list">
                                @forelse($tipoServicios as $item)
                                    <div class="item-row">
                                        <span class="item-name">{{ $item->nombre }}</span>
                                        <form action="{{ route('oc.config.tipo-servicio.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Eliminar">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="empty-state">No hay tipos de servicio registrados</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Tipos de Proyecto -->
                    <div class="settings-card">
                        <div class="card-header">
                            <div class="card-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <h3 class="card-title">Tipos de Proyecto</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('oc.config.tipo-proyecto.store') }}" method="POST" class="add-form">
                                @csrf
                                <input type="text" name="nombre" class="input" placeholder="Nombre del proyecto..." required style="flex:1;">
                                <button type="submit" class="btn btn-primary" style="height:46px;">Añadir</button>
                            </form>
                            
                            <div class="items-list">
                                @forelse($tipoProyectos as $item)
                                    <div class="item-row">
                                        <span class="item-name">{{ $item->nombre }}</span>
                                        <form action="{{ route('oc.config.tipo-proyecto.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este tipo de proyecto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" title="Eliminar">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="empty-state">No hay tipos de proyecto registrados</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('oc.partials.common_scripts')
    @if(session('success'))
    <script>
        (function() {
            const alertId = "{{ session('alert_id') }}";
            if (alertId && sessionStorage.getItem('shown_alert_' + alertId)) return;
            
            showAlert('success', "{{ session('success') }}");
            
            if (alertId) sessionStorage.setItem('shown_alert_' + alertId, 'true');
        })();
    </script>
    @endif
</body>
</html>
