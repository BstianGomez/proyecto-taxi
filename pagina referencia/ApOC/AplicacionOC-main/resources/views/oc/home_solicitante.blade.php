<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio - Aplicación OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        @include('oc.partials.common_styles')

        :root {
            --bg: #f5f7fb;
            --ink: #101828;
            --muted: #5b6473;
            --brand: #0f6bb6;
            --brand-2: #0a4f86;
            --line: #e3e8f0;
            --card: #ffffff;
            --success: #0f7a3e;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "DM Sans", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background-color: var(--bg);
            min-height: 100vh;
        }

        /* Safety overrides for mysterious background elements */
        body::before, body::after {
            display: none !important;
            content: none !important;
        }

        .content {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Banner Section */
        .banner {
            background: linear-gradient(135deg, #0f6bb6 0%, #1b7dc8 100%);
            border-radius: 20px;
            padding: 24px 32px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(15, 107, 182, 0.15);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }

        .banner-content {
            z-index: 1;
            max-width: 600px;
        }

        .banner h1 {
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .banner p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
            margin: 0;
        }

        /* Cards Grid */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .option-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            text-decoration: none;
            color: inherit;
            border: 1px solid #e3e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            position: relative;
        }

        .option-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15, 107, 182, 0.12);
            border-color: var(--brand);
        }

        .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .option-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .icon-cliente { background: #eff6ff; color: #3b82f6; }
        .icon-interna { background: #fef2f2; color: #ef4444; }
        .icon-negocio { background: #f0fdf4; color: #22c55e; }

        .option-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 10px;
            color: #1a202c;
        }

        .option-card p {
            font-size: 14px;
            color: #718096;
            line-height: 1.5;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .card-footer {
            display: flex;
            align-items: center;
            color: var(--brand);
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-footer svg {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .option-card:hover .card-footer svg {
            transform: translateX(4px);
        }

        @media (max-width: 1024px) {
            .options-grid { grid-template-columns: 1fr; }
            .banner { padding: 30px; }
            .banner h1 { font-size: 24px; }
        }
    </style>
    @include('oc.partials.common_scripts')
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'inicio'])

        <div class="main-content">
            <x-page-header 
                title="" 
                subtitle=""
                :showLogout="false"
            />

            <main class="content">
            <div class="banner">
                <div class="banner-content">
                    <h1>Bienvenido al Portal de Órdenes de Compra</h1>
                    <p>Seleccione el tipo de solicitud que desea realizar. Nuestro sistema le guiará en el proceso de creación de su OC.</p>
                </div>
                <div style="opacity: 0.2; z-index: 0;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM17 15H7v2h10v-2zm0-4H7v2h10v-2zm0-4H7v2h10V7z"/>
                    </svg>
                </div>
            </div>

            <div class="options-grid">
                <!-- OC Cliente -->
                <a href="{{ route('oc.cliente') }}" class="option-card">
                    <div class="icon-box icon-cliente">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3>OC Cliente</h3>
                    <p>Solicitud de orden para Clientes Externos.</p>
                    <div class="card-footer">
                        Comenzar solicitud
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                </a>

                <!-- OC Interna -->
                <a href="{{ route('oc.interna') }}" class="option-card">
                    <div class="icon-box icon-interna">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <h3>OC Interna</h3>
                    <p>Uso interno de la fundación.</p>
                    <div class="card-footer">
                        Comenzar solicitud
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                </a>

                <!-- OC Negocio -->
                <a href="{{ route('oc.negocio') }}" class="option-card">
                    <div class="icon-box icon-negocio">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <h3>OC Negocio</h3>
                    <p>Para Unidades de Negocio (OT, OP, DI, OR, etc.)</p>
                    <div class="card-footer">
                        Comenzar solicitud
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
            
            <div style="margin-top: 40px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="font-size: 22px; font-weight: 700; color: #1a202c; margin: 0;">Sus últimas solicitudes</h3>
                    <a href="{{ route('oc.index') }}" style="text-decoration: none; color: var(--brand); font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                        Ver todas
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>

                @if($recentRequests->isEmpty())
                    <div style="background: white; border-radius: 16px; padding: 40px; text-align: center; border: 1px dashed #cbd5e1;">
                        <p style="color: #64748b; margin: 0;">Aún no has realizado ninguna solicitud.</p>
                    </div>
                @else
                    <div style="background: white; border-radius: 20px; overflow: hidden; border: 1px solid #e3e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1px solid #e3e8f0;">
                                    <th style="padding: 16px 24px; font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase;">ID</th>
                                    <th style="padding: 16px 24px; font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase;">Proveedor</th>
                                    <th style="padding: 16px 24px; font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase;">Monto</th>
                                    <th style="padding: 16px 24px; font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase;">Estado</th>
                                    <th style="padding: 16px 24px; font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase;">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                        <td style="padding: 16px 24px; font-weight: 600; color: #1e293b;">#{{ $request->id }}</td>
                                        <td style="padding: 16px 24px; color: #475569;">{{ $request->proveedor ?? 'N/A' }}</td>
                                        <td style="padding: 16px 24px; font-weight: 600; color: #1e293b;">${{ number_format($request->monto, 0, ',', '.') }}</td>
                                        <td style="padding: 16px 24px;">
                                            @php
                                                $color = '#b97700'; // Solicitada
                                                if($request->estado == 'Aceptada' || $request->estado == 'Enviada') $color = '#0f7a3e';
                                                elseif($request->estado == 'Rechazada') $color = '#dc2626';
                                                elseif($request->estado == 'Facturado') $color = '#0284c7';
                                            @endphp
                                            <span style="background: {{ $color }}15; color: {{ $color }}; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 700;">
                                                {{ $request->estado }}
                                            </span>
                                        </td>
                                        <td style="padding: 16px 24px; color: #64748b; font-size: 14px;">{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                                    </tr>
                                    @if($request->manager_comment)
                                        <tr style="background: #fafbff; border-bottom: 1px solid #f1f5f9;">
                                            <td colspan="5" style="padding: 8px 24px 16px 48px;">
                                                <div style="display: flex; gap: 16px; align-items: flex-start;">
                                                    <div style="flex: 1;">
                                                        <div style="font-size: 11px; font-weight: 700; color: #0f6bb6; text-transform: uppercase; margin-bottom: 4px;">Mensaje del gestor:</div>
                                                        <div style="font-size: 13px; color: #1e293b; background: white; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; line-height: 1.4;">{{ $request->manager_comment }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
</body>
</html>
