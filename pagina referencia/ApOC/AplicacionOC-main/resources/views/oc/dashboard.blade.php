<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <style>
        @include('oc.partials.common_styles')

        :root {
            --brand: #2563eb;
            --brand-2: #1e40af;
        }

        .main-wrapper {
            display: grid;
            gap: 18px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 18px 12px;
            border-bottom: 1px solid #edf2f7;
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            text-transform: none;
        }

        /* Gmail Modal Rules */
        .gmail-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            align-items: center;
            justify-content: center;
        }
        .gmail-modal.show {
            display: flex;
        }
        .gmail-modal-panel {
            background-color: #fff;
            padding: 24px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .gmail-modal-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--ink);
        }
        .gmail-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 16px;
        }

        /* Botones de acción (Aceptar/Rechazar) */
        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            padding: 20px 18px 18px;
            background: #fafbfc;
            align-items: end;
        }

        .filters label {
            font-size: 12px;
            color: #5b6473;
            font-weight: 700;
            display: block;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        .input, .select {
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            font-size: 14px;
            color: var(--ink);
        }

        .input:focus, .select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1);
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .grid-4 {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .stat {
            padding: 20px 18px;
            border-radius: 16px;
            border: 2px solid var(--line);
            background: #fff;
            display: grid;
            gap: 8px;
            transition: all 180ms ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
        }

        .stat:nth-child(2)::before {
            background: linear-gradient(90deg, #f97316, #fb923c);
        }

        .stat:nth-child(3)::before {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .stat:nth-child(4)::before {
            background: linear-gradient(90deg, #8b5cf6, #a78bfa);
        }

        .stat:hover {
            border-color: #c7d2e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .stat.active {
            border-color: var(--brand);
            border-width: 3px;
            box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1), 0 4px 12px rgba(15, 107, 182, 0.2);
            background: #f4f9ff;
        }

        .stat.active::after {
            content: '✓';
            position: absolute;
            top: 12px;
            right: 12px;
            width: 24px;
            height: 24px;
            background: var(--brand);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .stat-label {
            font-size: 11px;
            color: #7c8ea3;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--ink);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #5b6473;
            background: #f8fafc;
            border-bottom: 2px solid #e3e8f0;
            padding: 12px 14px;
            font-weight: 700;
        }

        tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f0f4f8;
        }

        tbody tr {
            transition: background-color 160ms ease;
        }

        tbody tr:hover {
            background-color: #f9fbff;
        }

        tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        tbody tr:nth-child(even):hover {
            background: #f4f7fb;
        }

        .pagination-wrap {
            padding: 16px 24px;
            border-top: 1px solid #f0f4f8;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            border-radius: 0 0 16px 16px;
        }

        .pagination-info {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .page-link {
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            color: #475569;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .page-link:hover:not(.disabled) {
            border-color: #cbd5e1;
            background-color: #f8fafc;
            color: #0f6bb6;
        }

        .page-link.active {
            background-color: #0f6bb6;
            border-color: #0f6bb6;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(15, 107, 182, 0.2);
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f1f5f9;
        }

        .alert {
            margin: 16px 18px 0;
            padding: 14px 16px;
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }

        .alert-list {
            margin: 6px 0 0 18px;
            padding: 0;
            font-weight: 500;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 6px;
            font-weight: 600;
        }
        .dot { width: 8px; height: 8px; border-radius: 999px; }
        .pill.ok { background: #dcfce7 !important; color: #15803d !important; }
        .pill.ok .dot { background: #15803d !important; }
        .pill.pending { background: #fef08a !important; color: #a16207 !important; }
        .pill.pending .dot { background: #a16207 !important; }
        .pill.danger { background: #fee2e2 !important; color: #b91c1c !important; }
        .pill.danger .dot { background: #b91c1c !important; }
        .pill.facturado { background: #e0f2fe !important; color: #0369a1 !important; }
        .pill.facturado .dot { background: #0369a1 !important; }
        .pill.enviada { background: #f3f4f6 !important; color: #4b5563 !important; }
        .pill.enviada .dot { background: #4b5563 !important; }

        .link {
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 999px;
            background: var(--chip);
            color: var(--brand-2);
            font-weight: 600;
        }

        .chart-canvas {
            height: 400px;
            width: 100%;
            padding: 20px;
            background: #f9fbfd;
            position: relative;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
        }

        .chart-canvas canvas {
            max-width: 100%;
            max-height: 100%;
            display: block !important;
        }

        @media (max-width: 900px) {
            .filters {
                grid-template-columns: 1fr;
            }

            .charts-grid {
                grid-template-columns: 1fr !important;
            }

            .filter-actions {
                justify-content: flex-start;
            }
        }

        @media print {
            /* Estilos para impresión */
        }
    </style>
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'dashboard'])

        <!-- Main Content -->
        <div class="main-content">
        
        <!-- Modal para ingresar el correo Gmail -->
        <div id="gmailModal" class="gmail-modal">
            <div class="gmail-modal-panel">
            <form id="gmailSubmitForm" method="POST" action="{{ route('oc.send_gmail_all') }}" style="display:flex; flex-direction:column; gap:12px;">
                @csrf
                <input type="hidden" name="grafico1" id="grafico1Input">
                <input type="hidden" name="grafico2" id="grafico2Input">
                <p class="gmail-modal-title">Correo electrónico destino</p>
                <input type="email" id="gmailInput" name="gmail" class="input" required placeholder="ejemplo@correo.com">
                <div class="gmail-modal-actions">
                    <button type="button" id="closeGmailModal" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
            </div>
        </div>
        
        <x-page-header 
            title="" 
            subtitle=""
            :backRoute="null"
        >
            <x-slot name="additionalButtons">
                <button id="sendAllGmailBtn" class="btn-topbar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span>Enviar todo a Gmail</span>
                </button>
            </x-slot>
        </x-page-header>

        @if(session('success'))
            <div style="margin: 16px 24px; padding: 12px 16px; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div style="margin: 16px 24px; padding: 12px 16px; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <main class="content main-wrapper">
            <section class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title" style="font-size: 20px;">Dashboard de Indicadores</div>
                        <div style="font-size: 13px; color: var(--muted);">Análisis y gráficos de órdenes de compra</div>
                    </div>
                </div>
                <form class="filters" method="GET" action="{{ route('oc.dashboard') }}">
                    <div class="field">
                        <label for="from">Desde</label>
                        <input id="from" name="from" class="input" type="date" value="{{ $filters['from'] ?? '' }}" />
                    </div>
                    <div class="field">
                        <label for="to">Hasta</label>
                        <input id="to" name="to" class="input" type="date" value="{{ $filters['to'] ?? '' }}" />
                    </div>
                    <div class="field">
                        <label for="ceco">CECO</label>
                        <select id="ceco" name="ceco" class="select">
                            <option value="">Todos los CECO</option>
                            @foreach($cecos as $c)
                                <option value="{{ $c }}" {{ ($filters['ceco'] ?? '') == $c ? 'selected' : '' }}>
                                    {{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" class="select">
                            <option value="">Todos los estados</option>
                            <option value="Solicitada" {{ ($filters['estado'] ?? '') == 'Solicitada' ? 'selected' : '' }}>Solicitada</option>
                            <option value="Enviada" {{ ($filters['estado'] ?? '') == 'Enviada' ? 'selected' : '' }}>Enviada</option>
                            <option value="Aceptada" {{ ($filters['estado'] ?? '') == 'Aceptada' ? 'selected' : '' }}>Aceptada</option>
                            <option value="Rechazada" {{ ($filters['estado'] ?? '') == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                            <option value="Facturado" {{ ($filters['estado'] ?? '') == 'Facturado' ? 'selected' : '' }}>Facturado</option>
                        </select>
                    </div>
                    <div class="field" style="justify-content: flex-end; display: flex; gap: 8px;">
                        <label style="opacity:0;">Accion</label>
                        <div class="filter-actions">
                            <a href="{{ route('oc.dashboard') }}" class="btn btn-ghost">Limpiar</a>
                            <button class="btn btn-primary" type="submit">Filtrar</button>
                        </div>
                    </div>
                </form>
            </section>

            @if(($filters['from'] ?? '') || ($filters['to'] ?? '') || ($filters['ceco'] ?? '') || ($filters['estado'] ?? ''))
                <div style="padding: 0 4px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <span style="font-size: 13px; color: var(--muted); font-weight: 600;">Filtros activos:</span>
                    @if($filters['from'] ?? '')
                        <span class="chip">📅 Desde: {{ $filters['from'] }}</span>
                    @endif
                    @if($filters['to'] ?? '')
                        <span class="chip">📅 Hasta: {{ $filters['to'] }}</span>
                    @endif
                    @if($filters['ceco'] ?? '')
                        <span class="chip">🏢 CECO: {{ $filters['ceco'] }}</span>
                    @endif
                    @if($filters['estado'] ?? '')
                        <span class="chip">📊 Estado: {{ $filters['estado'] }}</span>
                    @endif
                </div>
            @endif

            <section class="grid grid-4">
                <div class="stat" data-estado="Solicitada">
                    <div class="stat-label">Solicitada</div>
                    <div class="stat-value">{{ $statusCounts['Solicitada'] ?? 0 }}</div>
                </div>
                <div class="stat" data-estado="Enviada">
                    <div class="stat-label">Enviada</div>
                    <div class="stat-value">{{ $statusCounts['Enviada'] ?? 0 }}</div>
                </div>
                <div class="stat" data-estado="Aceptada">
                    <div class="stat-label">Aceptada</div>
                    <div class="stat-value">{{ $statusCounts['Aceptada'] ?? 0 }}</div>
                </div>
                <div class="stat" data-estado="Rechazada">
                    <div class="stat-label">Rechazada</div>
                    <div class="stat-value">{{ $statusCounts['Rechazada'] ?? 0 }}</div>
                </div>
                <div class="stat" data-estado="Facturado">
                    <div class="stat-label">Facturado</div>
                    <div class="stat-value">{{ $statusCounts['Facturado'] ?? 0 }}</div>
                </div>
            </section>

            <div class="charts-grid">
                <section class="card">
                    <div class="card-header">
                        <div class="card-title">Monto total por CECO</div>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="chartByCeco" width="550" height="360"></canvas>
                    </div>
                </section>

                <section class="card">
                    <div class="card-header">
                        <div class="card-title">Gasto mensual por CECO</div>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="chartByMonth" width="550" height="360"></canvas>
                    </div>
                </section>
            </div>

            <section class="card" id="table-solicitudes" style="scroll-margin-top: 24px;">
                <div class="card-header">
                    <div class="card-title">Solicitudes registradas</div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>CECO</th>
                                <th>Tipo</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Facturación</th>
                                <th>Proveedor</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                @php
                                    $estadoClass = match (strtolower((string) $row->estado)) {
                                        'facturado' => 'facturado',
                                        'aprobada', 'aceptada', 'entregada' => 'ok',
                                        'enviada' => 'enviada',
                                        'rechazada' => 'danger',
                                        default => 'pending',
                                    };
                                    $facturacionClass = strtolower(($row->estado_facturacion ?? 'No Facturado') === 'Facturado' ? 'ok' : 'pending');
                                @endphp
                                <tr style="cursor: pointer; transition: background 0.2s;" onclick="window.location.href='{{ route('oc.gestor') }}';" onmouseover="this.style.background='#f0f4f8'" onmouseout="this.style.background='transparent'">
                                    <td>{{ $row->ceco }}</td>
                                    <td>{{ $row->tipo_solicitud }}</td>
                                    <td>{{ $row->tipo_documento }}</td>
                                    <td><span class="pill {{ $estadoClass }}"><span class="dot"></span>{{ $row->estado }}</span></td>
                                    <td><span class="pill {{ $facturacionClass }}"><span class="dot"></span>{{ $row->estado_facturacion ?? 'No Facturado' }}</span></td>
                                    <td>{{ $row->proveedor }}</td>
                                    <td>{{ $row->descripcion }}</td>
                                    <td>{{ $row->cantidad }}</td>
                                    <td>${{ number_format($row->monto, 0, ',', '.') }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">Aun no hay solicitudes registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap" aria-label="Paginación solicitudes registradas" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; padding: 16px 24px; background: #fff; border-top: 1px solid #f0f4f8; border-radius: 0 0 16px 16px;">
                    <div style="flex: 1; min-width: 200px; display: flex; justify-content: flex-start;">
                        <div class="pagination-info">
                            Mostrando {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} de {{ $rows->total() }} solicitudes
                        </div>
                    </div>
                    
                    @if($rows->lastPage() > 1)
                    <div style="flex: 1; min-width: 250px; display: flex; justify-content: center;">
                        <div class="pagination">
                            <a class="page-link {{ $rows->onFirstPage() ? 'disabled' : '' }}" href="{{ $rows->previousPageUrl() ? $rows->previousPageUrl() . '#table-solicitudes' : '#' }}">Anterior</a>

                            @for($page = 1; $page <= $rows->lastPage(); $page++)
                                <a class="page-link {{ $rows->currentPage() === $page ? 'active' : '' }}" href="{{ $rows->url($page) }}#table-solicitudes">{{ $page }}</a>
                            @endfor

                            <a class="page-link {{ $rows->hasMorePages() ? '' : 'disabled' }}" href="{{ $rows->nextPageUrl() ? $rows->nextPageUrl() . '#table-solicitudes' : '#' }}">Siguiente</a>
                        </div>
                    </div>
                    @else
                    <div style="flex: 1; min-width: 250px;"></div>
                    @endif

                    <div style="flex: 1; min-width: 200px; display: flex; justify-content: flex-end;">
                        <div class="action-buttons">
                            <a href="{{ route('oc.export') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #0f6bb6 0%, #1b7dc8 100%);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Descargar Excel
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        </div><!-- Cierre del wrapper del main content -->
    </div><!-- Cierre de .page -->

    <script>
        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            
            // Activar transiciones solo al hacer toggle manual
            sidebar.classList.add('sidebar-ready');
            
            sidebar.classList.toggle('collapsed');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Filtrado rápido por estadísticas
        document.addEventListener('DOMContentLoaded', function() {
            const estadoSelect = document.getElementById('estado');
            const statCards = document.querySelectorAll('.stat[data-estado]');
            const currentEstado = '{{ $filters['estado'] ?? '' }}';

            // Marcar como activa la tarjeta del filtro actual
            if (currentEstado) {
                statCards.forEach(card => {
                    if (card.dataset.estado === currentEstado) {
                        card.classList.add('active');
                    }
                });
            }

            // Agregar listeners a las tarjetas
            statCards.forEach(card => {
                card.addEventListener('click', function() {
                    const estado = this.dataset.estado;
                    
                    // Si ya está seleccionado, limpiar el filtro
                    if (this.classList.contains('active')) {
                        estadoSelect.value = '';  // Limpiar el select
                        statCards.forEach(c => c.classList.remove('active'));  // Remover active de todas
                    } else {
                        // Actualizar el select con el estado seleccionado
                        estadoSelect.value = estado;
                        
                        // Remover clase active de todas
                        statCards.forEach(c => c.classList.remove('active'));
                        
                        // Agregar clase active a la seleccionada
                        this.classList.add('active');
                    }
                    
                    // Enviar formulario después de 150ms para que se vea la animación
                    setTimeout(() => {
                        document.querySelector('form.filters').submit();
                    }, 150);
                });
            });
        });

        // Restore sidebar state on page load (sin animaciones)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            // Aplicar estado sin transiciones
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
            
            // NO agregar 'sidebar-ready' aquí para evitar animaciones en la carga inicial
        });

        // Chart scripts - Esperar a que las librerías y el DOM estén listos
        function initializeCharts() {
            // Verificar que Chart.js esté disponible
            if (typeof Chart === 'undefined') {
                console.error('❌ Chart.js no está cargado');
                setTimeout(initializeCharts, 100);
                return;
            }
            
            // Registrar el plugin si está disponible
            if (typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }
            
            // Datos para gráfico por CECO
            const dataByCeco = @json($sumByCeco);
            
            const toCecoNumber = (value) => {
                const match = String(value || '').match(/\d+/g);
                return match ? match.join('') : String(value || '');
            };
            const cecoLabels = dataByCeco.map(item => String(item.ceco));
            const cecoValues = dataByCeco.map(item => parseFloat(item.total_monto));
            

        // Datos para gráfico por mes
        const dataByMonth = @json($sumByCecoMonth);
        
        // Preparar datos mensuales
        const months = {};
        const cecosSet = new Set();
        
        dataByMonth.forEach(item => {
            const monthKey = `${item.year}-${String(item.month).padStart(2, '0')}`;
            const monthLabel = new Date(item.year, item.month - 1).toLocaleDateString('es-ES', { year: 'numeric', month: 'short' });
            
            if (!months[monthKey]) {
                months[monthKey] = { label: monthLabel, data: {} };
            }
            
            const cecoKey = String(item.ceco);
            months[monthKey].data[cecoKey] = parseFloat(item.total_monto);
            cecosSet.add(cecoKey);
        });

        const monthLabels = Object.values(months).map(m => m.label);
        const cecosList = Array.from(cecosSet);
        
        
        // Colores para cada CECO
        const colors = [
            'rgba(15, 107, 182, 0.8)',
            'rgba(27, 125, 200, 0.8)',
            'rgba(52, 152, 219, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
        ];

        const datasets = cecosList.map((ceco, index) => ({
            label: ceco,
            data: Object.keys(months).map(monthKey => months[monthKey].data[ceco] || 0),
            backgroundColor: colors[index % colors.length],
            borderColor: colors[index % colors.length].replace('0.8', '1'),
            borderWidth: 1
        }));

        // Gráfico 1: Total por CECO
        const chartByCecoCanvas = document.getElementById('chartByCeco');
        if (!chartByCecoCanvas) {
            console.error('❌ Canvas chartByCeco no encontrado en el DOM');
            return;
        }
        
        const ctx1 = chartByCecoCanvas.getContext('2d');
        
        const maxValue = Math.max(...cecoValues);
        
        if(window.chart1) window.chart1.destroy();
        window.chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: cecoLabels,
                datasets: [{
                    label: 'Monto Total',
                    data: cecoValues,
                    backgroundColor: 'rgba(15, 107, 182, 0.8)',
                    borderColor: 'rgba(15, 107, 182, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                layout: { padding: { right: 60 } },
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                                        datalabels: {
                        anchor: 'end',
                        align: 'right',
                        offset: 4,
                        color: '#0f172a',
                        clamp: false,
                        clip: false,
                        font: {
                            weight: '600',
                            size: 11
                        },
                        padding: {
                            left: 5
                        },
                        formatter: function(value) {
                            if (value === 0) return '';
                            if (value >= 1000000) return '$' + (value / 1000000).toFixed(1).replace('.0', '') + 'M';
                            if (value >= 1000) return '$' + (value / 1000).toFixed(0) + 'k';
                            return '$' + value.toLocaleString('es-ES');
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.x.toLocaleString('es-ES');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-ES');
                            }
                        }
                    },
                    y: {
                        ticks: {
                            autoSkip: false
                        }
                    }
                }
            },
            
        });

        // Gráfico 2: Por mes y CECO
        const chartByMonthCanvas = document.getElementById('chartByMonth');
        if (!chartByMonthCanvas) {
            console.error('❌ Canvas chartByMonth no encontrado en el DOM');
            return;
        }
        
        const ctx2 = chartByMonthCanvas.getContext('2d');
        
        if(window.chart2) window.chart2.destroy();
        window.chart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: datasets
            },
            options: {
                layout: { padding: { top: 30 } },
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#101828',
                        clamp: false,
                        clip: false,
                        display: function(context) {
                            // Solo mostrar si el valor es mayor a 0
                            return context.dataset.data[context.dataIndex] > 0;
                        },
                        font: {
                            weight: '600',
                            size: 11
                        },
                        formatter: function(value) {
                            if (value === 0) return '';
                            // Formato compacto para números grandes (ej: 3.5M en vez de 3.500.000)
                            if (value >= 1000000) return '$' + (value / 1000000).toFixed(1).replace('.0', '') + 'M';
                            if (value >= 1000) return '$' + (value / 1000).toFixed(0) + 'k';
                            return '$' + value.toLocaleString('es-ES');
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.parsed.y.toLocaleString('es-ES');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        stacked: false,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-ES');
                            }
                        }
                    }
                }
            },
            
        });
        }

        // Inicializar gráficos cuando esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initializeCharts, 50);
            });
        } else {
            setTimeout(initializeCharts, 50);
        }

        // Modal de Gmail
        document.addEventListener('DOMContentLoaded', function() {
            const sendBtn = document.getElementById('sendAllGmailBtn');
            const modal = document.getElementById('gmailModal');
            const closeBtn = document.getElementById('closeGmailModal');

            if (sendBtn && modal && closeBtn) {
                sendBtn.addEventListener('click', function() {
                    modal.style.display = 'flex';
                    document.getElementById('gmailInput').focus();
                });
                
                document.getElementById('gmailSubmitForm').addEventListener('submit', function() {
                    if (window.chart1) document.getElementById('grafico1Input').value = window.chart1.toBase64Image();
                    if (window.chart2) document.getElementById('grafico2Input').value = window.chart2.toBase64Image();
                });
                
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });

                // Cerrar modal al hacer clic fuera de él
                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
