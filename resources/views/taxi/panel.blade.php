@extends('layouts.app')

@section('title', 'Panel de Conductor - Taxi Premium')

@section('styles')
<style>
    .taxi-container {
        max-width: 500px;
        margin: 40px auto;
        padding: 20px;
    }

    .offer-card {
        background: white;
        border-radius: 32px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        border: 1px solid var(--line);
        position: relative;
        overflow: hidden;
    }

    .offer-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 8px;
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
    }

    .offer-icon {
        width: 80px;
        height: 80px;
        background: #fef3c7;
        color: #d97706;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .route-display {
        background: #f8fafc;
        border-radius: 20px;
        padding: 24px;
        margin: 32px 0;
        text-align: left;
    }

    .route-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 12px;
    }

    .btn-taxi {
        width: 100%;
        padding: 18px;
        border-radius: 16px;
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .btn-accept {
        background: #10b981;
        color: white;
        border: none;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-reject {
        background: white;
        border: 2px solid #f1f5f9;
        color: #64748b;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted);
    }
</style>
@endsection

@section('content')
<!-- DEBUG: Request ID: {{ $request ? $request->id : 'NULL' }} -->
<div class="taxi-container">
    @if($request)
    <div class="offer-card">
        <div class="offer-icon">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
        </div>

        <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 700; color: var(--ink);">¡Nuevo Viaje!</h2>
        <p style="color: var(--muted); margin-top: 8px;">Tienes una solicitud esperando respuesta.</p>

        <div class="route-display">
            <div style="margin-bottom: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Recogida</div>
                <div style="font-weight: 700; color: var(--ink); font-size: 16px;">
                    <span class="route-dot" style="background: #10b981;"></span>
                    {{ $request->start_address }}
                </div>
            </div>
            <div>
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Destino</div>
                <div style="font-weight: 700; color: var(--ink); font-size: 16px;">
                    <span class="route-dot" style="background: #ef4444;"></span>
                    {{ $request->destination_address }}
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding: 0 10px;">
            <div style="text-align: left;">
                <div style="font-size: 11px; color: var(--muted);">Tarifa Est.</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--brand);">
                    @if($request->price > 0)
                        ${{ number_format($request->price, 0, ',', '.') }}
                    @else
                        <span style="font-size: 14px; font-weight: 400; color: var(--muted); font-style: italic;">Pendiente</span>
                    @endif
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 11px; color: var(--muted);">Pasajero</div>
                <div style="font-weight: 700;">{{ substr($request->rut, 0, 10) }}...</div>
            </div>
        </div>

        <form action="{{ route('taxi.accept', $request) }}" method="POST">
            @csrf
            <button type="submit" class="btn-taxi btn-accept">ACEPTAR CARRERA</button>
        </form>

        <form action="{{ route('taxi.reject', $request) }}" method="POST">
            @csrf
            <button type="submit" class="btn-taxi btn-reject">RECHAZAR</button>
        </form>
    </div>
    @else
    <div class="empty-state">
        <div style="font-size: 64px; margin-bottom: 24px;">📡</div>
        <h2 style="font-family: 'Space Grotesk', sans-serif; color: var(--ink); font-weight: 700;">Buscando viajes...</h2>
        <p style="margin-top: 12px; max-width: 300px; margin-left: auto; margin-right: auto;">Mantente en esta página para recibir nuevas solicitudes automáticamente.</p>
        
        <div style="margin-top: 40px; display: flex; justify-content: center; gap: 8px;">
            <div style="width: 8px; height: 8px; background: var(--brand); border-radius: 50%; animation: pulse 1.5s infinite 0s;"></div>
            <div style="width: 8px; height: 8px; background: var(--brand); border-radius: 50%; animation: pulse 1.5s infinite 0.2s;"></div>
            <div style="width: 8px; height: 8px; background: var(--brand); border-radius: 50%; animation: pulse 1.5s infinite 0.4s;"></div>
        </div>
    </div>

    <script>
        // Auto refresh to check for new offers
        setTimeout(() => {
            window.location.reload();
        }, 3000); // Check every 3 seconds
    </script>
    @endif
</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 0.3; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1.2); }
    }
</style>
@endsection
