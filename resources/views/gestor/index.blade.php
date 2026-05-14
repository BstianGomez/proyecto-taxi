@extends('layouts.app')

@section('title', 'Centro de Despacho - Taxi Premium')

@section('styles')
<style>
    .dispatch-header {
        background: #0f172a;
        color: white;
        padding: 40px;
        border-radius: 24px;
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .gestor-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 24px;
    }

    .request-item {
        background: white;
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
        position: relative;
    }

    .request-item:hover {
        border-color: var(--brand);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .request-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .request-badge {
        width: 50px;
        height: 50px;
        background: #f1f5f9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #475569;
    }

    .request-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .request-route {
        font-weight: 700;
        font-size: 15px;
        color: var(--ink);
    }

    .request-meta {
        font-size: 12px;
        color: var(--muted);
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .action-group {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .side-card {
        background: white;
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 24px;
        position: sticky;
        top: 24px;
    }

    .pulse-solicitado {
        width: 10px;
        height: 10px;
        background: #f59e0b;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }

    .price-input {
        padding: 10px 14px;
        border-radius: 12px;
        border: 2px solid var(--line);
        font-size: 14px;
        width: 140px;
        font-weight: 700;
        color: var(--ink);
        transition: all 0.2s;
        text-align: right;
    }

    .price-input:focus {
        outline: none;
        border-color: var(--brand);
        box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
    }

    /* Modal Styles */
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
        width: 100%;
        max-width: 600px;
        padding: 32px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalScale {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
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

    .route-visual {
        position: relative;
        padding-left: 24px;
        margin-top: 10px;
    }

    .route-visual::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        border-left: 2px dashed var(--line);
    }

    .route-point {
        position: relative;
        margin-bottom: 20px;
    }

    .route-point::after {
        content: '';
        position: absolute;
        left: -21px;
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: white;
        border: 2.5px solid var(--brand);
        z-index: 2;
    }

    .route-point.end::after { border-color: #10b981; }

    .price-tag {
        background: #0f172a;
        color: white;
        padding: 20px;
        border-radius: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    /* Custom Confirm Modal */
    .confirm-modal-content {
        max-width: 400px;
        text-align: center;
        padding: 40px;
    }

    .confirm-icon {
        width: 64px;
        height: 64px;
        background: #fff1f2;
        color: #e11d48;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
</style>
@endsection

@section('content')
<div class="dispatch-header">
    <div>
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <span class="badge" style="background: #ef4444; color: white;">Live</span>
            <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: 32px; font-weight: 700;">Centro de Despacho</h1>
        </div>
        <p style="opacity: 0.7;">Gestión de solicitudes en tiempo real.</p>
    </div>
    <div style="text-align: right;">
        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.5;">Servidor Activo</div>
        <div style="font-size: 20px; font-weight: 700;" id="clock">--:--:--</div>
    </div>
</div>

<div class="gestor-grid">
    <div class="main-column">
        <!-- Section: Requested (Solicitados) -->
        <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center;">
            <span class="pulse-solicitado"></span> Viajes Solicitados
        </h2>

        @forelse($requests->where('status', 'solicitado') as $request)
        <div class="request-item" style="border-left: 4px solid #f59e0b; cursor: pointer;" onclick="showRequestDetails({{ json_encode($request) }})">
            <div class="request-info">
                <div class="request-badge">T{{ $request->id }}</div>
                <div class="request-details">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="request-route">{{ $request->start_address }} → {{ $request->destination_address }}</div>
                    </div>
                    <div class="request-meta">
                        <span>👤 {{ $request->rut }}</span>
                        <span>🏷️ {{ $request->project_number ?? 'Particular' }}</span>
                        <span style="color: var(--brand); font-weight: 600;">🕒 {{ $request->scheduled_date ? date('d/m', strtotime($request->scheduled_date)) : '' }} {{ $request->scheduled_time ? date('H:i', strtotime($request->scheduled_time)) : 'Inmediato' }}</span>
                    </div>
                </div>
            </div>
            <div class="action-group" onclick="event.stopPropagation()">
                <div style="display: flex; gap: 8px;">
                    <form action="{{ route('taxi.updateStatus', $request) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="en_viaje">
                        <button type="submit" class="btn btn-primary" style="background: #3b82f6; border:none; padding: 10px 20px; border-radius: 12px; font-weight: 700;">Iniciar</button>
                    </form>
                    
                    <button type="button" onclick="confirmRejection({{ $request->id }})" class="btn" style="background: #fff1f2; border: 1.5px solid #fecaca; color: #e11d48; padding: 10px 20px; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Rechazar
                    </button>
                    <form id="reject-form-{{ $request->id }}" action="{{ route('taxi.updateStatus', $request) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelado">
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="padding: 40px; text-align: center; background: white; border-radius: 20px; border: 1.5px dashed var(--line); color: var(--muted); margin-bottom: 32px;">
            No hay solicitudes pendientes.
        </div>
        @endforelse

        <!-- Section: In Progress (En Viaje) -->
        <h2 style="font-size: 18px; font-weight: 700; margin-top: 40px; margin-bottom: 20px; display: flex; align-items: center;">
            <span style="width: 10px; height: 10px; background: var(--brand); border-radius: 50%; margin-right: 8px;"></span> Viajes en Curso
        </h2>
        @forelse($requests->where('status', 'en_viaje') as $request)
        <div class="request-item" style="border-left: 4px solid var(--brand); cursor: pointer;" onclick="showRequestDetails({{ json_encode($request) }})">
            <div class="request-info">
                <div class="request-badge" style="background: var(--brand-light); color: var(--brand);">🚕</div>
                <div class="request-details">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div class="request-route">{{ $request->start_address }} → {{ $request->destination_address }}</div>
                    </div>
                    <div class="request-meta">
                        <span>Ticket #{{ $request->id }}</span>
                        <span>👤 {{ $request->rut }}</span>
                        <span class="badge badge-brand">En Viaje</span>
                    </div>
                </div>
            </div>
            <form action="{{ route('taxi.updateStatus', $request) }}" method="POST" class="action-group" onclick="event.stopPropagation()">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completado">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase;">Monto Final</label>
                    <input type="text" name="price" placeholder="0" class="price-input" required 
                           oninput="formatPrice(this)">
                </div>
                <button type="submit" class="btn btn-primary" style="background: #10b981; border:none; margin-top: 15px; padding: 10px 24px; border-radius: 12px; font-weight: 700;">Finalizar</button>
            </form>
        </div>
        @empty
        <div style="padding: 20px; text-align: center; color: var(--muted); font-size: 12px;">No hay viajes en curso en este momento.</div>
        @endforelse
    </div>

    <div class="side-column">
        <div class="side-card">
            <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Panel de Control</h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--muted);">Solicitados</span>
                    <span style="font-weight: 800; color: #f59e0b;">{{ $requests->where('status', 'solicitado')->count() }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--muted);">En Curso</span>
                    <span style="font-weight: 800; color: var(--brand);">{{ $requests->where('status', 'en_viaje')->count() }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--muted);">Completados</span>
                    <span style="font-weight: 800; color: #10b981;">{{ $requests->where('status', 'completado')->count() }}</span>
                </div>
            </div>

            <hr style="margin: 24px 0; border: 0; border-top: 1px solid var(--line);">

            <button class="btn btn-outline" style="width: 100%; justify-content: center;" onclick="window.location.reload()">Refrescar Datos</button>
        </div>
    </div>
</div>

<!-- Modal Ver Datos -->
<div class="modal" id="detailsModal">
    <div class="modal-content">
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
                <div class="route-visual">
                    <div class="route-point">
                        <label>Origen</label>
                        <div class="value" style="font-size: 12px;" id="det-start"></div>
                    </div>
                    <div class="route-point end">
                        <label>Destino</label>
                        <div class="value" style="font-size: 12px;" id="det-dest"></div>
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

        <div class="price-tag">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">💵</div>
                <div>
                    <div style="font-size: 10px; font-weight: 700; opacity: 0.6; text-transform: uppercase;">Monto Final del Viaje</div>
                    <div style="font-size: 22px; font-weight: 800;" id="det-price"></div>
                </div>
            </div>
            <div id="det-status-badge"></div>
        </div>

        <div style="margin-top: 24px;">
            <button onclick="closeDetailsModal()" class="btn btn-outline" style="width: 100%; padding: 14px; border-radius: 16px; font-weight: 800;">Entendido</button>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal (Rechazo) -->
<div class="modal" id="confirmRejectionModal">
    <div class="modal-content confirm-modal-content">
        <div class="confirm-icon">
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h2 style="font-size: 20px; font-weight: 800; color: var(--ink); margin-bottom: 12px;">¿Rechazar Solicitud?</h2>
        <p style="color: var(--muted); font-size: 14px; line-height: 1.5; margin-bottom: 32px;">Estás a punto de cancelar este viaje permanentemente. Esta acción no se puede revertir.</p>
        
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <button id="confirm-rejection-btn" class="btn" style="background: #e11d48; color: white; border: none; padding: 16px; border-radius: 14px; font-weight: 800; cursor: pointer; transition: all 0.2s;">
                Sí, Rechazar Viaje
            </button>
            <button onclick="closeConfirmModal()" class="btn btn-outline" style="padding: 16px; border-radius: 14px; font-weight: 800; border-color: var(--line);">
                No, Mantener Solicitud
            </button>
        </div>
    </div>
</div>

<script>
    let pendingRejectionId = null;

    function updateClock() {
        document.getElementById('clock').textContent = new Date().toLocaleTimeString('es-CL');
    }
    setInterval(updateClock, 1000);
    updateClock();

    function formatPrice(input) {
        let value = input.value.replace(/\D/g, "");
        if (value) {
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        } else {
            input.value = "";
        }
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

        const price = request.price > 0 ? `$${new Intl.NumberFormat('es-CL').format(request.price)}` : 'Pendiente';
        document.getElementById('det-price').textContent = price;

        const statusLabels = {
            'solicitado': 'Solicitado',
            'en_viaje': 'En Viaje',
            'completado': 'Finalizado',
            'cancelado': 'Cancelado'
        };
        const statusColors = {
            'solicitado': '#f59e0b',
            'en_viaje': '#3b82f6',
            'completado': '#10b981',
            'cancelado': '#ef4444'
        };

        document.getElementById('det-status-badge').innerHTML = `
            <span style="background: ${statusColors[request.status]}; color: white; padding: 6px 14px; border-radius: 100px; font-size: 10px; font-weight: 800; text-transform: uppercase;">
                ${statusLabels[request.status]}
            </span>
        `;

        document.getElementById('detailsModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Custom Confirmation logic
    function confirmRejection(id) {
        pendingRejectionId = id;
        document.getElementById('confirmRejectionModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        document.getElementById('confirmRejectionModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        pendingRejectionId = null;
    }

    document.getElementById('confirm-rejection-btn').onclick = function() {
        if (pendingRejectionId) {
            document.getElementById(`reject-form-${pendingRejectionId}`).submit();
        }
    };

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeDetailsModal();
            closeConfirmModal();
        }
    }
</script>
@endsection
