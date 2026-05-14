<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Validación - OC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        
        .header {
            padding: 40px 30px;
            text-align: center;
        }
        
        .header.success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .header.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }
        
        .icon {
            font-size: 48px;
            margin-bottom: 16px;
        }
        
        .header h1 {
            font-size: 28px;
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
        
        .message {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid;
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .message.success {
            border-left-color: #10b981;
            color: #065f46;
            background: #d1fae5;
        }
        
        .message.error {
            border-left-color: #ef4444;
            color: #991b1b;
            background: #fee2e2;
        }
        
        .details {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #e3e8f0;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 13px;
            border-bottom: 1px solid #e3e8f0;
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
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-aceptada {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-rechazada {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0b5fa5, #0f6bb6);
            color: white;
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.2);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0f6bb6, #0a4f86);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 107, 182, 0.3);
            text-decoration: none;
        }
        
        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            border-top: 1px solid #e3e8f0;
            text-align: center;
            font-size: 12px;
            color: #5b6473;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($success)
            <div class="header success">
                <div class="icon">✓</div>
                <h1>¡Operación Exitosa!</h1>
                <p>{{ $message }}</p>
            </div>
        @else
            <div class="header error">
                <div class="icon">✗</div>
                <h1>Error en la Operación</h1>
                <p>{{ $message }}</p>
            </div>
        @endif
        
        <div class="content">
            <div class="message {{ $success ? 'success' : 'error' }}">
                <strong>{{ $status }}</strong> - La solicitud ha sido {{ $status === 'Aceptada' ? 'aceptada' : ($status === 'Rechazada' ? 'rechazada' : $status) }} correctamente.
            </div>
            
            @if(isset($solicitud))
                <div class="details">
                    <div style="margin-bottom: 12px; font-weight: 600; color: #101828;">Detalles de la Solicitud</div>
                    <div class="detail-row">
                        <span class="detail-label">ID:</span>
                        <span class="detail-value">#{{ $solicitud->id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">CECO:</span>
                        <span class="detail-value">{{ $solicitud->ceco }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tipo:</span>
                        <span class="detail-value">{{ $solicitud->tipo_solicitud }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Proveedor:</span>
                        <span class="detail-value">{{ $solicitud->proveedor ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Monto:</span>
                        <span class="detail-value" style="font-weight: 700; color: #0f6bb6;">${{ number_format($solicitud->monto, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Estado:</span>
                        <span class="detail-value">
                            <span class="status-badge status-{{ strtolower($solicitud->estado) }}">
                                {{ $solicitud->estado }}
                            </span>
                        </span>
                    </div>
                </div>
            @endif
            
            <div class="actions">
                <a href="{{ route('oc.index') }}" class="btn btn-primary">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>Fundación Sofofa - Sistema de Órdenes de Compra</p>
        </div>
    </div>
</body>
</html>
