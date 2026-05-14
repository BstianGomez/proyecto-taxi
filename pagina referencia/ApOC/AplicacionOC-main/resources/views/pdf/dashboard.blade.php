<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard OC - Reporte PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0f6bb6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #0f6bb6;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 12px;
        }
        .date {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            gap: 20px;
        }
        .stat-box {
            flex: 1;
            padding: 15px;
            border: 2px solid #e3e8f0;
            border-radius: 8px;
            text-align: center;
            background: #f9fafb;
        }
        .stat-box h3 {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-box .value {
            font-size: 32px;
            font-weight: bold;
            color: #0f6bb6;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f6bb6;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #0f6bb6;
            padding-bottom: 8px;
        }
        .summary-box {
            margin-bottom: 20px;
            padding: 15px;
            background: #f0f7ff;
            border-left: 4px solid #0f6bb6;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .summary-item {
            padding: 10px;
            background: #fff;
            border: 1px solid #e3e8f0;
            border-radius: 4px;
        }
        .summary-item-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-item-value {
            font-size: 14px;
            font-weight: bold;
            color: #0f6bb6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }
        table thead {
            background: #0f6bb6;
            color: white;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0f6bb6;
        }
        table td {
            padding: 8px 10px;
            border: 1px solid #e3e8f0;
        }
        table tbody tr:nth-child(odd) {
            background: #f9fafb;
        }
        table tbody tr:hover {
            background: #f0f7ff;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }
        .status-solicitada {
            background: #e0e7ff;
            color: #312e81;
        }
        .status-enviada {
            background: #fed7aa;
            color: #92400e;
        }
        .status-aceptada {
            background: #d1fae5;
            color: #065f46;
        }
        .status-rechazada {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e3e8f0;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        .currency {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard de Órdenes de Compra</h1>
        <p>Reporte Ejecutivo de Solicitudes</p>
        <div class="date">Generado: {{ date('d/m/Y H:i:s') }}</div>
    </div>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="stat-box">
            <h3>Solicitadas</h3>
            <div class="value">{{ $statusCounts['Solicitada'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>Enviadas</h3>
            <div class="value">{{ $statusCounts['Enviada'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>Aceptadas</h3>
            <div class="value">{{ $statusCounts['Aceptada'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h3>Rechazadas</h3>
            <div class="value">{{ $statusCounts['Rechazada'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Gráficos de Resumen -->
    <div class="section-title">Gráficos de Resumen</div>
    @if(!empty($grafico1) || !empty($grafico2))
    <div style="margin-bottom: 25px; text-align: center;">
        @if(!empty($grafico1))
            <img src="{{ $grafico1 }}" style="max-width: 48%; display: inline-block; border: 1px solid #e2e8f0; padding: 10px; background: white; margin-right: 2%; vertical-align: top;">
        @endif
        @if(!empty($grafico2))
            <img src="{{ $grafico2 }}" style="max-width: 48%; display: inline-block; border: 1px solid #e2e8f0; padding: 10px; background: white; vertical-align: top;">
        @endif
    </div>
    @else
    @php
        $maxCecoMonto = (isset($sumByCeco) && $sumByCeco->count() > 0) ? max(1, (float)($sumByCeco->max('total_monto') ?? 1)) : 1;
        $maxEstadoMonto = (isset($statusCostos) && count($statusCostos) > 0) ? max(1, (float)max($statusCostos)) : 1;
    @endphp
    <div style="margin-bottom: 25px; display: flex; gap: 18px; align-items: flex-start;">
        <div style="width: 49%; border: 1px solid #e2e8f0; background: #fff; padding: 12px; border-radius: 8px;">
            <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Monto total por CECO</div>
            @foreach(($sumByCeco ?? collect())->take(6) as $item)
                @php $w = (int) round((((float)$item->total_monto) / $maxCecoMonto) * 100); @endphp
                <div style="margin-bottom: 8px;">
                    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 3px; color: #334155;">
                        <span>{{ $item->ceco }}</span>
                        <span>${{ number_format((float)$item->total_monto, 0, ',', '.') }}</span>
                    </div>
                    <div style="height: 8px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                        <div style="height: 8px; width: {{ $w }}%; background: #2563eb;"></div>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="width: 49%; border: 1px solid #e2e8f0; background: #fff; padding: 12px; border-radius: 8px;">
            <div style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Monto total por estado</div>
            @foreach((['Solicitada','Enviada','Aceptada','Rechazada','Facturado']) as $estado)
                @php
                    $montoEstado = (float)($statusCostos[$estado] ?? 0);
                    $wEstado = (int) round(($montoEstado / $maxEstadoMonto) * 100);
                @endphp
                <div style="margin-bottom: 8px;">
                    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 3px; color: #334155;">
                        <span>{{ $estado }}</span>
                        <span>${{ number_format($montoEstado, 0, ',', '.') }}</span>
                    </div>
                    <div style="height: 8px; background: #e2e8f0; border-radius: 999px; overflow: hidden;">
                        <div style="height: 8px; width: {{ $wEstado }}%; background: #0ea5e9;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabla de Solicitudes -->
    <div class="section-title">Solicitudes Registradas</div>
    <table>
        <thead>
            <tr>
                <th>CECO</th>
                <th>Tipo</th>
                <th>Documento</th>
                <th>Estado</th>
                <th>Proveedor</th>
                <th>Descripción</th>
                <th style="text-align: center;">Cant.</th>
                <th class="currency">Monto</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row->ceco }}</td>
                    <td>{{ $row->tipo_solicitud }}</td>
                    <td>{{ $row->tipo_documento }}</td>
                    <td>
                        @if($row->estado === 'Solicitada')
                            <span class="status-badge status-solicitada">{{ $row->estado }}</span>
                        @elseif($row->estado === 'Enviada')
                            <span class="status-badge status-enviada">{{ $row->estado }}</span>
                        @elseif($row->estado === 'Aceptada')
                            <span class="status-badge status-aceptada">{{ $row->estado }}</span>
                        @elseif($row->estado === 'Rechazada')
                            <span class="status-badge status-rechazada">{{ $row->estado }}</span>
                        @else
                            <span class="status-badge">{{ $row->estado }}</span>
                        @endif
                    </td>
                    <td>{{ $row->proveedor }}</td>
                    <td>{{ substr($row->descripcion ?? '', 0, 40) }}{{ strlen($row->descripcion ?? '') > 40 ? '...' : '' }}</td>
                    <td style="text-align: center;">{{ $row->cantidad }}</td>
                    <td class="currency">${{ number_format($row->monto ?? 0, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #999;">No hay solicitudes registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Total General -->
    <div style="margin-top: 20px; padding: 15px; background: #f0f7ff; border: 2px solid #0f6bb6; border-radius: 8px; text-align: right;">
        <strong style="font-size: 16px; color: #0f6bb6; margin-bottom: 10px; display: block;">Total General: ${{ number_format($rows->sum('monto'), 0, ',', '.') }}</strong>
        
        <table style="font-size: 12px; margin-left: auto; margin-right: 0; background: #fff; border-collapse: collapse; border: 1px solid #d1d5db; width: 250px;">
            <tr>
                <td style="padding: 6px 12px; text-align: left;"><strong style="color: #64748b;">Solicitado:</strong></td>
                <td style="padding: 6px 12px; text-align: right;">${{ number_format($statusCostos['Solicitada'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; text-align: left;"><strong style="color: #10b981;">Aceptado:</strong></td>
                <td style="padding: 6px 12px; text-align: right;">${{ number_format($statusCostos['Aceptada'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 12px; text-align: left;"><strong style="color: #ef4444;">Rechazado:</strong></td>
                <td style="padding: 6px 12px; text-align: right;">${{ number_format($statusCostos['Rechazada'] ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <br>
        <span style="font-size: 11px; color: #666; display: inline-block; margin-top: 10px;">Total de solicitudes: {{ $rows->count() }}</span>
    </div>

    <div class="footer">
        <p>Este es un reporte automático generado desde el Dashboard de Órdenes de Compra</p>
    </div>
</body>
</html>
