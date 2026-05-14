<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Usuarios</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('oc.partials.common_scripts')

    <style>
        @include('oc.partials.common_styles')

        .content {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .btn-edit {
            background: #eff6ff;
            color: #3b82f6;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            border: 1px solid #dbeafe;
        }

        .btn-edit:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .btn-delete {
            background: #fff1f2;
            color: #f43f5e;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            border: 1px solid #ffe4e6;
            padding: 0;
        }

        .btn-delete:hover {
            background: #f43f5e;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.2);
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }

        .card-header {
            padding: 24px 32px;
            background: #f8fafc;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .card-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            padding: 12px 16px 12px 42px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            width: 300px;
            transition: all 0.3s ease;
            background: white;
            color: #1e293b;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1);
            width: 350px;
        }

        .table-container {
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            padding: 16px 32px;
            background: #f8fafc;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 20px 32px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #475569;
            vertical-align: middle;
        }

        tr:hover td {
            background: #fbfcfe;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 15px;
        }

        .user-email {
            font-size: 13px;
            color: #94a3b8;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-super-admin { background: #fffbeb; color: #92400e; border: 1px solid #fef3c7; }
        .badge-admin { background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe; }
        .badge-gestor { background: #ecfdf5; color: #065f46; border: 1px solid #d1fae5; }
        .badge-cliente { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-new-user {
            background: var(--brand);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(15, 107, 182, 0.2);
        }

        .btn-new-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 107, 182, 0.3);
            background: #0d5ca0;
        }
    </style>
</head>
<body>
    @php
        $roleLabels = [
            'super_admin' => 'Super Admin',
            'admin' => 'Administrador',
            'gestor' => 'Gestor',
            'cliente' => 'Cliente',
        ];
    @endphp

    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'users'])

        <div class="main-content">
            <x-page-header title="" subtitle="" :showLogout="true" />

            <div class="content">
                @if(session('success'))
                    <script>showAlert('success', "{{ session('success') }}");</script>
                @endif

                @if(session('error'))
                    <script>showAlert('error', "{{ session('error') }}");</script>
                @endif


            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title" style="font-size: 20px;">Gestión de Usuarios</div>
                        <div class="card-subtitle">Roles y permisos del sistema</div>
                    </div>
                    @if($users->count() > 0)
                    <div style="position: relative; margin-left: 16px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input id="userSearchInput" type="text" placeholder="Buscar usuario..." style="padding: 10px 16px 10px 38px; border-radius: 8px; border: 1.5px solid #e3e8f0; font-size: 14px; min-width: 280px; width: 100%; max-width: 350px; outline: none; transition: border-color 0.2s; background: white;" onfocus="this.style.borderColor='#0f6bb6'; this.style.boxShadow='0 0 0 3px rgba(15,107,182,0.1)';" onblur="this.style.borderColor='#e3e8f0'; this.style.boxShadow='none';">
                    </div>
                    @endif
                </div>

                @if($users->count() > 0)
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const input = document.getElementById('userSearchInput');
                            if (!input) return;
                            const rows = document.querySelectorAll('table tbody tr');
                            input.addEventListener('input', function() {
                                const value = input.value.toLowerCase();
                                rows.forEach(row => {
                                    const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                                    const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                                    if (name.includes(value) || email.includes(value)) {
                                        row.style.display = '';
                                    } else {
                                        row.style.display = 'none';
                                    }
                                });
                            });
                        });
                    </script>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <span class="user-name">{{ $user->name }}</span>
                                                <span class="user-email">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge badge-{{ str_replace('_', '-', $user->role) }}">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                {{ $roleLabels[$user->role] ?? $user->role }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                @php
                                                    $currentUser = auth()->user();
                                                    $canEdit = false;
                                                    $canDelete = false;

                                                    if ($currentUser->role === 'super_admin') {
                                                        $canEdit = $user->role !== 'super_admin' && $user->id !== $currentUser->id;
                                                        $canDelete = $user->role !== 'super_admin' && $user->id !== $currentUser->id;
                                                    }
                                                    else if ($currentUser->role === 'admin') {
                                                        $canEdit = in_array($user->role, ['gestor', 'cliente']) && $user->id !== $currentUser->id;
                                                        $canDelete = in_array($user->role, ['gestor', 'cliente']) && $user->id !== $currentUser->id;
                                                    }
                                                    else if ($currentUser->role === 'gestor') {
                                                        $canEdit = false;
                                                        $canDelete = $user->role === 'cliente' && $user->id !== $currentUser->id;
                                                    }
                                                @endphp

                                                @if($canEdit)
                                                    <a href="{{ route('users.edit', $user) }}" class="btn-edit" title="Editar Usuario">
                                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </a>
                                                @endif

                                                @if($canDelete)
                                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn-delete" title="Eliminar Usuario" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!$canEdit && !$canDelete)
                                                    <span class="no-permissions">Sin permisos</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p>No hay usuarios para mostrar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        // Restaurar estado del sidebar
        document.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('sidebar').classList.add('collapsed');
            }
        });

        function confirmDelete(userId, userName, userEmail) {
            Swal.fire({
                title: 'Eliminar Usuario',
                icon: 'warning',
                iconColor: '#dc2626',
                html: `<div style="text-align: left; padding: 0 10px;">
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 16px;">¿Estás seguro de que deseas eliminar este usuario?</p>
                    <div style="background: #f8fafc; border-left: 3px solid #dc2626; padding: 12px; border-radius: 6px; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <strong style="color: #1e293b; font-size: 14px;">${userName}</strong>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span style="color: #64748b; font-size: 13px;">${userEmail}</span>
                        </div>
                    </div>
                    <p style="color: #ef4444; font-size: 13px; font-weight: 600; margin: 0;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:4px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        Esta acción no se puede deshacer
                    </p>
                </div>`,
                showCancelButton: true,
                confirmButtonText: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:6px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Sí, Eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#94a3b8',
                buttonsStyling: true,
                reverseButtons: true,
                customClass: {
                    popup: 'swal-modern-popup',
                    title: 'swal-modern-title',
                    htmlContainer: 'swal-modern-html',
                    confirmButton: 'swal-modern-confirm',
                    cancelButton: 'swal-modern-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${userId}`).submit();
                }
            });
        }
    </script>
</body>
</html>