<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OC Enviadas</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('oc.partials.common_scripts')

    <style>
        @include('oc.partials.common_styles')

        .content {
            padding: 32px;
            max-width: 1500px;
            margin: 0 auto;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 32px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .toolbar-title h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .toolbar-title p {
            font-size: 14px;
            color: #64748b;
            margin: 4px 0 0;
        }

        .filter-group {
            display: flex;
            gap: 16px;
            background: white;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid #e2e8f0;
            align-items: center;
        }

        .search-wrapper {
            position: relative;
            min-width: 320px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .btn-export {
            background: white;
            color: var(--brand);
            border: 1px solid var(--brand);
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(15, 107, 182, 0.1);
        }

        .btn-export:hover {
            background: var(--brand);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(15, 107, 182, 0.25);
        }

        .oc-number {
            font-weight: 800;
            color: var(--brand);
            font-size: 14px;
        }

        .provider-info {
            display: flex;
            flex-direction: column;
        }

        .provider-name {
            font-weight: 700;
            color: #1e293b;
        }

        .provider-email {
            font-size: 12px;
            color: #94a3b8;
        }

        .btn-actions-trigger {
            width: 38px;
            height: 38px;
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-left: auto;
        }

        .btn-actions-trigger:hover {
            background: var(--brand);
            color: white;
            border-color: var(--brand);
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.2);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            min-width: 200px;
            z-index: 100;
            padding: 8px;
            margin-top: 8px;
        }

        tbody td.col-proveedor {
            width: auto;
            max-width: 150px;
            white-space: normal;
        }

        tbody td.col-desc {
            width: auto;
            max-width: 200px;
            white-space: normal;
        }
            animation: dropdownFade 0.2s ease;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #475569;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #f8fafc;
            color: var(--brand);
            padding-left: 20px;
        }

        .footer {
            padding: 16px 32px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
        }

        .empty-state {
            padding: 80px 40px;
            text-align: center;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'enviadas'])

        <div class="main-content">
            <x-page-header title="" subtitle="" :showLogout="true" />

            <div class="content">
                @if(session('success'))
                    <script>showAlert('success', "{{ session('success') }}");</script>
                @endif

                <div class="toolbar">
                    <div class="toolbar-title">
                        <h1>OC Enviadas</h1>
                        <p>Órdenes de compra enviadas a proveedores</p>
                    </div>

                    <div class="filter-group">
                        <div class="search-wrapper">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input id="search" class="input search-input" type="search" placeholder="Buscar por N° OC, proveedor o descripción" />
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label style="font-size: 13px; font-weight: 600; color: #64748b;">Tipo:</label>
                            <select id="filterTipo" class="select" style="width: 140px; padding: 8px 12px !important;">
                                <option value="">Todos</option>
                                <option value="Cliente">Cliente</option>
                                <option value="Interna">Interna</option>
                                <option value="Negocio">Negocio</option>
                            </select>
                        </div>

                        <a href="{{ route('oc.enviadas.export') }}" class="btn-export">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Exportar
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrap">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th class="col-doc"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> N° OC</th>
                                    <th class="compact"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Fecha</th>
                                    <th class="compact"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg> CECO</th>
                                    <th class="col-tipo"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg> Tipo</th>
                                    <th class="col-status"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Estado</th>
                                    <th class="col-proveedor"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg> Proveedor / Email</th>
                                    <th class="col-desc"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg> Descripción</th>
                                    <th class="compact"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><line x1="11" y1="5" x2="21" y2="5"></line><line x1="11" y1="9" x2="18" y2="9"></line><line x1="11" y1="13" x2="15" y2="13"></line><line x1="3" y1="17" x2="3" y2="7"></line><polyline points="7 13 3 17 0 13"></polyline></svg> Cant.</th>
                                    <th class="compact"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Monto</th>
                                    <th class="col-actions" style="text-align: right;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px; color:#94a3b8"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="ocRows">
                                @forelse ($rows as $row)
                                    <tr data-text="{{ strtolower($row->numero_oc.' '.$row->ceco.' '.$row->tipo_solicitud.' '.$row->proveedor.' '.$row->descripcion) }}"
                                        data-tipo="{{ $row->tipo_solicitud }}"
                                        data-id="{{ $row->id }}"
                                        data-numero="{{ $row->numero_oc }}"
                                        data-email="{{ $row->email_proveedor }}"
                                        data-descripcion="{{ $row->descripcion }}"
                                        data-cantidad="{{ $row->cantidad }}"
                                        data-monto="{{ $row->monto }}"
                                        data-comentario="{{ $row->comentario }}"
                                        data-file-path="{{ $row->file_path }}">
                                        <td data-label="N° OC"><span class="oc-number">{{ $row->numero_oc }}</span></td>
                                        <td data-label="Fecha" style="white-space: nowrap;">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                                        <td data-label="CECO">{{ $row->ceco }}</td>
                                        <td>
                                            <span class="badge badge-oc-{{ strtolower($row->tipo_solicitud) }}">
                                                {{ $row->tipo_solicitud }}
                                            </span>
                                        </td>
                                        <td data-label="Estado">@php $sStatus = strtolower((string)($row->solicitud_estado ?? ''));
                                                $sBilling = strtolower((string)($row->solicitud_estado_facturacion ?? ''));
                                                $isBilled = ($sStatus === 'facturado' || $sBilling === 'facturado');
                                            @endphp
                                            <span class="badge {{ $isBilled ? 'badge-status-billed' : 'badge-status-sent' }}">
                                                @if($isBilled)
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><polyline points="7 11 12 16 22 6"></polyline><polyline points="12 21 17 16"></polyline></svg>
                                                @else
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                @endif
                                                {{ $isBilled ? 'Facturado' : 'Enviada' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="provider-info">
                                                <span class="provider-name">{{ $row->proveedor }}</span>
                                                <span class="provider-email">{{ $row->email_proveedor }}</span>
                                            </div>
                                        </td>
                                        <td data-label="Descripción" title="{{ $row->descripcion }}">
                                            {{ $row->descripcion }}
                                        </td>
                                        <td data-label="Cant.">{{ $row->cantidad }}</td>
                                        <td data-label="Monto" style="font-weight: 700;">${{ number_format($row->monto, 0, ',', '.') }}</td>
                                        <td style="text-align: right;">
                                            <div style="position: relative; display: inline-block;">
                                                <button type="button" class="btn-actions-trigger" onclick="toggleDropdown(this, event)" title="Ver acciones">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button type="button" class="dropdown-item" onclick="verDatosOC(this)">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #3b82f6;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Ver Detalles
                                                    </button>
                                                    <a href="{{ route('oc.enviadas.pdf', $row->id) }}" class="dropdown-item">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ef4444;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Descargar PDF
                                                    </a>
                                                    
                                                    @if(!$isBilled && strtolower($row->solicitud_estado) !== 'enviada')
                                                        <div style="height: 1px; background: #f1f5f9; margin: 8px 12px;"></div>
                                                        <button type="button" class="dropdown-item" onclick="abrirModalEditarFromDropdown(this)">
                                                            <i class="fas fa-pen-nib" style="color: #f59e0b;"></i> Editar / Subir OC
                                                        </button>
                                                        <button type="button" class="dropdown-item {{ !$row->file_path ? 'disabled' : '' }}" 
                                                                @if($row->file_path) onclick="enviarOcManual({{ $row->id }}, this)" @endif
                                                                style="{{ !$row->file_path ? 'opacity: 0.5; cursor: not-allowed;' : '' }}">
                                                            <i class="fas fa-paper-plane" style="color: #10b981;"></i> Re-enviar Email
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">
                                            <div class="empty-state">
                                                <i class="fas fa-folder-open"></i>
                                                <p>No hay órdenes de compra enviadas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="emptyState" class="empty-state" style="display: none;">
                        <i class="fas fa-search"></i>
                        <p>No hay OC enviadas que coincidan con los filtros.</p>
                    </div>

                    <div class="footer">
                        <div id="rowsCount">Total: {{ count($rows) }} OC</div>
                        <div>Última actualización: {{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </main>
</section>

            @if(count($rows) === 0)
                <div class="alert alert-info" style="margin-top: 20px;">
                    <span style="font-size: 18px;">ℹ</span>
                    <span>No hay órdenes de compra enviadas. Las OC aparecerán aquí una vez que se envíen desde el menú de acciones.</span>
                </div>
            @endif
        </main>
        </div>
    </div>

    <!-- Modal Editar OC (Premium Design) -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">Editar Orden de Compra</h3>
                    <p style="margin: 4px 0 0; font-size: 13px; color: #64748b;">Actualice los detalles de la OC enviada</p>
                </div>
                <button class="modal-close" onclick="cerrarModalEditar()" title="Cerrar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="editOcForm" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                        <div class="form-group">
                            <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 8px;">N° Orden de Compra <span style="color: #ef4444;">*</span></label>
                            <div style="position: relative;">
                                <input type="text" name="numero_oc" id="edit_numero_oc" required class="input" style="width: 100%; padding-left: 12px; height: 44px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-weight: 500;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 8px;">Email Proveedor <span style="color: #ef4444;">*</span></label>
                            <input type="email" name="email_proveedor" id="edit_email_proveedor" required class="input" style="width: 100%; padding-left: 12px; height: 44px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-weight: 500;">
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 8px;">Descripción del Servicio/Compra</label>
                        <textarea name="descripcion" id="edit_descripcion" class="input" style="width: 100%; min-height: 90px; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: inherit; font-size: 14px; line-height: 1.5;"></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                        <div class="form-group">
                            <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 8px;">Cantidad</label>
                            <input type="number" name="cantidad" id="edit_cantidad" class="input" style="width: 100%; padding-left: 12px; height: 44px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-weight: 500;">
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 8px;">Monto Total ($)</label>
                            <input type="text" name="monto" id="edit_monto" class="input" style="width: 100%; padding-left: 12px; height: 44px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-weight: 600; color: #0f172a;">
                        </div>
                    </div>

                    <div style="margin-bottom: 24px; padding: 20px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 16px;">
                        <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 12px;">Reemplazar Archivo OC (PDF)</label>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <input type="file" name="oc_file" accept=".pdf" class="input" style="width: 100%; height: auto; padding: 8px; font-size: 13px; border: none; background: transparent;">
                            <small style="color: #94a3b8; font-size: 12px; font-style: italic;">Sube un nuevo archivo solo si deseas reemplazar el actual. Máximo 10MB.</small>
                        </div>
                    </div>

                    <div style="margin-bottom: 8px;">
                        <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 8px;">Comentarios Adicionales</label>
                        <textarea name="comentario" id="edit_comentario" class="input" placeholder="Añadir notas internas..." style="width: 100%; min-height: 80px; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-family: inherit; font-size: 14px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="cerrarModalEditar()" style="border-radius: 12px; margin: 0;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" style="margin: 0;">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ver Detalles (Read-only) -->
    <div class="modal-overlay" id="viewModal">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">Detalles de la OC</h3>
                    <p style="margin: 4px 0 0; font-size: 13px; color: #64748b;">Información completa de la orden de compra enviada</p>
                </div>
                <button class="modal-close" onclick="cerrarModalView()" title="Cerrar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px;">N° Orden de Compra</label>
                        <div id="view_numero_oc" style="font-size: 16px; font-weight: 600; color: #0f172a;"></div>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px;">Email Proveedor</label>
                        <div id="view_email_proveedor" style="font-size: 15px; font-weight: 500; color: #0f172a;"></div>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px;">Descripción</label>
                    <div id="view_descripcion" style="font-size: 14px; line-height: 1.6; color: #334155; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #f1f5f9;"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px;">Cantidad</label>
                        <div id="view_cantidad" style="font-size: 15px; font-weight: 500; color: #0f172a;"></div>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px;">Monto Total</label>
                        <div id="view_monto" style="font-size: 18px; font-weight: 700; color: #0f6bb6;"></div>
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px;">Comentarios Adicionales</label>
                    <div id="view_comentario" style="font-size: 14px; line-height: 1.6; color: #475569; font-style: italic;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrarModalView()" class="btn btn-primary" style="margin: 0; width: auto; font-weight: 700;">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const filterTipo = document.getElementById('filterTipo');
        const rows = Array.from(document.querySelectorAll('#ocRows tr'));
        const emptyState = document.getElementById('emptyState');
        const rowsCount = document.getElementById('rowsCount');

        const applyFilters = () => {
            const search = searchInput.value.trim().toLowerCase();
            const tipo = filterTipo.value;
            let visible = 0;

            rows.forEach((row) => {
                const matchesSearch = !search || row.dataset.text.includes(search);
                const matchesTipo = !tipo || row.dataset.tipo === tipo;
                const show = matchesSearch && matchesTipo;

                row.style.display = show ? '' : 'none';
                if (show) visible += 1;
            });

            emptyState.style.display = visible === 0 ? 'block' : 'none';
            rowsCount.textContent = `Total: ${visible} OC`;
        };

        [searchInput, filterTipo].forEach((el) => {
            el.addEventListener('input', applyFilters);
            el.addEventListener('change', applyFilters);
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        function toggleDropdown(btn, event) {
            event.stopPropagation();
            const allMenus = document.querySelectorAll('.dropdown-menu');
            const currentMenu = btn.nextElementSibling;
            
            allMenus.forEach(menu => {
                if (menu !== currentMenu) menu.style.display = 'none';
            });

            const isVisible = currentMenu.style.display === 'block';
            
            if (!isVisible) {
                const rect = btn.getBoundingClientRect();
                currentMenu.style.display = 'block';
                currentMenu.style.position = 'fixed';
                currentMenu.style.left = (rect.right - 200) + 'px';
                
                const spaceBelow = window.innerHeight - rect.bottom;
                if (spaceBelow < 200) {
                    currentMenu.style.bottom = (window.innerHeight - rect.top + 5) + 'px';
                    currentMenu.style.top = 'auto';
                    currentMenu.style.marginBottom = '8px';
                } else {
                    currentMenu.style.top = rect.bottom + 'px';
                    currentMenu.style.bottom = 'auto';
                    currentMenu.style.marginTop = '8px';
                }
            } else {
                currentMenu.style.display = 'none';
            }
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });

        function verDatosOC(btn) {
            const tr = btn.closest('tr');
            document.getElementById('view_numero_oc').textContent = tr.dataset.numero;
            document.getElementById('view_email_proveedor').textContent = tr.dataset.email;
            document.getElementById('view_descripcion').textContent = tr.dataset.descripcion || 'Sin descripción';
            document.getElementById('view_cantidad').textContent = tr.dataset.cantidad || '0';
            
            // Format monto
            const rawMonto = tr.dataset.monto;
            const formattedMonto = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(rawMonto);
            document.getElementById('view_monto').textContent = formattedMonto;
            
            document.getElementById('view_comentario').textContent = tr.dataset.comentario || 'Sin comentarios adicionales';

            const modal = document.getElementById('viewModal');
            const container = modal.querySelector('.modal-container');
            modal.style.display = 'flex';
            setTimeout(() => { container.style.transform = 'scale(1)'; }, 10);
        }

        function cerrarModalView() {
            const modal = document.getElementById('viewModal');
            const container = modal.querySelector('.modal-container');
            container.style.transform = 'scale(0.95)';
            setTimeout(() => { modal.style.display = 'none'; }, 200);
        }

        function abrirModalEditarFromDropdown(btn) {
            abrirModalEditar(btn);
        }

        function abrirModalEditar(btn) {
            const tr = btn.closest('tr');
            const id = tr.dataset.id;
            const numero = tr.dataset.numero;
            const email = tr.dataset.email;
            const descripcion = tr.dataset.descripcion;
            const cantidad = tr.dataset.cantidad;
            const monto = tr.dataset.monto;
            const comentario = tr.dataset.comentario;

            document.getElementById('edit_numero_oc').value = numero;
            document.getElementById('edit_email_proveedor').value = email;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_cantidad').value = cantidad;
            document.getElementById('edit_monto').value = monto;
            document.getElementById('edit_comentario').value = comentario;

            const form = document.getElementById('editOcForm');
            form.action = `/oc/enviadas/${id}`;

            const modal = document.getElementById('editModal');
            const container = modal.querySelector('.modal-container');
            
            modal.style.display = 'flex';
            setTimeout(() => {
                container.style.transform = 'scale(1)';
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalEditar() {
            const modal = document.getElementById('editModal');
            const container = modal.querySelector('.modal-container');
            
            container.style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }, 200);
        }

        async function enviarOcManual(id, btn) {
            if (btn.classList.contains('disabled')) return;
            
            const result = await Swal.fire({
                title: '¿Enviar Orden de Compra?',
                text: "Se enviará la OC al correo del proveedor.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f6bb6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, enviar ahora',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Enviando OC...',
                text: 'Por favor espere mientras notificamos al proveedor.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch(`/oc/enviadas/${id}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errData = await response.json().catch(() => ({ message: 'Error interno en el servidor.' }));
                    throw new Error(errData.message || `Error del servidor (${response.status})`);
                }

                const data = await response.json();
                if (data.success) {
                    showAlert('success', '¡Enviado! La OC ha sido procesada correctamente.');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showAlert('error', data.message || 'El servidor rechazó la solicitud.');
                }
            } catch (error) {
                showAlert('error', error.message || 'No se pudo conectar con el servidor.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Validación de archivos PDF preventiva
            const pdfInputs = document.querySelectorAll('input[type="file"][accept*="pdf"]');
            pdfInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file && file.type !== 'application/pdf') {
                        alert('Por favor, seleccione un archivo PDF válido.');
                        this.value = '';
                    }
                });
            });

            const sidebar = document.getElementById('sidebar');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }
        });
    </script>
</body>
</html>
