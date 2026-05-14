<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión · Taxi Premium</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600,700" rel="stylesheet" />

    <style>
        :root {
            --brand: #0f6bb6;
            --brand-dark: #0a4f86;
            --brand-light: #1b7dc8;
            --bg-left: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
            --text-main: #101828;
            --text-muted: #64748b;
            --line: #e2e8f0;
            --white: #ffffff;
        }

        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "DM Sans", "Space Grotesk", sans-serif;
            background-color: var(--white);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            margin: 0;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .login-left {
            flex: 1.2;
            background: var(--bg-left);
            color: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-left-content {
            position: relative;
            z-index: 2;
            max-width: 540px;
        }

        .login-left h1 {
            font-family: "Space Grotesk", sans-serif;
            font-size: 42px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 24px;
            letter-spacing: -1px;
        }

        .login-left p.description {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 48px;
        }

        .features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .login-right {
            flex: 0.8;
            background: var(--white);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .login-right-content {
            width: 100%;
            max-width: 420px;
        }

        .logo-box {
            margin-bottom: 40px;
            text-align: center;
        }

        .header {
            margin-bottom: 32px;
        }

        .header h2 {
            font-family: "Space Grotesk", sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .label {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 20px;
            height: 20px;
        }

        .input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8fafc;
            font-family: inherit;
        }

        .input:focus {
            outline: none;
            border-color: var(--brand);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--brand);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            font-family: inherit;
        }

        .btn-submit:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        .alert {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 24px;
            border: 1px solid #fecaca;
        }

        /* Quick Login Styles */
        .quick-login {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
        }

        .quick-login-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            text-align: center;
        }

        .quick-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quick-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
        }

        .quick-btn:hover {
            border-color: var(--brand);
            background: var(--white);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .quick-btn-info b {
            display: block;
            font-size: 13px;
            color: var(--text-main);
        }

        .quick-btn-info span {
            font-size: 11px;
            color: var(--text-muted);
        }

        .quick-btn-icon {
            color: var(--brand);
            opacity: 0.5;
        }

        @media (max-width: 1024px) {
            .login-left {
                display: none;
            }
            .login-right {
                flex: 1;
                padding: 40px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-left">
            <div class="login-left-content">
                <h1>Solicita tu transporte<br>de forma premium.</h1>
                <p class="description">
                    Plataforma exclusiva para la gestión de traslados ejecutivos. Solicita, rastrea y gestiona tus viajes corporativos con la mayor comodidad.
                </p>

                <ul class="features">
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        Solicitudes rápidas y seguras
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        Control de gastos por proyecto
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        Historial completo de trayectos
                    </li>
                </ul>
            </div>
        </div>

        <div class="login-right">
            <div class="login-right-content">
                <div class="logo-box">
                    <img src="{{ asset('images/logo-fundacion.png') }}" alt="Logo Fundación" style="height: 80px; width: auto; object-fit: contain;">
                </div>

                <div class="header">
                    <h2>Bienvenido de vuelta</h2>
                    <p>Ingresa tus credenciales para continuar</p>
                </div>

                @if($errors->any())
                    <div class="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="form" id="loginForm">
                    @csrf
                    <div class="field">
                        <label class="label">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <input type="email" name="email" id="email" class="input" placeholder="usuario@empresa.cl" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Contraseña</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" name="password" id="password" class="input" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Iniciar Sesión</button>
                </form>

                <div class="quick-login">
                    <div class="quick-login-title">Acceso Rápido</div>
                    <div class="quick-buttons">
                        <button class="quick-btn" onclick="quickLogin('superadmin@example.com')">
                            <div class="quick-btn-info">
                                <b>Superadministrador</b>
                                <span>Acceso total al sistema</span>
                            </div>
                            <div class="quick-btn-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                        </button>
                        <button class="quick-btn" onclick="quickLogin('admin@example.com')">
                            <div class="quick-btn-info">
                                <b>Administrador</b>
                                <span>Gestión de usuarios y taxis</span>
                            </div>
                            <div class="quick-btn-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        </button>
                        <button class="quick-btn" onclick="quickLogin('user@example.com')">
                            <div class="quick-btn-info">
                                <b>Usuario</b>
                                <span>Solicitud de taxis e historial</span>
                            </div>
                            <div class="quick-btn-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quickLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
            document.getElementById('loginForm').submit();
        }
    </script>
</body>
</html>
