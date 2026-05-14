<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solicitudes de OC</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|dm-sans:400,500,600" rel="stylesheet" />

    <style>
        @include('oc.partials.common_styles')

        /* Especificos de esta vista */
        .sidebar.collapsed .nav-item:hover::after {
            content: attr(title);
            position: absolute;
            left: 100%;
            margin-left: 12px;
            padding: 8px 12px;
            background: rgba(16, 24, 40, 0.95);
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            pointer-events: none;
        }

        .content {
            flex: 1;
            padding: 24px 20px 48px;
        }

        .container {
            width: 100%;
            margin: 24px auto 60px;
            padding: 0 20px;
        }

        .card {
            background: var(--card);
            border: none; /* Quitamos el borde claro para un diseño más limpio */
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); /* Sombra más pronunciada tipo dashboard */
            overflow-x: hidden;
            overflow-y: visible;
        }

        .card[aria-label="Tabla de solicitudes"] {
            width: 100%;
            margin: 0 auto;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 26px 30px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 16px 16px 0 0;
            box-shadow: inset 0 -2px 0 0 #f1f5f9;
        }

        .toolbar-title {
            font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #2563eb !important; /* Azul rey vibrante para diferenciarse */
            letter-spacing: -0.02em;
        }

        .toolbar-subtitle {
            font-size: 14px;
            color: #64748b;
            margin-top: 6px;
        }

        .toolbar > div:first-child div:first-child {
            font-size: 24px !important;
            font-weight: 800 !important;
            color: #2563eb !important; /* Mismo azul vibrante */
            letter-spacing: -0.5px;
        }

        .toolbar > div:first-child div:last-child {
            font-size: 14px !important;
            color: #5b6473 !important;
            margin-top: 4px !important;
        }

        .toolbar-actions { align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pending-summary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #fff7ed, #fffbeb);
            border: 1px solid #fdba74;
            padding: 9px 14px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(251, 146, 60, 0.18);
        }

        .pending-summary-dot {
            height: 10px;
            width: 10px;
            background: #f59e0b;
            border-radius: 999px;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        .pending-summary-label {
            color: #9a3412;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.01em;
        }

        .pending-summary-count {
            min-width: 28px;
            text-align: center;
            background: #f59e0b;
            color: #ffffff;
            padding: 2px 9px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 13px;
            box-shadow: 0 3px 8px rgba(245, 158, 11, 0.35);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 18px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }


        .btn-ghost {
            background: #ffffff !important;
            color: #4f46e5 !important;
            border: 1.5px solid #c7d2fe !important;
            border-radius: 9px !important;
            box-shadow: 0 2px 5px rgba(79, 70, 229, 0.05) !important;
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
        .btn-ghost:hover {
            background: #f8fafc !important;
            border-color: #a5b4fc !important;
        }
        .btn-ghost:active {
            transform: translateY(1px) !important;
        }

        .btn-ghost:hover {
            background: #eff6ff !important;
            border-color: #3b82f6 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.1) !important;
            color: #1d4ed8 !important;
        }


        .btn-secondary {
            background: #eaf3ff;
            color: #0b5fa5;
            border-color: #cfe2ff;
        }

        .btn-outline {
            background: #ffffff;
            color: #0b5fa5;
            border-color: #0b5fa5;
        }

        .btn-accent {
            background: #0f7a3e;
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(15, 122, 62, 0.3);
        }

        .btn-accent:hover {
            background: #0b5b2e;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 122, 62, 0.4);
        }


        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .stat-info h3 {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-info p {
            margin: 4px 0 0 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--ink);
        }
        /* Search Field Enhancement */
        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-container i {
            position: absolute;
            left: 12px;
            color: #94a3b8;
        }
        .search-container input {
            padding-left: 36px;
            width: 100%;
        }

        .filters {
            display: grid;
            grid-template-columns: 1.5fr repeat(2, minmax(150px, 1fr));
            gap: 14px;
            padding: 18px 24px 20px;
            border-bottom: 1px solid rgba(227, 232, 240, 0.8);
            background: #f8fafc;
        }

        .filters label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        .input, .select {
            padding: 11px 13px;
            border: 1.5px solid #d9dfe8;
            border-radius: 9px;
            background: white;
            font-size: 14px;
            color: #1f2937;
            transition: all 150ms ease;
            font-weight: 500;
        }
        }

        .select {
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 35px;
        }

        .select option {
            white-space: normal;
            padding: 8px;
        }

        .input:focus, .select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(15, 107, 182, 0.1);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #475569;
            background: #f1f5f9;
            border-bottom: 2px solid #cbd5e1;
            padding: 12px 12px;
            font-weight: 700;
            position: sticky;
            top: 0;
            z-index: 1;
            white-space: nowrap;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody td {
            padding: 12px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            color: #1f2937;
            font-weight: 500;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #fcfdff;
        }

        tbody td.compact {
            width: 1%;
            white-space: nowrap;
            text-align: center;
        }

        tbody td.col-proveedor {
            width: auto;
        }

        tbody td.col-descripcion {
            width: auto;
        }

        tbody td.actions {
            text-align: right;
            white-space: nowrap;
            overflow: visible !important;
            padding-right: 12px;
            width: 1%;
        }

        tbody tr {
            position: relative;
        }

        tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
            z-index: 10;
            transition: background 120ms ease;
        }

        tbody tr.pending-row {
            background: linear-gradient(90deg, #fff7ed 0%, #ffffff 35%);
        }

        tbody tr.pending-row td:first-child {
            border-left: 4px solid #f59e0b;
            padding-left: 8px;
        }

        tbody tr.pending-row:hover {
            background: linear-gradient(90deg, #ffedd5 0%, #fff7ed 60%);
        }

        .chip {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e8f1fb 0%, #f0f7ff 100%);
            color: #0a4f86;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .chip-cliente {
            background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%);
            color: #5b21b6;
        }

        .chip-interna {
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
            color: #1d4ed8;
        }

        .chip-negocio {
            background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%);
            color: #166534;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--warning);
        }

        .status.ok { background: #dcfce7 !important; color: #15803d !important; }
        .status.ok .dot { background: #15803d !important; }
        
        .status.pending { background: #fef08a !important; color: #a16207 !important; }
        .status.pending .dot { background: #a16207 !important; }

        .status.danger { background: #fee2e2 !important; color: #b91c1c !important; }
        .status.danger .dot { background: #b91c1c !important; }
        
        .status.facturado { background: #e0f2fe !important; color: #0369a1 !important; }
        .status.facturado .dot { background: #0369a1 !important; }
        
        .status.enviada { background: #f3f4f6 !important; color: #4b5563 !important; }
        .status.enviada .dot { background: #4b5563 !important; }

        .col-proveedor,
        .col-descripcion {
            max-width: 260px;
        }

        .truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            white-space: normal;
            line-height: 1.35;
            max-height: calc(1.35em * 2);
        }

        .menu-trigger {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 20px;
            font-weight: bold;
            color: var(--muted);
            transition: background 150ms ease;
            position: relative;
            line-height: 1;
        }

        .menu-trigger:hover {
            background: #f0f4f8;
        }

        .dropdown-menu {
            display: none;
            position: fixed;
            background: white;
            border: 1.5px solid #e5e9f2;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(16, 24, 40, 0.20), 0 2px 8px rgba(16, 24, 40, 0.10);
            min-width: 180px;
            z-index: 999999;
            overflow: visible;
        }

        .dropdown-menu.show {
            display: block;
            animation: dropdownSlideIn 0.15s ease-out;
        }

        @keyframes dropdownSlideIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            display: block;
            width: 100%;
            padding: 11px 16px;
            border: none;
            background: transparent;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
            color: #1f2937;
            transition: all 150ms ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: #f7fbff;
            color: #0f6bb6;
        }

        .dropdown-item.primary {
            color: var(--brand);
            font-weight: 600;
        }

        .menu-container {
            position: relative;
            z-index: 100;
        }

        tbody tr:hover .menu-container {
            z-index: 1001;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px 16px;
            color: var(--muted);
            font-size: 13px;
        }

        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding: 12px 18px 18px;
            border-top: 1px solid #eef2f7;
            background: #fcfdff;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .page-link {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dbe5f1;
            border-radius: 9px;
            background: #fff;
            color: #1f2937;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 140ms ease;
        }

        .page-link:hover {
            border-color: #0f6bb6;
            color: #0f6bb6;
            background: #f4f9ff;
        }

        .page-link.active {
            background: #0f6bb6;
            color: #fff;
            border-color: #0f6bb6;
        }

        .page-link.disabled {
            opacity: 0.45;
            pointer-events: none;
        }

        .empty {
            display: none;
            padding: 24px 18px 30px;
            color: var(--muted);
            text-align: center;
        }

        .alert {
            max-width: 1200px;
            margin: 20px auto 0;
            padding: 14px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
    
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .stat-info h3 {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-info p {
            margin: 4px 0 0 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--ink);
        }
        /* Search Field Enhancement */
        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-container i {
            position: absolute;
            left: 12px;
            color: #94a3b8;
        }
        .search-container input {
            padding-left: 36px;
            width: 100%;
        }

        .filters {
                grid-template-columns: 1fr;
            }

            .toolbar {
                align-items: flex-start;
            }
            
            tbody td {
                max-width: 150px;
            }
        }
        
        /* Modal Styles Mejorados */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            animation: fadeIn 0.3s ease-out;
        }
        .modal-overlay.show { display: flex; }
        .modal-container {
            background: #ffffff; border-radius: 20px; width: 100%; max-width: 600px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid rgba(0,0,0,0.05);
        }
        .modal-header {
            padding: 24px 32px; border-bottom: 1px solid #f1f5f9; display: flex;
            align-items: center; justify-content: space-between; background: #ffffff;
        }
        .modal-title { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;}
        .modal-close {
            background: #f8fafc; border: none; font-size: 24px; cursor: pointer; color: #64748b;
            display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;
            border-radius: 50%; transition: all 0.2s;
        }
        .modal-close:hover { background: #e2e8f0; color: #0f172a; transform: rotate(90deg); }
        .modal-body { padding: 32px; max-height: 70vh; overflow-y: auto; background: #fcfcfd; }
        
        .detail-section {
            background: #ffffff; border-radius: 12px; padding: 20px; margin-bottom: 20px;
            border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        
        .detail-section-title {
            font-size: 13px; font-weight: 700; text-transform: uppercase; color: #3b82f6;
            letter-spacing: 0.05em; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
            border-bottom: 2px solid #eff6ff; padding-bottom: 8px;
        }

        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .detail-item { display: flex; flex-direction: column; gap: 6px; }
        .detail-item.full-width { grid-column: 1 / -1; }
        
        .detail-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
        .detail-value { font-size: 15px; color: #0f172a; font-weight: 500; }
        
        .monto-badge {
            display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 6px 12px;
            border-radius: 8px; font-weight: 800; font-size: 18px; border: 1px solid #bfdbfe;
        }
        
        .obs-box {
            background: #fdfbed; border: 1px solid #fcebb6; color: #92720e;
            padding: 16px; border-radius: 12px; font-size: 14px; line-height: 1.6;
        }
        .desc-box {
            background: #f8fafc; border: 1px solid #e2e8f0; color: #334155;
            padding: 16px; border-radius: 12px; font-size: 14px; line-height: 1.6;
        }

        .modal-footer {
            padding: 20px 32px; background: #ffffff; border-top: 1px solid #f1f5f9;
            display: flex; justify-content: flex-end; gap: 12px;
        }
        .btn-modern {
            padding: 10px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-close { background: #f1f5f9; color: #475569; }
        .btn-close:hover { background: #e2e8f0; color: #0f172a; }
        .btn-download { background: #0f6bb6; color: white; box-shadow: 0 4px 6px rgba(15, 107, 182, 0.2); text-decoration: none;}
        .btn-download:hover { background: #0d5a9a; box-shadow: 0 6px 8px rgba(15, 107, 182, 0.3); transform: translateY(-1px); }
@keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

<style>
    .btn-action {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        white-space: nowrap;
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-action:active {
        transform: translateY(1px);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-action-view {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        box-shadow: none;
    }
    .btn-action-view:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
    }

    .btn-action-accept {
        background: #10b981;
        color: white;
        border: 1px solid #10b981;
    }
    .btn-action-accept:hover {
        background: #059669;
        border-color: #059669;
    }

    .btn-action-reject {
        background: #ef4444;
        color: white;
        border: 1px solid #ef4444;
    }
    .btn-action-reject:hover {
        background: #dc2626;
        border-color: #dc2626;
    }

    .btn-action-invoice {
        background: #f59e0b;
        color: white;
        border: 1px solid #f59e0b;
    }
    .btn-action-invoice:hover {
        background: #d97706;
        border-color: #d97706;
    }

    /* Modal Swal Modernizado */
    .swal-modern-popup {
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        padding: 24px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .swal-modern-title {
        color: #1e293b;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0px !important;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    .swal-modern-input.swal2-textarea {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 16px;
        font-size: 14px;
        color: #334155;
        transition: all 0.3s ease;
        resize: vertical;
        min-height: 120px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) inset;
    }
    .swal-modern-input.swal2-textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        outline: none;
    }
    .swal-modern-confirm, .swal-modern-cancel {
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        letter-spacing: 0.3px;
        transition: all 0.2s ease !important;
    }
    .swal-modern-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);
    }
    .swal-modern-cancel:hover {
        transform: translateY(-2px);
        background-color: #64748b !important;
    }

    .swal2-container { z-index: 10000 !important; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'proveedores'])

        <!-- Main Content -->
        <div class="main-content">
            <x-page-header 
                title="" 
                subtitle=""
                :backRoute="null"
                :showLogout="true"
            />

            @if(session('success'))
                <div class="alert alert-success">
                    <span style="font-size: 18px;">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-info" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;">
                    <span style="font-size: 18px;">⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            
            
            @php
                $totalPendientes = \Illuminate\Support\Facades\DB::table('oc_solicitudes')->where('estado', 'Solicitada')->count();
            @endphp
            
<style>
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.4);
    }
    70% {
        box-shadow: 0 0 0 4px rgba(234, 179, 8, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(234, 179, 8, 0);
    }
}
</style>
<main class="content">

                    <!-- Content Wrapper -->
                    <section class="card">
                        <div class="toolbar">
                            <div>
                                <div class="toolbar-title" style="display:flex; align-items:center; gap:8px;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand); margin-right: 4px;">
                                        <rect x="1" y="3" width="15" height="13"></rect>
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                    </svg>
                                    <span style="font-size: 20px; color: #0f172a !important;">Gestor de Aprobaciones</span>
                                </div>
                                <div class="toolbar-subtitle">Listado de Proveedores registrados en el sistema.</div>
                            </div>
                            <div class="toolbar-actions" style="display: flex; align-items: center; gap: 16px;">
                                <div class="search-box" style="margin: 0;">
                                    <form id="filterForm" method="GET" action="{{ route('proveedores.index') }}" style="display:flex; gap:8px; align-items:center; flex-wrap: wrap; justify-content: flex-end;">
                                        <input type="hidden" name="sidebar" id="sidebar_state_input" value="{{ request('sidebar', 'expanded') }}">
                                        <input type="text" name="search" class="search-input" placeholder="Buscar..." value="{{ request('search') }}" style="padding: 8px 12px; border: 1px solid var(--line); border-radius: 6px; outline: none; width: 200px;">
                                        <select name="sort" style="padding: 8px 12px; border: 1px solid var(--line); border-radius: 6px; outline: none; background:#fff;">
                                            <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>Mas reciente</option>
                                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Mas antigua</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary" style="padding: 8px 12px;">Filtrar</button>
                                        @if(request('search') || request('sort'))
                                            <a href="{{ route('proveedores.index') }}" class="btn" style="padding: 8px 12px; background: #e2e8f0; border-radius:6px; text-decoration:none; color: var(--ink);">Limpiar</a>
                                        @endif
                                        <a href="{{ route('proveedores.download', request()->all()) }}" class="btn btn-ghost" style="padding: 10px; border: 1px solid var(--line); border-radius: 8px; color: var(--muted); background: white;" title="Descargar Excel/CSV">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                        </a>
                                    </form>
                                </div>
                                <button type="button" onclick="openModal()" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span>Nuevo Proveedor</span>
                                </button>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>RUT</th>
                                        <th>Nombre</th>
                                        <th>Razón Social</th>
                                        <th>Banco</th>
                                        <th>Cuenta</th>
                                        <th>Correo</th>
                                        <th>Fecha</th>
                                        <th style="width: 100px; text-align: right;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $row)
                                    <tr>
                                        <td style="font-weight: 500; color: var(--muted);">#{{ $row->id }}</td>
                                        <td><span class="status-chip">{{ $row->rut }}</span></td>
                                        <td>
                                            <div style="font-weight: 600;">{{ $row->nombre }}</div>
                                            <div style="font-size: 11px; color: var(--muted);">{{ $row->acreedor }}</div>
                                        </td>
                                        <td>
                                            <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $row->razon_social }}">{{ $row->razon_social }}</div>
                                            <div style="font-size: 11px; color: var(--muted);">{{ $row->direccion }}</div>
                                        </td>
                                        <td>{{ $row->banco }}</td>
                                        <td>{{ $row->numero_cuenta }}</td>
                                        <td><a href="mailto:{{ $row->correo }}" style="color: var(--brand); text-decoration: none;">{{ $row->correo }}</a></td>
                                        <td>{{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') : '-' }}</td>
                                        <td style="text-align: right;">
                                            <button onclick="editModal({{ json_encode($row) }})" class="btn btn-ghost" style="padding: 6px; min-width: auto; color: var(--brand);">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="confirmDelete(this)" 
                                                data-url="{{ route('proveedores.destroy', $row->id) }}" 
                                                data-name="{{ $row->nombre }}"
                                                class="btn btn-ghost" style="padding: 6px; min-width: auto; color: #dc2626;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if(count($rows) === 0)
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 40px; color: var(--muted);">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            </svg>
                                            <div>No se encontraron proveedores</div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination-container">
                             {{ $rows->links('pagination::bootstrap-4') }}
                        </div>
                    </section>

                    <!-- Modals styled minimally like gestor's modal-overlay -->
                    <div class="modal-overlay" id="proveedorModal" style="display: none;">
                        <div class="modal-container" style="max-width: 600px;">
                            <div class="modal-header">
                                <h3 class="modal-title" style="font-size: 16px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--brand); margin-right: 8px;">
                                        <path d="M1 3h15v13H1z"></path>
                                        <path d="M16 8h4l3 3v5h-7z"></path>
                                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                    </svg>
                                    <span id="modal-title-text">Nuevo Proveedor</span>
                                </h3>
                                <button type="button" class="modal-close" onclick="closeModal()">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body" style="padding: 20px;">
                                <form id="proveedorForm" method="POST" action="{{ route('proveedores.store') }}">
                                    @csrf
                                    <input type="hidden" name="_method" id="formMethod" value="POST">
                                    
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">RUT *</label>
                                            <input type="text" name="rut" id="rut" required class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Nombre *</label>
                                            <input type="text" name="nombre" id="nombre" required class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 2;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Razón Social *</label>
                                            <input type="text" name="razon_social" id="razon_social" required class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Acreedor</label>
                                            <input type="text" name="acreedor" id="acreedor" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Correo Electrónico</label>
                                            <input type="email" name="correo" id="correo" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 2;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Dirección</label>
                                            <input type="text" name="direccion" id="direccion" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Comuna</label>
                                            <input type="text" name="comuna" id="comuna" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Banco</label>
                                            <input type="text" name="banco" id="banco" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Tipo de Cuenta</label>
                                            <select name="tipo_cuenta" id="tipo_cuenta" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px; background: white; cursor: pointer;">
                                                <option value="">Seleccionar...</option>
                                                <option value="Cuenta Corriente">Cuenta Corriente</option>
                                                <option value="Cuenta Vista">Cuenta Vista</option>
                                                <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
                                                <option value="Cuenta RUT">Cuenta RUT</option>
                                            </select>
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Número de Cuenta</label>
                                            <input type="text" name="numero_cuenta" id="numero_cuenta" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">Nombre Titular</label>
                                            <input type="text" name="nombre_titular" id="nombre_titular" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                        <div style="grid-column: span 1;">
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; color: var(--muted);">RUT Titular</label>
                                            <input type="text" name="rut_titular" id="rut_titular" class="search-input" style="width: 100%; border: 1px solid var(--line); border-radius: 6px; padding: 8px 12px;">
                                        </div>
                                    </div>
                                    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--line); display: flex; justify-content: flex-end; gap: 12px;">
                                        <button type="button" onclick="closeModal()" class="btn" style="background:#fff; border:1px solid var(--line); color: var(--ink);">Cancelar</button>
                                        <button type="submit" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:6px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                                <polyline points="7 3 7 8 15 8"></polyline>
                                            </svg>
                                            <span>Guardar</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

</main>
</div>
</div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const input = document.getElementById('sidebar_state_input');
            sidebar.classList.toggle('collapsed');
            
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            if (input) input.value = isCollapsed ? 'collapsed' : 'expanded';
        }

        // Aplicar estado inicial
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const input = document.getElementById('sidebar_state_input');
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                if (input) input.value = 'collapsed';
            } else {
                sidebar.classList.remove('collapsed');
                if (input) input.value = 'expanded';
            }
        });

        function openModal() {
            document.getElementById('modal-title-text').innerText = 'Nuevo Proveedor';
            document.getElementById('proveedorForm').reset();
            document.getElementById('proveedorForm').action = "{{ route('proveedores.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('proveedorModal').style.display = 'flex';
        }

        function editModal(row) {
            document.getElementById('modal-title-text').innerText = 'Editar Proveedor';
            document.getElementById('proveedorForm').action = `/proveedores/${row.id}`;
            document.getElementById('formMethod').value = 'PUT';
            
            // Llenar campos
            document.getElementById('rut').value = row.rut || '';
            document.getElementById('nombre').value = row.nombre || '';
            document.getElementById('razon_social').value = row.razon_social || '';
            document.getElementById('acreedor').value = row.acreedor || '';
            document.getElementById('correo').value = row.correo || '';
            document.getElementById('direccion').value = row.direccion || '';
            document.getElementById('comuna').value = row.comuna || '';
            document.getElementById('banco').value = row.banco || '';
            document.getElementById('tipo_cuenta').value = row.tipo_cuenta || '';
            document.getElementById('numero_cuenta').value = row.numero_cuenta || '';
            document.getElementById('nombre_titular').value = row.nombre_titular || '';
            document.getElementById('rut_titular').value = row.rut_titular || '';
            
            document.getElementById('proveedorModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('proveedorModal').style.display = 'none';
        }

        function confirmDelete(button) {
            const url = button.getAttribute('data-url');
            const name = button.getAttribute('data-name');
            Swal.fire({
                title: '¿Estás seguro?',
                html: `Estás a punto de eliminar al proveedor:<br><strong>${name}</strong><br><br><small style="color: #64748b;">Esta acción no se puede deshacer.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f6bb6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    
                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('proveedorModal');
            if (!modal) return;

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        });
    </script>
    </body>
    </html>
    