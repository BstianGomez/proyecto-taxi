@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('styles')
<style>
    .banner-users {
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

    /* Modal */
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
        max-width: 500px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalScale {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
    }

    .input-group label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
    }

    .input-field {
        padding: 12px 16px;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .input-field:focus {
        outline: none;
        border-color: var(--brand);
        background: white;
        box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
    }

    .alert {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 600;
    }
    .alert-success { background: var(--success-bg); color: var(--success); }
    .alert-error { background: var(--danger-bg); color: var(--danger); }
</style>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="banner-users">
    <div>
        <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: 32px; font-weight: 700; letter-spacing: -1px;">Gestión de Usuarios</h1>
        <p style="opacity: 0.9;">Administra los accesos, roles y estadísticas de uso de la plataforma.</p>
    </div>
    <button class="btn" onclick="openCreateModal()" style="background: white; color: var(--brand); padding: 14px 28px; border-radius: 14px; font-weight: 700; border:none; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 8px; vertical-align: middle;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Registrar Nuevo Usuario
    </button>
</div>

<div class="card" style="padding:0;">
    <div style="padding: 24px; border-bottom: 1px solid var(--line); font-weight: 700; color: var(--ink);">
        Usuarios Registrados
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Viajes</th>
                    <th>Gasto Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="avatar" style="background: var(--brand-light); color: var(--brand); font-size: 11px; font-weight: 800;">{{ substr($user->name, 0, 1) }}</div>
                            <span style="font-weight: 700; color: var(--ink);">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color: var(--muted);">{{ $user->email }}</td>
                    <td>
                        @php
                            $badgeClass = match($user->role) {
                                'superadmin' => 'badge-danger',
                                'admin' => 'badge-brand',
                                default => 'badge-success',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td style="font-weight: 600;">{{ $user->taxi_requests_count }}</td>
                    <td style="font-weight: 800; color: var(--brand); font-size: 14px;">${{ number_format($user->taxi_requests_sum_price ?? 0, 0, ',', '.') }}</td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            @if(auth()->user()->role === 'superadmin' || $user->role !== 'superadmin')
                                <button class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px;" 
                                    onclick="openEditModal({{ json_encode($user) }})">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px; background: #fff1f2; border: 1.5px solid #fecaca; color: #e11d48;">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            @else
                                <span style="font-size: 11px; color: var(--muted); font-style: italic;">Sin acceso</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear Usuario -->
<div class="modal" id="createModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 700; color: var(--ink);">Registrar Nuevo Usuario</h2>
            <button onclick="closeModal('createModal')" style="background:none; border:none; cursor:pointer; color: var(--muted);">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="input-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" class="input-field" required placeholder="Juan Pérez">
            </div>
            <div class="input-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" class="input-field" required placeholder="juan@empresa.cl">
            </div>
            <div class="input-group">
                <label>Rol de Usuario</label>
                <select name="role" class="input-field" required>
                    <option value="usuario">Usuario Estándar</option>
                    <option value="admin">Administrador</option>
                    @if(auth()->user()->role === 'superadmin')
                        <option value="superadmin">Superadmin</option>
                    @endif
                </select>
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="input-field" required placeholder="••••••••">
            </div>
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 14px;">Crear Usuario</button>
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline" style="flex: 1;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 700; color: var(--ink);">Editar Usuario</h2>
            <button onclick="closeModal('editModal')" style="background:none; border:none; cursor:pointer; color: var(--muted);">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="input-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" id="edit_name" class="input-field" required>
            </div>
            <div class="input-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" id="edit_email" class="input-field" required>
            </div>
            <div class="input-group">
                <label>Rol de Usuario</label>
                <select name="role" id="edit_role" class="input-field" required>
                    <option value="usuario">Usuario Estándar</option>
                    <option value="admin">Administrador</option>
                    @if(auth()->user()->role === 'superadmin')
                        <option value="superadmin">Superadmin</option>
                    @endif
                </select>
            </div>
            <div class="input-group">
                <label>Cambiar Contraseña (opcional)</label>
                <input type="password" name="password" class="input-field" placeholder="Dejar en blanco para no cambiar">
            </div>
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 14px;">Guardar Cambios</button>
                <button type="button" onclick="closeModal('editModal')" class="btn btn-outline" style="flex: 1;">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(user) {
        document.getElementById('editForm').action = `/usuarios/${user.id}`;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('editModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    }
</script>
@endsection
