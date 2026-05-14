<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar usuario</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        :root {
            --bg: #f5f7fb;
            --ink: #101828;
            --muted: #5b6473;
            --brand: #0f6bb6;
            --brand-2: #0a4f86;
            --line: #e3e8f0;
            --card: #ffffff;
            --error: #b42318;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "DM Sans", "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background: radial-gradient(1200px 500px at 10% 0%, #e7f0ff 0%, transparent 60%),
                        radial-gradient(900px 600px at 90% 10%, #fff1da 0%, transparent 55%),
                        var(--bg);
        }

        .page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 14px 30px rgba(16, 24, 40, 0.08);
            padding: 24px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }

        .label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
        }

        .input, .select {
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            font-size: 14px;
            color: var(--ink);
        }

        .input:focus, .select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1);
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            border: 2px solid transparent;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }


        .btn-ghost {
            background: #ffffff !important;
            color: #2563eb !important;
            border: 1.5px solid #bfdbfe !important;
            border-radius: 9px !important;
            box-shadow: 0 2px 5px rgba(37, 99, 235, 0.05) !important;
            font-weight: 600 !important;
            font-family: inherit !important;
            font-size: 14px !important;
            line-height: 1.2 !important;
            box-sizing: border-box !important;
            height: 40px !important;
            justify-content: center !important;
            gap: 8px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            padding: 10px 20px !important;
        }
        .btn-ghost:active {
            transform: translateY(1px) !important;
        }

        .alert {
            background: #fff5f5;
            color: var(--error);
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <div class="title">Editar usuario</div>
            <div class="subtitle">Actualiza los datos del usuario.</div>

            @if($errors->any())
                <div class="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="field">
                    <label class="label" for="name">Nombre</label>
                    <input class="input" id="name" name="name" type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+" title="El nombre no puede contener números" value="{{ old('name', $user->name) }}" required />
                </div>
                <div class="field">
                    <label class="label" for="email">Correo</label>
                    <input class="input" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                </div>
                <div class="field">
                    <label class="label" for="password">Contrasena (opcional)</label>
                    <input class="input" id="password" name="password" type="password" />
                </div>
                <div class="field">
                    <label class="label" for="role">Rol</label>
                    @php
                        $currentUser = auth()->user();
                        $canEditRole = false;

                        // Super admin puede editar rol de todos menos otros super admin
                        if ($currentUser->isSuperAdmin()) {
                            $canEditRole = !($user->isSuperAdmin() && $user->id !== $currentUser->id);
                        }
                        // Admin puede editar rol solo de gestores y clientes
                        else if ($currentUser->isAdmin()) {
                            $canEditRole = !$user->isSuperAdmin() && !$user->isAdmin();
                        }
                    @endphp

                    @if($canEditRole)
                        <select class="select" id="role" name="role" required>
                            @if($currentUser->isSuperAdmin())
                                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="gestor" {{ old('role', $user->role) === 'gestor' ? 'selected' : '' }}>Gestor</option>
                                <option value="cliente" {{ old('role', $user->role) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                            @elseif($currentUser->isAdmin())
                                <option value="gestor" {{ old('role', $user->role) === 'gestor' ? 'selected' : '' }}>Gestor</option>
                                <option value="cliente" {{ old('role', $user->role) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                            @endif
                        </select>
                    @else
                        <div style="padding: 10px; background: #f3f4f6; border-radius: 8px; font-size: 14px; color: var(--muted);">
                            Rol: <strong>{{ ucfirst($user->role) }}</strong>
                            <p style="font-size: 12px; margin-top: 4px;">No puedes cambiar el rol de este usuario.</p>
                        </div>
                    @endif
                </div>
                <div class="actions">
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">Cancelar</a>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
