@extends('layouts.app')

@section('title', 'Mis Viajes')

@section('styles')
<style>
    .banner-history {
        background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(15, 107, 182, 0.1);
    }
</style>
@endsection

@section('content')
<div class="banner-history">
    <div>
        <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: 32px; font-weight: 700; letter-spacing: -1px;">Historial de Viajes</h1>
        <p style="opacity: 0.9;">Revisa tus trayectos solicitados y gastos acumulados.</p>
    </div>
    <div style="background: rgba(255,255,255,0.15); padding: 16px 32px; border-radius: 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); text-align: right;">
        <p style="font-size: 11px; opacity: 0.8; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Total Gastado</p>
        <p style="font-size: 28px; font-weight: 800;">${{ number_format($totalSpent, 0, ',', '.') }}</p>
    </div>
</div>

<div class="card" style="padding:0;">
    <div style="padding: 24px; border-bottom: 1px solid var(--line); font-weight: 700; color: var(--ink);">
        Listado de Trayectos
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Proyecto / OT</th>
                    <th>Costo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td style="font-weight: 600; color: var(--ink);">{{ $request->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-size: 12px; color: var(--muted);">{{ $request->start_address }}</td>
                    <td style="font-size: 12px; color: var(--muted);">{{ $request->destination_address }}</td>
                    <td>
                        @if($request->is_associated_ot)
                            <span class="badge badge-brand">{{ $request->project_number }}</span>
                        @else
                            <span style="color: var(--muted); font-size: 11px;">Particular</span>
                        @endif
                    </td>
                    <td style="font-weight: 800; color: var(--brand); font-size: 14px;">
                        @if($request->price > 0)
                            ${{ number_format($request->price, 0, ',', '.') }}
                        @else
                            <span style="color: var(--muted); font-weight: 400; font-size: 12px; font-style: italic;">Pendiente</span>
                        @endif
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
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 60px; color: var(--muted);">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 16px; opacity: 0.3;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p style="font-weight: 600;">No has realizado solicitudes de taxi aún.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
