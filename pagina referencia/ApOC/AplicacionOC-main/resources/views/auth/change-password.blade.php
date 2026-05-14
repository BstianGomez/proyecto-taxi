<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambiar Contraseña · Sistema OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        :root {
            --bg: #f5f7fb;
            --ink: #101828;
            --muted: #5b6473;
            --brand: #0f6bb6;
            --brand-light: #1b7dc8;
            --brand-dark: #0a4f86;
            --line: #e3e8f0;
            --card: #ffffff;
            --error: #dc2626;
            --error-bg: #fef2f2;
            --error-border: #fecaca;
            --warning: #f59e0b;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            --success: #10b981;
        }

        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "DM Sans", "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background: 
                radial-gradient(circle at 20% 20%, rgba(15, 107, 182, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(27, 125, 200, 0.06) 0%, transparent 50%),
                linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            opacity: 0.4;
            z-index: 0;
        }

        body::before {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(15, 107, 182, 0.15) 0%, transparent 70%);
            top: -200px;
            right: -100px;
        }

        body::after {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(27, 125, 200, 0.12) 0%, transparent 70%);
            bottom: -150px;
            left: -50px;
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            position: relative;
            z-index: 1;
        }

        .container {
            width: 100%;
            max-width: 440px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 
                0 1px 2px rgba(0, 0, 0, 0.03),
                0 4px 8px rgba(0, 0, 0, 0.04),
                0 16px 32px rgba(0, 0, 0, 0.05);
            padding: 40px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            margin-bottom: 32px;
            text-align: center;
        }

        .card-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .card-subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            color: var(--ink);
            transition: all 0.2s ease;
            appearance: none;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1);
        }

        .form-control.error {
            border-color: var(--error);
            background: var(--error-bg);
        }

        .form-error {
            font-size: 13px;
            color: var(--error);
            margin-top: 6px;
            display: block;
        }

        .form-hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 8px;
            line-height: 1.6;
        }

        .form-hint strong {
            color: var(--ink);
        }

        .btn {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            color: #ffffff !important;
            border: 1px solid #1e40af !important;
            border-radius: 9px !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
            text-shadow: 0 1px 1px rgba(0,0,0,0.1) !important;
            font-weight: 600 !important;
            font-family: inherit !important;
            font-size: 14px !important;
            line-height: 1.2 !important;
            box-sizing: border-box !important;
            height: 40px !important;
            justify-content: center !important;
            gap: 8px !important;
            padding: 10px 20px !important;
            display: inline-flex !important;
            align-items: center !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .btn-primary:hover { background: linear-gradient(135deg, #1d4ed8, #1e3a8a) !important; transform: translateY(-2px) !important; box-shadow: 0 6px 14px rgba(37, 99, 235, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1) !important; 
            background: var(--brand-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 107, 182, 0.2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 24px;
            border: 1px solid;
        }

        .alert-warning {
            background: var(--warning-bg);
            border-color: var(--warning-border);
            color: #92400e;
        }

        .alert-error {
            background: var(--error-bg);
            border-color: var(--error-border);
            color: var(--error);
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert li {
            margin-bottom: 4px;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        hr {
            margin: 24px 0;
            border: none;
            height: 1px;
            background: var(--line);
        }

        .requirements {
            padding: 16px;
            background: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.8;
        }

        .requirement {
            display: flex;
            align-items: flex-start;
            margin-bottom: 6px;
        }

        .requirement:last-child {
            margin-bottom: 0;
        }

        .requirement-icon {
            margin-right: 8px;
            color: var(--success);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <x-global-alerts />
    <div class="page">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">🔐 Cambiar Contraseña</div>
                    <div class="card-subtitle">Por seguridad, debes establecer una nueva contraseña</div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="current_password">Contraseña Actual</label>
                        <input type="password" id="current_password" name="current_password"
                            class="form-control @error('current_password') error @enderror"
                            placeholder="Ingresa tu contraseña actual" required>
                        @error('current_password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Nueva Contraseña</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') error @enderror"
                            placeholder="Crea una contraseña fuerte" required>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <div class="form-hint">
                            <strong>Requisitos:</strong>
                            <div class="requirements">
                                <div class="requirement">
                                    <span class="requirement-icon">✓</span> Mínimo 12 caracteres
                                </div>
                                <div class="requirement">
                                    <span class="requirement-icon">✓</span> Al menos 1 mayúscula (A-Z)
                                </div>
                                <div class="requirement">
                                    <span class="requirement-icon">✓</span> Al menos 1 minúscula (a-z)
                                </div>
                                <div class="requirement">
                                    <span class="requirement-icon">✓</span> Al menos 1 número (0-9)
                                </div>
                                <div class="requirement">
                                    <span class="requirement-icon">✓</span> Al menos 1 carácter especial (!@#$%^&*)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirmar Nueva Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control"
                            placeholder="Confirma tu nueva contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        🔒 Cambiar Contraseña
                    </button>
                </form>

                <hr>

                <div class="alert alert-warning">
                    <strong>⚠️ Nota de Seguridad:</strong><br>
                    No puedes reutilizar contraseñas antiguas. Esto protege tu cuenta de accesos no autorizados.
                </div>
            </div>
        </div>
    </div>
</body>
</html>

