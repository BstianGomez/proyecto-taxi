<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud OC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: #f5f7fb;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #0f6bb6 0%, #0a4f86 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-new {
            background: #e8f1fb;
            color: #0f6bb6;
        }
        
        .status-accepted {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .details {
            background: #f9fafb;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #e3e8f0;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e3e8f0;
            font-size: 14px;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #5b6473;
            font-weight: 600;
        }
        
        .detail-value {
            color: #101828;
            font-weight: 500;
        }
        
        .actions {
            display: flex;
            gap: 12px;
            margin: 32px 0;
            justify-content: center;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .btn-accept {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .btn-accept:hover {
            background: linear-gradient(135deg, #059669, #047857);
            text-decoration: none;
        }
        
        .btn-reject {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }
        
        .btn-reject:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            text-decoration: none;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
        }
        
        .btn-view:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            text-decoration: none;
        }
        
        .message {
            background: #f0f9ff;
            border-left: 4px solid #0f6bb6;
            padding: 16px;
            border-radius: 6px;
            margin: 24px 0;
            font-size: 14px;
            color: #0c4a6e;
            line-height: 1.6;
        }
        
        .footer {
            background: #f9fafb;
            padding: 24px 30px;
            border-top: 1px solid #e3e8f0;
            text-align: center;
            font-size: 12px;
            color: #5b6473;
        }
        
        .footer a {
            color: #0f6bb6;
            text-decoration: none;
        }
        
        .divider {
            height: 1px;
            background: #e3e8f0;
            margin: 24px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($tipo === 'created' || $tipo === 'info')
            <div class="header">
                <h1>Nueva Solicitud OC {{ isset($solicitud->tipo_solicitud) ? '- ' . ucfirst($solicitud->tipo_solicitud) : '' }}</h1>
                <p>Se ha registrado una nueva solicitud de Orden de Compra</p>
            </div>
        @elseif($tipo === 'accepted')
            <div class="header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h1>Solicitud {{ isset($solicitud->tipo_solicitud) ? ucfirst($solicitud->tipo_solicitud) . ' ' : '' }}Aceptada ✓</h1>
                <p>La solicitud de OC ha sido aceptada correctamente</p>
            </div>
        @elseif($tipo === 'rejected')
            <div class="header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h1>Solicitud {{ isset($solicitud->tipo_solicitud) ? ucfirst($solicitud->tipo_solicitud) . ' ' : '' }}Rechazada ✗</h1>
                <p>La solicitud de OC ha sido rechazada</p>
            </div>
        @endif
        
        <div class="content">
            @if($tipo === 'created')
                <span class="status-badge status-new">Pendiente de Aprobación</span>
                
                <div class="message">
                    <strong>⏱️ Acción Requerida:</strong> Monto mayor a $1.000.000. Requiere validación directa desde este correo. Haga clic en los botones para aceptar o rechazar.
                </div>
            @elseif($tipo === 'info')
                <span class="status-badge status-new">Informativo</span>
                
                <div class="message" style="background:#f1f5f9; color:#475569; border-left:4px solid #cbd5e1;">
                    <strong>ℹ️ Aviso Automático:</strong> Esta solicitud es menor o igual a $1.000.000. No requiere de aprobación manual en este correo. Ya se encuentra disponible en el Gestor.
                </div>
            @elseif($tipo === 'accepted')
                <span class="status-badge status-accepted">Aceptada</span>
            @elseif($tipo === 'rejected')
                <span class="status-badge status-rejected">Rechazada</span>
            @endif
            
            <h2 style="font-size: 18px; color: #101828; margin-bottom: 16px;">Detalles de la Solicitud</h2>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">ID:</span>
                    <span class="detail-value">#{{ $solicitud->id ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">CECO:</span>
                    <span class="detail-value">{{ $solicitud->ceco ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipo:</span>
                    <span class="detail-value">{{ $solicitud->tipo_solicitud ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipo de Documento:</span>
                    <span class="detail-value">{{ $solicitud->tipo_documento ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Proveedor:</span>
                    <span class="detail-value">{{ $solicitud->proveedor ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">RUT:</span>
                    <span class="detail-value">{{ $solicitud->rut ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Descripción:</span>
                    <span class="detail-value">{{ $solicitud->descripcion ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cantidad:</span>
                    <span class="detail-value">{{ $solicitud->cantidad ?? 0 }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Monto:</span>
                    <span class="detail-value" style="font-weight: 700; color: #0f6bb6;">${{ number_format($solicitud->monto ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y H:i') }}</span>
                </div>
                
                @php
                    $extraData = isset($solicitud->datos_extra) ? json_decode($solicitud->datos_extra, true) : [];
                    $excludeKeys = ['_token', 'proveedor', 'rut', 'email_proveedor', 'descripcion', 'ceco', 'monto', 'cantidad'];
                    $quien_envio = 'Desconocido (No detectado)';
                    
                    $montoVal = floatval($solicitud->monto ?? 0);
                    if ($montoVal >= 1000000) {
                        $userKeys = ['correo_sesion_usuario', 'nombre_sesion_usuario', 'nombre_solicitante', 'nombre_empleado', 'nombre', 'email'];
                    } else {
                        $userKeys = ['nombre_solicitante', 'nombre_empleado', 'nombre', 'coordinador', 'coordinador_servicios', 'email', 'correo_sesion_usuario'];
                    }

                    if ($extraData) {
                        foreach ($userKeys as $k) {
                            if (!empty($extraData[$k])) {
                                $quien_envio = $extraData[$k];
                                break;
                            }
                        }
                    }
                @endphp
                
                <div class="detail-row" style="background:#e0f2fe; margin-top:8px; border-radius:4px; padding:8px;">
                    <span class="detail-label">Enviado por:</span>
                    <span class="detail-value" style="font-weight: bold; color: #0369a1;">{{ $quien_envio }}</span>
                </div>

                @if($extraData && count(array_diff(array_keys($extraData), $excludeKeys)) > 0)
                    <h2 style="font-size: 16px; color: #101828; margin-top: 24px; margin-bottom: 12px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">Datos Completos de la Solicitud</h2>
                    @foreach($extraData as $key => $value)
                        @if(!in_array($key, $excludeKeys))
                            <div class="detail-row">
                                <span class="detail-label">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                <span class="detail-value">{{ is_array($value) ? json_encode($value) : ($value ?: 'N/A') }}</span>
                            </div>
                        @endif
                    @endforeach
                @endif
                
            </div>
            
            @if($tipo === 'created')
                <div class="actions">
                    <a href="{{ url('/oc/solicitudes/' . $solicitud->id . '/aceptar-email') }}" class="btn btn-accept">
                        ✓ Aceptar
                    </a>
                    <a href="{{ url('/oc/solicitudes/' . $solicitud->id . '/rechazar-email') }}" class="btn btn-reject">
                        ✗ Rechazar
                    </a>
                </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Fundación Sofofa - Sistema de Órdenes de Compra</p>
            <p style="margin-top: 8px; opacity: 0.7;">
                Si tiene dudas respecto a esta solicitud, por favor comuníquese con el área correspondiente.
            </p>
        </div>
    </div>
</body>
</html>
