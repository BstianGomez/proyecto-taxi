@extends('layouts.app')

@section('title', 'Dashboard de Taxis')

@section('styles')
<style>
    .banner-dashboard {
        background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
        border-radius: 24px;
        padding: 48px;
        color: white;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(15, 107, 182, 0.15);
    }

    .banner-dashboard h1 {
        font-family: "Space Grotesk", sans-serif;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -1px;
    }

    .banner-dashboard p {
        font-size: 16px;
        opacity: 0.9;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
        border-color: var(--brand);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-info .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-info .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1;
    }

    /* Filters bar */
    .filters-bar {
        background: white;
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-end;
        gap: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-group label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-input {
        padding: 10px 16px;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        font-size: 13px;
        min-width: 180px;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .btn-new {
        background: white;
        color: var(--brand);
        border: none;
        padding: 14px 28px;
        border-radius: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    /* Modal Optimized */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: white;
        border-radius: 24px;
        width: 95%;
        max-width: 1100px; /* Wider modal */
        padding: 32px 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-height: 98vh; /* Maximum height to avoid scroll as much as possible */
        overflow: hidden; /* Hide scroll unless absolutely necessary */
    }

    @keyframes modalScale {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .input-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px; /* Reduced margin */
    }

    .input-group label {
        font-size: 10px;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-field {
        padding: 10px 14px; /* Slightly more compact padding */
        border: 1.5px solid var(--line);
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s;
        background: #f8fafc;
        width: 100%;
        font-family: inherit;
        color: var(--ink);
    }

    .input-field:focus {
        outline: none;
        border-color: var(--brand);
        background: white;
        box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
    }

    .phone-input-wrapper {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        padding-left: 14px;
        transition: all 0.2s;
        height: 42px; /* More compact */
    }

    .phone-input-wrapper:focus-within {
        border-color: var(--brand);
        background: white;
        box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
    }

    .phone-input-wrapper .prefix {
        font-weight: 700;
        color: var(--muted);
        margin-right: 4px;
        font-size: 13px;
    }

    .phone-input-wrapper input {
        border: none !important;
        background: transparent !important;
        padding: 0 12px 0 4px !important;
        box-shadow: none !important;
        flex: 1;
        height: 100%;
        font-weight: 700;
        font-size: 13px;
    }

    .btn-view-data {
        background: white;
        border: 1.5px solid var(--line);
        color: var(--muted);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        cursor: pointer;
        width: 100%;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--line);
    }

    .section-title {
        font-size: 11px;
        font-weight: 800;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-item {
        margin-bottom: 12px;
    }

    .detail-item label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .detail-item .value {
        font-size: 14px;
        font-weight: 700;
        color: var(--ink);
    }

    @media (max-width: 1024px) {
        .modal-content { max-width: 900px; }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .details-grid { grid-template-columns: 1fr; }
        .modal-content { max-height: 90vh; overflow-y: auto; }
    }
</style>
@endsection

@section('content')
<!-- Banner -->
<div class="banner-dashboard">
    <div class="banner-content">
        <h1>Solicitudes de Taxi</h1>
        <p>Gestión centralizada de traslados corporativos y facturación.</p>
    </div>
    <button class="btn-new" onclick="openModal()">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Solicitar Nuevo Taxi
    </button>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Solicitados</div>
            <div class="stat-value">{{ $stats['solicitados'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #1d4ed8;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">En Viaje</div>
            <div class="stat-value">{{ $stats['en_viaje'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Completados</div>
            <div class="stat-value">{{ $stats['completados'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #b91c1c;">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Rechazados</div>
            <div class="stat-value">{{ $stats['cancelados'] }}</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card" style="padding:0;">
    <div style="padding: 24px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
        <h3 style="font-size: 15px; font-weight: 700; color: var(--ink);">Registros de Solicitudes</h3>
        <button class="btn btn-outline" style="padding: 6px 14px; font-size: 11px;">Exportar Excel</button>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>CECO</th>
                    <th>Documento</th>
                    <th>Estado</th>
                    <th>Proveedor</th>
                    <th>Descripción</th>
                    <th>Duración</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th style="width: 180px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                <tr>
                    <td style="color: var(--brand); font-weight: 700;">{{ $request->is_associated_ot ? '20133' : '20001' }}</td>
                    <td>
                        <div style="font-weight: 700; color: var(--ink);">Ticket #{{ $request->id }}</div>
                        <div style="font-size: 11px; color: var(--brand); font-weight: 600;">
                            🕒 {{ $request->scheduled_date ? date('d/m/Y', strtotime($request->scheduled_date)) : '' }} {{ $request->scheduled_time ? date('H:i', strtotime($request->scheduled_time)) : 'Inmediato' }}
                        </div>
                    </td>
                    <td>
                        @php
                            $statusClasses = [
                                'solicitado' => 'badge-warning',
                                'en_viaje' => 'badge-brand',
                                'completado' => 'badge-success',
                                'cancelado' => 'badge-danger'
                            ];
                            $statusLabels = [
                                'solicitado' => 'Solicitado',
                                'en_viaje' => 'En Viaje',
                                'completado' => 'Completado',
                                'cancelado' => 'Cancelado'
                            ];
                        @endphp
                        <span class="badge {{ $statusClasses[$request->status] ?? 'badge-outline' }}">
                            {{ $statusLabels[$request->status] ?? $request->status }}
                        </span>
                    </td>
                    <td style="font-weight: 600;">ServiTaxi Chile</td>
                    <td style="color: var(--muted); font-size: 12px; max-width: 200px;">
                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $request->start_address }} → {{ $request->destination_address }}
                        </div>
                    </td>
                    <td style="font-weight: 600; color: var(--ink);">
                        @if($request->started_at && $request->completed_at)
                            {{ $request->started_at->diff($request->completed_at)->format('%i min') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="font-weight: 800; font-size: 14px; color: var(--ink);">
                        @if($request->price > 0)
                            ${{ number_format($request->price, 0, ',', '.') }}
                        @else
                            <span style="color: var(--muted); font-weight: 400; font-size: 12px; font-style: italic;">Pendiente</span>
                        @endif
                    </td>
                    <td style="color: var(--muted);">{{ $request->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button onclick="showRequestDetails({{ json_encode($request) }})" class="btn-view-data">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Ver Datos
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Solicitar Taxi (Full Width Optimization) -->
<div class="modal" id="requestModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 24px; font-weight: 700; color: var(--ink); font-family: 'Space Grotesk';">Solicitar Nuevo Taxi</h2>
            <button onclick="closeModal('requestModal')" style="background:#f1f5f9; border:none; cursor:pointer; color: var(--muted); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('taxi.store') }}" method="POST">
            @csrf
            <!-- OT Row -->
            <div class="input-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 16px; padding: 16px 24px; border: 2px solid var(--line); border-radius: 16px; cursor: pointer; background: #f8fafc; transition: all 0.2s;" id="ot_label">
                    <input type="checkbox" name="is_associated_ot" value="1" onchange="toggleOT(this)" style="width: 20px; height: 20px; accent-color: var(--brand); cursor: pointer;">
                    <div>
                        <p style="font-weight: 800; color: var(--ink); font-size: 14px;">¿Asociado a Orden de Trabajo (OT)?</p>
                        <p style="font-size: 12px; color: var(--muted);">Marca si el viaje pertenece a un proyecto específico.</p>
                    </div>
                </label>
            </div>

            <div id="ot_group" style="display: none; margin-bottom: 20px;">
                <div class="input-group">
                    <label>Número de Proyecto / OT / OC / OP</label>
                    <div style="display: flex; gap: 12px;">
                        <select name="project_prefix" class="input-field" style="width: 120px; font-weight: 700;">
                            <option value="OT">OT</option>
                            <option value="OC">OC</option>
                            <option value="OP">OP</option>
                        </select>
                        <input type="text" name="project_number" class="input-field" placeholder="Ingrese número" style="flex: 1;" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                </div>
            </div>

            <!-- Main Grid: 3 Columns for compact view -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <div class="input-group">
                    <label>RUT Pasajero</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="text" name="rut_body" id="rut_body" class="input-field" placeholder="12345678" required maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 8) document.getElementById('rut_dv').focus();" style="flex: 1;">
                        <span style="font-weight: 800; color: var(--muted);">-</span>
                        <input type="text" name="rut_dv" id="rut_dv" class="input-field" placeholder="K" required maxlength="1" oninput="this.value = this.value.toUpperCase().replace(/[^0-9K]/g, '')" style="width: 50px; text-align: center; font-weight: 800;">
                    </div>
                </div>

                <div class="input-group">
                    <label>Teléfono de Contacto</label>
                    <div class="phone-input-wrapper">
                        <span class="prefix">+56 9</span>
                        <input type="tel" name="phone" placeholder="1234 5678" required maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                </div>

                <div class="input-group">
                    <label>Email de Confirmación</label>
                    <input type="email" name="email" class="input-field" value="{{ auth()->user()->email }}" readonly style="background: #eff6ff; border-color: #bfdbfe; color: #1e40af; font-weight: 700;">
                </div>

                <div class="input-group">
                    <label>Punto de Recogida</label>
                    <input type="text" name="start_address" class="input-field" placeholder="Dirección de origen" required>
                </div>

                <div class="input-group">
                    <label>Destino Final</label>
                    <input type="text" name="destination_address" class="input-field" placeholder="Dirección de destino" required>
                </div>

                <div class="input-group">
                    <label>Fecha y Hora</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="date" name="scheduled_date" class="input-field" required value="{{ date('Y-m-d') }}" style="flex: 1.5;">
                        <input type="time" name="scheduled_time" class="input-field" required style="flex: 1;">
                    </div>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 16px; justify-content: center;">
                <button type="submit" class="btn btn-primary" style="width: 300px; padding: 16px; border-radius: 16px; font-size: 16px; font-weight: 800;">Confirmar Solicitud Premium</button>
                <button type="button" onclick="closeModal('requestModal')" class="btn btn-outline" style="width: 200px; padding: 16px; border-radius: 16px; font-size: 16px; font-weight: 800;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ver Datos -->
<div class="modal" id="detailsModal">
    <div class="modal-content" style="max-width: 600px; padding: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="font-size: 22px; font-weight: 800; color: var(--ink); font-family: 'Space Grotesk';">Resumen del Viaje</h2>
                <p id="details-ticket-id" style="font-size: 13px; color: var(--muted); font-weight: 600;"></p>
            </div>
            <button onclick="closeDetailsModal()" style="background:#f8fafc; border:1px solid var(--line); cursor:pointer; color: var(--muted); width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="details-grid">
            <div class="detail-section" style="grid-column: span 2;">
                <div class="section-title">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2.5"></path></svg>
                    Información del Pasajero
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="detail-item">
                        <label>Nombre / RUT</label>
                        <div class="value" id="det-rut"></div>
                    </div>
                    <div class="detail-item">
                        <label>Teléfono</label>
                        <div class="value" id="det-phone"></div>
                    </div>
                    <div class="detail-item" style="grid-column: span 2;">
                        <label>Email</label>
                        <div class="value" id="det-email"></div>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <div class="section-title">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2.5"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2.5"></path></svg>
                    Ruta del Viaje
                </div>
                <div style="margin-top: 10px;">
                    <div class="detail-item">
                        <label>Origen</label>
                        <div class="value" style="font-size: 13px;" id="det-start"></div>
                    </div>
                    <div class="detail-item">
                        <label>Destino</label>
                        <div class="value" style="font-size: 13px;" id="det-dest"></div>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <div class="section-title">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2.5"></path></svg>
                    Programación
                </div>
                <div class="detail-item">
                    <label>Fecha</label>
                    <div class="value" id="det-date"></div>
                </div>
                <div class="detail-item">
                    <label>Hora Estimada</label>
                    <div class="value" id="det-time"></div>
                </div>
                <div class="detail-item" id="det-project-container">
                    <label>Proyecto / OT</label>
                    <div class="value" id="det-project"></div>
                </div>
            </div>
        </div>

        <div style="margin-top: 24px;">
            <button onclick="closeDetailsModal()" class="btn btn-outline" style="width: 100%; padding: 14px; border-radius: 16px; font-weight: 800;">Entendido</button>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('requestModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function showRequestDetails(request) {
        document.getElementById('details-ticket-id').textContent = `Ticket #${request.id}`;
        document.getElementById('det-rut').textContent = request.rut;
        document.getElementById('det-phone').textContent = request.phone;
        document.getElementById('det-email').textContent = request.email;
        document.getElementById('det-start').textContent = request.start_address;
        document.getElementById('det-dest').textContent = request.destination_address;
        document.getElementById('det-date').textContent = request.scheduled_date || 'Inmediato';
        document.getElementById('det-time').textContent = request.scheduled_time || '--:--';
        
        if (request.project_number) {
            document.getElementById('det-project-container').style.display = 'block';
            document.getElementById('det-project').textContent = request.project_number;
        } else {
            document.getElementById('det-project-container').style.display = 'none';
        }
        
        document.getElementById('detailsModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function toggleOT(checkbox) {
        const group = document.getElementById('ot_group');
        const label = document.getElementById('ot_label');
        group.style.display = checkbox.checked ? 'block' : 'none';
        label.style.borderColor = checkbox.checked ? 'var(--brand)' : 'var(--line)';
        label.style.background = checkbox.checked ? 'var(--brand-light)' : '#f8fafc';
    }

    function formatPrice(input) {
        let value = input.value.replace(/\D/g, "");
        if (value) {
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        } else {
            input.value = "";
        }
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    }
</script>
@endsection
