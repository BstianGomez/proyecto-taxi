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
            padding: 16px 10px 30px;
        }

        .container {
            width: 100%;
            margin: 24px 0 60px;
            padding: 0;
        }

        .card {
            background: var(--card);
            border: 1px solid #e5e9f2;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(16, 24, 40, 0.10), 0 2px 8px rgba(16, 24, 40, 0.05);
            overflow-x: hidden;
            overflow-y: visible;
            width: 100%;
        }

        .card[aria-label="Tabla de solicitudes"] {
            width: 100%;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 22px 24px;
            border-bottom: 1px solid rgba(227, 232, 240, 0.8);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .toolbar-title {
            font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .toolbar-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }

        .toolbar-actions {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
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

        .filters {
            display: grid;
            grid-template-columns: 1.5fr repeat(2, minmax(150px, 1fr));
            gap: 14px;
            padding: 18px 24px 20px;
            border-bottom: 1px solid rgba(227, 232, 240, 0.8);
            background: #fcfdfe;
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
            scrollbar-width: thin;
        }

        table {
            width: 100%;
            
            border-collapse: collapse;
            font-size: 11px;
        }

        thead th {
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5b6473;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f4f8 100%);
            border-bottom: 2px solid #e3e8f0;
            padding: 14px 16px;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1;
            backdrop-filter: blur(3px);
        }

        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f3f8;
            font-size: 13px;
            color: #1f2937;
            font-weight: 500;
            white-space: normal;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) { background: #fcfdff; }
        tbody td.compact { width: auto; }
        tbody td.actions { width: 1%; text-align: center; white-space: nowrap; }
        tbody tr:hover {
            background: rgba(15, 107, 182, 0.04);
            z-index: 10;
            transition: background 120ms ease;
        }

        td {
            vertical-align: middle !important;
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

        .chip-cliente { background: linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%); color: #5b21b6; }
        .chip-interna { background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); color: #1d4ed8; }
        .chip-negocio { background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); color: #166534; }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .dot { width: 8px; height: 8px; border-radius: 999px; background: var(--warning); }

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

        .col-proveedor, .col-descripcion { max-width: 200px; }

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
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            color: #64748b;
            margin: 0 auto;
            outline: none !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .menu-trigger:hover {
            background: #f8fafc;
            border-color: var(--brand);
            color: var(--brand);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(15, 107, 182, 0.1);
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
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
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

        .dropdown-item:hover { background: #f7fbff; color: #0f6bb6; }
        .dropdown-item.primary { color: var(--brand); font-weight: 600; }

        .menu-container { position: relative; z-index: 100; }
        tbody tr:hover .menu-container { z-index: 1001; }

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

        .pagination-info { font-size: 13px; color: var(--muted); font-weight: 500; }

        .pagination { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

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

        .page-link:hover { border-color: #0f6bb6; color: #0f6bb6; background: #f4f9ff; }
        .page-link.active { background: #0f6bb6; color: #fff; border-color: #0f6bb6; }
        .page-link.disabled { opacity: 0.45; pointer-events: none; }

        .empty { display: none; padding: 24px 18px 30px; color: var(--muted); text-align: center; }

        .alert {
            width: 100%;
            margin: 0 0 20px 0;
            padding: 14px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 900px) {
            .filters { grid-template-columns: 1fr; }
            .toolbar { align-items: flex-start; }
            tbody td { max-width: 150px; }
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.62); backdrop-filter: blur(9px);
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
        .modal-footer {
            padding: 20px 32px; background: #ffffff; border-top: 1px solid #f1f5f9;
            display: flex; justify-content: flex-end; gap: 12px;
        }
        .btn-modern { padding: 10px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
        .btn-close { background: #f1f5f9; color: #475569; }
        .btn-close:hover { background: #e2e8f0; color: #0f172a; }
        .btn-download { background: #0f6bb6; color: white; box-shadow: 0 4px 6px rgba(15, 107, 182, 0.2); text-decoration: none;}
        .btn-download:hover { background: #0d5a9a; box-shadow: 0 6px 8px rgba(15, 107, 182, 0.3); transform: translateY(-1px); }

        #ocDetailModal .modal-container {
            max-width: 1040px;
            border-radius: 24px;
            border: 1px solid #d9e2ef;
            overflow: hidden;
        }

        #ocDetailModal .modal-header {
            padding: 18px 24px;
            background: linear-gradient(135deg, #f7f9fd 0%, #eef4ff 100%);
        }

        .rendicion-header {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .rendicion-header-top {
            font-size: 12px;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: #4f5d73;
            text-transform: uppercase;
        }

        .rendicion-title {
            margin: 0;
            font-size: 34px;
            line-height: 1;
            letter-spacing: -0.02em;
            color: #0f172a;
            font-weight: 700;
        }

        .rendicion-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.65fr) minmax(280px, 1fr);
            gap: 16px;
        }

        .rendicion-main,
        .rendicion-aside {
            display: flex;
            flex-direction: column;
            gap: 14px;
            min-width: 0;
        }

        .rendicion-card {
            background: #ffffff;
            border: 1px solid #dbe5f2;
            border-radius: 18px;
            box-shadow: 0 14px 22px rgba(17, 24, 39, 0.06);
            padding: 16px;
        }

        .rendicion-owner {
            display: flex;
            gap: 14px;
            align-items: center;
            justify-content: space-between;
        }

        .owner-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .owner-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(160deg, #d3dae6 0%, #b8c6db 100%);
            color: #213047;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .owner-name {
            font-size: 28px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
            color: #0f172a;
        }

        .owner-meta {
            font-size: 14px;
            color: #5e6c82;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            background: #f9edd1;
            color: #856503;
            border: 1px solid #edd49f;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            padding: 5px 10px;
        }

        .action-outline {
            border: 1px solid #c8d4e4;
            color: #1f2d45;
            background: #fff;
            border-radius: 12px;
            height: 40px;
            padding: 0 16px;
            font-weight: 700;
            letter-spacing: 0.03em;
            font-size: 12px;
            text-transform: uppercase;
            cursor: default;
        }

        .section-title {
            margin: 0 0 12px;
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .voucher-item {
            border: 1px solid #dbe6f6;
            border-radius: 16px;
            overflow: hidden;
            background: #f9fbff;
        }

        .voucher-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 14px;
            border-bottom: 1px solid #dbe6f6;
        }

        .voucher-main {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            min-width: 0;
        }

        .voucher-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #e7eef8;
            color: #3368a0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .voucher-name {
            font-size: 28px;
            line-height: 1;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
            word-break: break-word;
        }

        .voucher-sub {
            font-size: 14px;
            color: #4f5d73;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .voucher-tag {
            background: #d9e8f9;
            border: 1px solid #b9d3f1;
            color: #235e95;
            border-radius: 8px;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .voucher-amount {
            text-align: right;
            color: #0f172a;
            white-space: nowrap;
        }

        .voucher-amount .date {
            font-size: 13px;
            color: #5e6c82;
        }

        .voucher-amount .money {
            margin-top: 4px;
            display: block;
            font-size: 35px;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .voucher-preview {
            padding: 14px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
        }

        .desc-box,
        .obs-box {
            border-radius: 12px;
            padding: 12px;
            font-size: 14px;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .desc-box {
            background: #f5f7fb;
            border-color: #dfe7f3;
            color: #334155;
        }

        .obs-box {
            background: #fcf7ea;
            border-color: #f2dfac;
            color: #8a6d1d;
            margin-top: 10px;
        }

        .total-box {
            margin-top: 12px;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            background: linear-gradient(180deg, #f0f4fb 0%, #e7edf7 100%);
            padding: 14px;
            text-align: center;
        }

        .total-label {
            font-size: 13px;
            font-weight: 700;
            color: #1f2d45;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .total-sub {
            margin-top: 5px;
            color: #4f5d73;
            font-size: 14px;
        }

        .side-card-title {
            margin: 0 0 12px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #0f172a;
        }

        .ghost-action {
            width: 100%;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #c8d4e4;
            background: #fff;
            color: #1f2d45;
            font-weight: 600;
            font-size: 14px;
            margin-top: 8px;
            cursor: default;
        }

        .ghost-action.primary {
            border-color: #b69950;
            color: #8a6d1d;
            background: #f9f2df;
        }

        .transfer-name {
            font-size: 28px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin-bottom: 12px;
            word-break: break-word;
        }

        .transfer-list {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .transfer-item {
            display: grid;
            grid-template-columns: 76px minmax(0, 1fr);
            gap: 8px;
            font-size: 14px;
            color: #334155;
            word-break: break-word;
        }

        .transfer-item strong {
            color: #0f172a;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.04em;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .meta-pill {
            border: 1px solid #dbe5f2;
            border-radius: 12px;
            padding: 10px 12px;
            background: #f8fbff;
        }

        .meta-pill .k {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            display: block;
        }

        .meta-pill .v {
            margin-top: 4px;
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            word-break: break-word;
        }

        #ocDetailModal .modal-body {
            max-height: calc(100vh - 220px);
            padding: 16px;
            background: linear-gradient(170deg, #f2f6fd 0%, #e6edf8 100%);
        }

        .detail-cta {
            min-width: 200px;
            justify-content: center;
            border-radius: 10px;
            font-weight: 700;
        }

        .detail-cta.muted {
            background: #e2e8f0;
            color: #64748b;
            box-shadow: none;
            cursor: default;
        }

        @media (max-width: 980px) {
            .rendicion-layout {
                grid-template-columns: 1fr;
            }

            .voucher-preview {
                grid-template-columns: 1fr;
            }

            .voucher-amount .money,
            .owner-name,
            .voucher-name,
            .transfer-name,
            .rendicion-title {
                font-size: 24px;
            }

            #ocDetailModal .modal-header {
                padding: 14px 16px;
            }
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .swal2-container { z-index: 10000 !important; }
    </style>

</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'index'])

        <!-- Main Content -->
        <div class="main-content">
            <x-page-header 
                title="" 
                subtitle=""
                :backRoute="null"
                :showLogout="true"
            />

            @if(session('success'))
                <script>showAlert('success', "{{ session('success') }}");</script>
            @endif

            @if(session('error'))
                <script>showAlert('error', "{{ session('error') }}");</script>
            @endif

            <main class="content">
                <section class="card" aria-label="Tabla de solicitudes">
                <div class="toolbar">
                    <div>
                        <div class="toolbar-title" style="display:flex; align-items:center; gap:12px;">
                            <div style="background: var(--bg-accent); padding: 8px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--brand);">
                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                </svg>
                            </div>
                            <span style="font-size: 22px; font-weight: 800; color: #1e293b; letter-spacing: -0.02em;">Solicitudes de OC</span>
                        </div>
                        <div class="toolbar-subtitle" style="margin-top: 4px; margin-left: 50px;">Gestión y seguimiento de órdenes de compra en tiempo real</div>
                    </div>
                    <div class="toolbar-actions">
                        <a href="{{ route('oc.export') }}" class="btn btn-ghost" style="padding: 10px 20px; font-weight: 700;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Exportar Datos
                        </a>
                    </div>
                </div>

                <div class="filters" style="background: #fcfdfe; border-bottom: 1px solid #edf2f7; padding: 16px 20px; display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;">
                    <div class="field" style="flex: 2; min-width: 300px;">
                        <label for="search" style="font-weight: 700; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; display: block;">Buscar Solicitud</label>
                        <div style="position: relative;">
                            <input id="search" class="input" type="search" placeholder="Buscar por proveedor, descripción o RUT..." style="padding-left: 44px !important; border-radius: 12px; border-color: #e2e8f0; height: 46px; width: 100%;" />
                            <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <div class="field" style="flex: 1; min-width: 180px;">
                        <label for="filterTipo" style="font-weight: 700; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; display: block;">Tipo de Orden</label>
                        <select id="filterTipo" class="select" style="border-radius: 12px; border-color: #e2e8f0; height: 46px; width: 100%;">
                            <option value="">Todos los tipos</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Interna">Interna</option>
                            <option value="Negocio">Negocio</option>
                        </select>
                    </div>
                    <div class="field" style="flex: 1; min-width: 180px;">
                        <label for="filterEstado" style="font-weight: 700; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; display: block;">Estado Actual</label>
                        <select id="filterEstado" class="select" style="border-radius: 12px; border-color: #e2e8f0; height: 46px; width: 100%;">
                            <option value="">Todos los estados</option>
                            <option value="Solicitada">Solicitada</option>
                            <option value="Aceptada">Aceptada</option>
                            <option value="Rechazada">Rechazada</option>
                            <option value="Facturado">Facturado</option>
                        </select>
                    </div>
                    <div style="display: flex; align-items: flex-end; padding-bottom: 0;">
                         <button class="btn btn-primary" id="btnFilter" style="height: 46px; padding: 0 28px; border-radius: 12px; font-weight: 700; letter-spacing: 0.5px;">
                            Filtrar
                         </button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>CECO</th>
                                <th>Tipo</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Proveedor</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                                <th><span style="visibility: hidden;">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody id="ocRows">
                            @foreach ($rows as $index => $row)
                                @php
                                    $ceco = data_get($row, 'ceco');
                                    $cecoNumber = preg_match('/\d+/', (string) $ceco, $cecoMatch) ? $cecoMatch[0] : $ceco;
                                    $tipo = data_get($row, 'tipo_solicitud');
                                    $documento = data_get($row, 'tipo_documento');
                                    $estado = data_get($row, 'estado');
                                    $rut = data_get($row, 'rut');
                                    $proveedor = data_get($row, 'proveedor');
                                    $descripcion = data_get($row, 'descripcion');
                                    $cantidad = data_get($row, 'cantidad');
                                    $monto = data_get($row, 'monto');
                                    $numeroOc = 'OC-' . date('Y', $row->created_at ? strtotime($row->created_at) : time()) . '-' . str_pad($row->id, 3, '0', STR_PAD_LEFT);
                                    $estadoClass = match (strtolower((string) $estado)) {
                                        'facturado' => 'facturado',
                                        'aprobada', 'aceptada', 'entregada' => 'ok',
                                        'enviada' => 'enviada',
                                        'rechazada' => 'danger',
                                        default => 'pending',
                                    };
                                    $tipoClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string) $tipo));
                                @endphp
                                    <tr data-text="{{ strtolower(($ceco ?? '').' '.($tipo ?? '').' '.($documento ?? '').' '.($estado ?? '').' '.($rut ?? '').' '.($proveedor ?? '').' '.($descripcion ?? '')) }}"
                                        data-tipo="{{ $tipo }}"
                                        data-estado="{{ $estado }}"
                                        data-index="{{ $index }}"
                                        @php
                                            $datosExtraIdx = json_decode($row->datos_extra, true) ?? [];
                                            $emailProvIdx = data_get($row, 'email_proveedor') ?: data_get($datosExtraIdx, 'email', '');
                                            $numeroOcIdx = 'OC-' . date('Y', $row->created_at ? strtotime($row->created_at) : time()) . '-' . str_pad($row->id, 3, '0', STR_PAD_LEFT);
                                        @endphp
                                        data-id="{{ $row->id }}"
                                        data-numero-oc="{{ $numeroOcIdx }}"
                                        data-ceco="{{ $ceco }}"
                                        data-tipo-solicitud="{{ $tipo }}"
                                        data-tipo-documento="{{ $documento }}"
                                        data-proveedor="{{ $proveedor }}"
                                        data-email-proveedor="{{ $emailProvIdx }}"
                                        data-rut="{{ $rut }}"
                                        data-descripcion="{{ $descripcion }}"
                                        data-cantidad="{{ $cantidad }}"
                                        data-monto="{{ $monto }}"
                                        data-observacion="{{ data_get($datosExtraIdx, 'observacion', data_get($datosExtraIdx, 'observacion2', 'No especificada')) }}"
                                        data-ruta-adjunto="{{ data_get($datosExtraIdx, 'ruta_adjunto', '') }}"
                                        data-manager-comment="{{ $row->manager_comment ?? '' }}"
                                        data-sent-file-path="{{ $row->sent_file_path ?? '' }}"
                                        data-fecha="{{ data_get($row, 'created_at') ? date('d/m/Y H:i', strtotime($row->created_at)) : 'N/A' }}">
                                        <td class="compact" data-label="CECO">{{ $cecoNumber }}</td>
                                    <td data-label="Tipo"><span class="chip chip-{{ $tipoClass }}">{{ $tipo }}</span></td>
                                    <td data-label="Documento">{{ $documento }}</td>
                                    <td data-label="Estado"><span class="status {{ $estadoClass }}">
                                            <span class="dot"></span>
                                            {{ $estado }}
                                        </span>
                                    </td>
                                    <td class="col-proveedor" title="{{ $proveedor }} - {{ $rut }}" data-label="Proveedor"><span class="truncate-2">{{ $proveedor }}</span></td>
                                    <td class="col-descripcion" title="{{ $descripcion }}" data-label="Descripción"><span class="truncate-2">{{ $descripcion }}</span></td>
                                    <td class="compact" data-label="Cantidad">{{ $cantidad }}</td>
                                    <td class="compact" data-label="Monto">${{ number_format((float) $monto, 0, ',', '.') }}</td>
                                    <td class="actions" style="vertical-align: middle !important; text-align: center; width: 60px;">
                                        <div class="menu-container">
                                            <button type="button" class="menu-trigger" onclick="toggleMenu(event, {{ $index }})">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="1.5"></circle>
                                                    <circle cx="12" cy="5" r="1.5"></circle>
                                                    <circle cx="12" cy="19" r="1.5"></circle>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu" id="menu-{{ $index }}">
                                                @if(strtolower((string)$estado) !== 'facturado')
                                                    <button type="button" class="dropdown-item primary" onclick="enviarOC({{ $index }})">✉ Enviar OC</button>
                                                    <button type="button" class="dropdown-item" onclick="irAlGestor({{ $index }})">🔍 Ir al Gestor</button>
                                                @else
                                                    <button type="button" class="dropdown-item" style="opacity: 0.5; cursor: not-allowed;" title="Esta OC ya está facturada" disabled>✉ Enviar OC (S/N)</button>
                                                    <button type="button" class="dropdown-item" onclick="irAlGestor({{ $index }})">🔍 Ir al Gestor</button>
                                                @endif
                                                <button type="button" class="dropdown-item" onclick="verDetalle({{ $index }})">👁 Ver detalle</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($rows->total() === 0)
                    <div class="empty">No hay resultados para este filtro.</div>
                @endif

                <div class="footer">
                    <div>Mostrando: {{ $rows->total() }} solicitud{{ $rows->total() !== 1 ? 'es' : '' }}</div>
                    <div>Ultima actualizacion: {{ now()->format('d/m/Y') }}</div>
                </div>

                @if($rows->lastPage() > 1)
                    <div class="pagination-wrap" aria-label="Paginación de solicitudes">
                        <div class="pagination-info">
                            Mostrando {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} de {{ $rows->total() }} solicitudes
                        </div>
                        <div class="pagination">
                            <a class="page-link {{ $rows->onFirstPage() ? 'disabled' : '' }}" href="{{ $rows->previousPageUrl() ?: '#' }}">Anterior</a>

                            @for($page = 1; $page <= $rows->lastPage(); $page++)
                                <a class="page-link {{ $rows->currentPage() === $page ? 'active' : '' }}" href="{{ $rows->url($page) }}">{{ $page }}</a>
                            @endfor

                            <a class="page-link {{ $rows->hasMorePages() ? '' : 'disabled' }}" href="{{ $rows->nextPageUrl() ?: '#' }}">Siguiente</a>
                        </div>
                    </div>
                @endif
            </section>
        </main>
        </div> <!-- Cierre del wrapper de contenido -->
    </div> <!-- Cierre de .page -->

    <script>
        const searchInput = document.getElementById('search');
        const filterTipo = document.getElementById('filterTipo');
        const filterEstado = document.getElementById('filterEstado');

        // Restaurar filtros desde URL
        const restoreFiltersFromUrl = () => {
            const urlParams = new URLSearchParams(window.location.search);
            searchInput.value = urlParams.get('search') || '';
            filterTipo.value = urlParams.get('tipo') || '';
            filterEstado.value = urlParams.get('estado') || '';
        };

        // Aplicar filtros (recargar página con parámetros)
        const applyFilters = () => {
            const params = new URLSearchParams();
            const searchValue = searchInput.value.trim();
            const tipoValue = filterTipo.value;
            const estadoValue = filterEstado.value;

            if (searchValue) params.set('search', searchValue);
            if (tipoValue) params.set('tipo', tipoValue);
            if (estadoValue) params.set('estado', estadoValue);

            // Redirigir a página 1 con los filtros aplicados
            const query = params.toString();
            const nextUrl = query ? `${window.location.pathname}?${query}` : window.location.pathname;
            window.location.href = nextUrl;
        };

        // Debounce para búsqueda de texto
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });

        // Aplicar filtros inmediatamente al cambiar select
        filterTipo.addEventListener('change', applyFilters);
        filterEstado.addEventListener('change', applyFilters);

        // Restaurar valores al cargar la página
        restoreFiltersFromUrl();

        // Menú desplegable
        function toggleMenu(event, index) {
            event.stopPropagation();
            const button = event.currentTarget;
            const menu = document.getElementById(`menu-${index}`);
            const allMenus = document.querySelectorAll('.dropdown-menu');
            
            // Cerrar todos los otros menús
            allMenus.forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            document.querySelectorAll('tbody tr').forEach(tr => tr.style.zIndex = '');
            
            // Toggle el menú actual
            if (menu.classList.contains('show')) {
                menu.classList.remove('show');
                button.closest('tr').style.zIndex = '';
            } else {
                button.closest('tr').style.zIndex = '9999';
                // Posicionar el menú usando fixed position
                const rect = button.getBoundingClientRect();
                menu.style.top = (rect.bottom + 4) + 'px';
                menu.style.left = (rect.right - 170) + 'px';
                menu.classList.add('show');
            }
        }

        // Cerrar menús al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.menu-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
                document.querySelectorAll('tbody tr').forEach(tr => {
                    tr.style.zIndex = '';
                });
            }
        });

        let currentOcToSend = null;

        function enviarOC(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                currentOcToSend = row;
                
                // Set data to the confirmation modal
                const nroOC = row.dataset.numeroOc || 'N/A';
                document.getElementById('confirmEnvioOCNumber').textContent = nroOC;
                
                // Show confirmation modal
                document.getElementById('ocConfirmModal').classList.add('show');
            }
            
            // Cerrar el menú y restaurar z-index
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('tbody tr').forEach(tr => tr.style.zIndex = '');
        }

        function confirmarEnvio() {
            if (currentOcToSend) {
                const row = currentOcToSend;
                // Llenar el formulario oculto
                document.getElementById('oc_solicitud_id').value = row.dataset.id;
                document.getElementById('numero_oc').value = row.dataset.numeroOc;
                document.getElementById('ceco').value = row.dataset.ceco || '';
                document.getElementById('tipo_solicitud').value = row.dataset.tipoSolicitud || '';
                document.getElementById('proveedor').value = row.dataset.proveedor || '';
                document.getElementById('email_proveedor').value = row.dataset.emailProveedor || '';
                document.getElementById('rut').value = row.dataset.rut || '';
                document.getElementById('descripcion').value = row.dataset.descripcion || '';
                document.getElementById('cantidad').value = row.dataset.cantidad || '';
                document.getElementById('monto').value = row.dataset.monto || '';
                
                // Mover el archivo del modal al formulario real si existe
                const fileInput = document.getElementById('oc_file_input');
                const form = document.getElementById('ocSendForm');
                
                // Limpiar archivos previos en el form real
                const existingFile = form.querySelector('input[type="file"]');
                if (existingFile) existingFile.remove();
                
                // Clonar el input de archivo (o simplemente moverlo)
                if (fileInput.files.length > 0) {
                    form.appendChild(fileInput);
                }

                // Enviar el formulario
                form.submit();
                cerrarModalEnvio();
            }
        }

        function cerrarModalEnvio() {
            document.getElementById('ocConfirmModal').classList.remove('show');
            currentOcToSend = null;
        }

        function verDetalle(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                // Rellenar datos en el modal
                document.getElementById('modal-detalle-id').textContent = row.dataset.id || 'N/A';
                document.getElementById('modal-detalle-numero-oc').textContent = row.dataset.numeroOc || 'N/A';
                
                const estado = row.dataset.estado || 'N/A';
                const estadoEl = document.getElementById('modal-detalle-estado');
                estadoEl.textContent = estado;
                
                // Aplicar color al estado según su valor
                estadoEl.className = 'status-chip';
                estadoEl.style.background = '#f9edd1';
                estadoEl.style.color = '#856503';
                estadoEl.style.borderColor = '#edd49f';

                if (estado.toLowerCase() === 'aceptada' || estado.toLowerCase() === 'enviada') {
                    estadoEl.style.background = '#e8f6ee';
                    estadoEl.style.color = '#0f7a3e';
                    estadoEl.style.borderColor = '#bfe5cf';
                } else if (estado.toLowerCase() === 'facturado') {
                    estadoEl.style.background = '#e8f4fb';
                    estadoEl.style.color = '#0284c7';
                    estadoEl.style.borderColor = '#b9ddf2';
                } else if (estado.toLowerCase() === 'rechazada') {
                    estadoEl.style.background = '#fbeaea';
                    estadoEl.style.color = '#dc2626';
                    estadoEl.style.borderColor = '#f3c8c8';
                }

                document.getElementById('modal-detalle-fecha').textContent = row.dataset.fecha || 'N/A';
                document.getElementById('modal-detalle-ceco').textContent = row.dataset.ceco || 'N/A';
                document.getElementById('modal-detalle-tipo').textContent = row.dataset.tipoSolicitud || 'N/A';
                document.getElementById('modal-detalle-documento').textContent = row.dataset.tipoDocumento || 'N/A';
                document.getElementById('modal-detalle-proveedor').textContent = row.dataset.proveedor || 'N/A';
                document.getElementById('voucher-proveedor').textContent = row.dataset.proveedor || 'N/A';
                document.getElementById('transfer-nombre').textContent = row.dataset.proveedor || 'N/A';
                document.getElementById('modal-detalle-email').textContent = row.dataset.emailProveedor || 'No especificado';
                document.getElementById('modal-detalle-rut').textContent = row.dataset.rut || 'N/A';
                document.getElementById('transfer-rut').textContent = row.dataset.rut || 'N/A';
                document.getElementById('transfer-cuenta').textContent = row.dataset.emailProveedor || 'No especificada';
                document.getElementById('modal-detalle-monto').textContent = '$' + Number(row.dataset.monto || 0).toLocaleString('es-CL');
                document.getElementById('modal-detalle-cantidad').textContent = row.dataset.cantidad || 'N/A';
                document.getElementById('modal-detalle-descripcion').textContent = row.dataset.descripcion || 'N/A';
                document.getElementById('modal-detalle-observacion').textContent = row.dataset.observacion || 'No especificada';
                document.getElementById('voucher-fecha').textContent = row.dataset.fecha || 'N/A';

                const avatar = document.getElementById('owner-avatar');
                const proveedorName = (row.dataset.proveedor || 'OC').trim();
                avatar.textContent = proveedorName ? proveedorName.charAt(0).toUpperCase() : 'OC';
                
                // Gestión de Envío (Datos del Gestor)
                const managerComment = row.dataset.managerComment;
                const sentFilePath = row.dataset.sentFilePath;
                const gestionSection = document.getElementById('modal-detalle-gestion-section');
                
                if (managerComment || sentFilePath) {
                    gestionSection.style.display = 'block';
                    
                    const commentEl = document.getElementById('modal-detalle-manager-comment');
                    if (managerComment) {
                        commentEl.textContent = managerComment;
                        commentEl.parentElement.style.display = 'block';
                    } else {
                        commentEl.parentElement.style.display = 'none';
                    }
                    
                    const sentFileBtn = document.getElementById('modal-detalle-sent-file-btn');
                    const noSentFileEl = document.getElementById('modal-detalle-no-sent-file');
                    
                    if (sentFilePath) {
                        sentFileBtn.href = '/oc/enviadas/pdf/' + row.dataset.id; // Adjusted to use the route for downloading sent PDF
                        sentFileBtn.style.display = 'inline-flex';
                        noSentFileEl.style.display = 'none';
                    } else {
                        sentFileBtn.style.display = 'none';
                        noSentFileEl.style.display = 'inline-flex';
                    }
                } else {
                    gestionSection.style.display = 'none';
                }

                if (row.dataset.rutaAdjunto) {
                    document.getElementById('modal-detalle-adjunto-btn').href = '/oc/adjunto/' + row.dataset.rutaAdjunto;
                    document.getElementById('modal-detalle-adjunto-btn').style.display = 'inline-flex';
                    document.getElementById('modal-detalle-no-adjunto').style.display = 'none';
                } else {
                    document.getElementById('modal-detalle-adjunto-btn').style.display = 'none';
                    document.getElementById('modal-detalle-no-adjunto').style.display = 'inline-flex';
                }

                // Mostrar modal
                document.getElementById('ocDetailModal').classList.add('show');
            }

            // Cerrar el menú
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }

        function cerrarModalDetalle() {
            document.getElementById('ocDetailModal').classList.remove('show');
        }

        function irAlGestor(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (row) {
                // Redirigir al gestor de aprobaciones
                window.location.href = '{{ route("oc.gestor") }}';
            }
            // Cerrar el menú
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }



        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }
        });
    </script>

    
    <!-- Modal Detalle OC -->
    <div class="modal-overlay" id="ocDetailModal" onclick="cerrarModalDetalle()">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div class="rendicion-header">
                    <div class="rendicion-header-top">Expediente de gasto</div>
                    <h3 class="rendicion-title" id="modal-detalle-numero-oc">N/A</h3>
                </div>
                <button class="modal-close" onclick="cerrarModalDetalle()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="rendicion-layout">
                    <section class="rendicion-main">
                        <article class="rendicion-card rendicion-owner">
                            <div class="owner-info">
                                <div class="owner-avatar" id="owner-avatar">OC</div>
                                <div>
                                    <div class="owner-name" id="modal-detalle-proveedor">N/A</div>
                                    <div class="owner-meta">
                                        <span id="modal-detalle-fecha">N/A</span>
                                        <span class="status-chip" id="modal-detalle-estado">N/A</span>
                                    </div>
                                </div>
                            </div>
                            <button class="action-outline" type="button">Anular informe</button>
                        </article>

                        <article class="rendicion-card">
                            <h4 class="section-title">Listado de comprobantes</h4>
                            <div class="voucher-item">
                                <div class="voucher-head">
                                    <div class="voucher-main">
                                        <span class="voucher-icon"><i class="fas fa-file-invoice"></i></span>
                                        <div>
                                            <div class="voucher-name" id="voucher-proveedor">N/A</div>
                                            <div class="voucher-sub">
                                                <span>RUT: <span id="modal-detalle-rut">N/A</span></span>
                                                <span class="voucher-tag" id="modal-detalle-documento">Documento</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="voucher-amount">
                                        <span class="date" id="voucher-fecha">N/A</span>
                                        <span class="money" id="modal-detalle-monto">$0</span>
                                    </div>
                                </div>

                                <div class="voucher-preview">
                                    <div>
                                        <div class="desc-box" id="modal-detalle-descripcion"></div>
                                        <div class="obs-box" id="modal-detalle-observacion"></div>
                                    </div>

                                    <div>
                                        <a id="modal-detalle-adjunto-btn" href="#" class="btn-modern btn-download detail-cta" style="display:none;" target="_blank">
                                            <i class="fas fa-paperclip"></i> Ver Boleta
                                        </a>
                                        <button type="button" class="btn-modern detail-cta muted" id="modal-detalle-no-adjunto" style="display:none;">Sin adjunto</button>
                                    </div>
                                </div>
                            </div>

                            <div class="total-box">
                                <div class="total-label">Monto total rendición</div>
                                <div class="total-sub">Calculado automáticamente sobre <span id="modal-detalle-cantidad">0</span> items</div>
                            </div>
                        </article>
                    </section>

                    <aside class="rendicion-aside">
                        <article class="rendicion-card">
                            <h4 class="side-card-title">Gestión</h4>
                            <button type="button" class="ghost-action primary">Habilitar edición al solicitante</button>
                            <button type="button" class="ghost-action">Solicitar ajuste</button>
                        </article>

                        <article class="rendicion-card">
                            <h4 class="side-card-title">Información de transferencia</h4>
                            <div class="transfer-name" id="transfer-nombre">N/A</div>
                            <div class="transfer-list">
                                <div class="transfer-item"><strong>RUT</strong><span id="transfer-rut">N/A</span></div>
                                <div class="transfer-item"><strong>Banco</strong><span>No especificado</span></div>
                                <div class="transfer-item"><strong>Cuenta</strong><span id="transfer-cuenta">No especificada</span></div>
                            </div>
                        </article>

                        <article class="rendicion-card" id="estado_item">
                            <h4 class="side-card-title">Resumen</h4>
                            <div class="meta-grid">
                                <div class="meta-pill">
                                    <span class="k">ID Interno</span>
                                    <span class="v">#<span id="modal-detalle-id"></span></span>
                                </div>
                                <div class="meta-pill">
                                    <span class="k">CECO</span>
                                    <span class="v" id="modal-detalle-ceco"></span>
                                </div>
                                <div class="meta-pill">
                                    <span class="k">Tipo Solicitud</span>
                                    <span class="v" id="modal-detalle-tipo"></span>
                                </div>
                                <div class="meta-pill">
                                    <span class="k">Email Proveedor</span>
                                    <span class="v" id="modal-detalle-email"></span>
                                </div>
                            </div>
                        </article>

                        <article class="rendicion-card" id="modal-detalle-gestion-section" style="display: none;">
                            <h4 class="side-card-title">Gestión de envío</h4>
                            <div class="obs-box" id="modal-detalle-manager-comment" style="margin-top: 0;"></div>
                            <div style="margin-top: 10px;">
                                <a id="modal-detalle-sent-file-btn" href="#" class="btn-modern btn-download detail-cta" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Descargar OC enviada
                                </a>
                                <button type="button" class="btn-modern detail-cta muted" id="modal-detalle-no-sent-file" style="display:none;">No hay archivo enviado</button>
                            </div>
                        </article>
                    </aside>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modern btn-close" onclick="cerrarModalDetalle()">
                    Cerrar
                </button>
            </div>
        </div>
    </div>


    <!-- Formulario oculto para envío de OC -->
    <form id="ocSendForm" method="POST" action="{{ route('oc.send') }}" style="display: none;" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="oc_solicitud_id" name="oc_solicitud_id">
        <input type="hidden" id="numero_oc" name="numero_oc">
        <input type="hidden" id="ceco" name="ceco">
        <input type="hidden" id="tipo_solicitud" name="tipo_solicitud">
        <input type="hidden" id="proveedor" name="proveedor">
        <input type="hidden" id="email_proveedor" name="email_proveedor">
        <input type="hidden" id="rut" name="rut">
        <input type="hidden" id="descripcion" name="descripcion">
        <input type="hidden" id="cantidad" name="cantidad">
        <input type="hidden" id="monto" name="monto">
    </form>

    <!-- Modal Confirmar Envío OC -->
    <div class="modal-overlay" id="ocConfirmModal" style="z-index: 100000; display: none;">
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title" style="color: #0f6bb6;">Preparar Envío de OC</h3>
                <button class="modal-close" onclick="cerrarModalEnvio()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <form id="ocConfirmSendForm" method="POST" action="{{ route('oc.send') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden; margin: 0;">
                @csrf
                <input type="hidden" id="modal_oc_solicitud_id" name="oc_solicitud_id">
                <input type="hidden" id="modal_numero_oc" name="numero_oc">
                <input type="hidden" id="modal_ceco" name="ceco">
                <input type="hidden" id="modal_tipo_solicitud" name="tipo_solicitud">
                <input type="hidden" id="modal_proveedor" name="proveedor">
                <input type="hidden" id="modal_email_proveedor" name="email_proveedor">
                <input type="hidden" id="modal_rut" name="rut">
                <input type="hidden" id="modal_descripcion" name="descripcion">
                <input type="hidden" id="modal_cantidad" name="cantidad">
                <input type="hidden" id="modal_monto" name="monto">

                <div class="modal-body">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="font-size: 40px; color: #0f6bb6; margin-bottom: 12px;">📨</div>
                        <div style="font-size: 18px; font-weight: 600;">¿Enviar OC <span id="confirmEnvioOCNumber" style="color: #0f6bb6;"></span>?</div>
                        <p style="color: #64748b; font-size: 14px; margin-top: 8px;">Adjunte el archivo PDF de la OC y añada un comentario opcional para el proveedor.</p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 701; font-size: 13px; color: #475569; margin-bottom: 8px; text-transform: uppercase;">Archivo OC (PDF) <span style="color:red">*</span></label>
                        <input type="file" name="oc_file" id="modal_oc_file_idx" accept=".pdf" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #f8fafc;">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="display: block; font-weight: 701; font-size: 13px; color: #475569; margin-bottom: 8px; text-transform: uppercase;">Comentario para el Proveedor</label>
                        <textarea name="comentario" id="modal_comentario_idx" placeholder="Escriba un mensaje opcional..." style="width: 100%; min-height: 100px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="cerrarModalEnvio()" style="margin: 0;">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="margin: 0;">Enviar Orden de Compra</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('ocConfirmSendForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            // Usar setTimeout para evitar que algunos navegadores cancelen el envío al deshabilitar el botón instantáneamente
            setTimeout(() => {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            }, 10);
        });

        function cerrarModalEnvio() {
            document.getElementById('ocConfirmModal').style.display = 'none';
        }

        function enviarOC(index) {
            const row = document.querySelector(`tr[data-index="${index}"]`);
            if (!row) return;

            const nroOC = row.dataset.numeroOc || 'N/A';
            document.getElementById('confirmEnvioOCNumber').textContent = nroOC;
            
            // Populate hidden fields
            document.getElementById('modal_oc_solicitud_id').value = row.dataset.id;
            document.getElementById('modal_numero_oc').value = nroOC;
            document.getElementById('modal_ceco').value = row.dataset.ceco;
            document.getElementById('modal_tipo_solicitud').value = row.dataset.tipoSolicitud;
            document.getElementById('modal_proveedor').value = row.dataset.proveedor;
            document.getElementById('modal_email_proveedor').value = row.dataset.emailProveedor;
            document.getElementById('modal_rut').value = row.dataset.rut;
            document.getElementById('modal_descripcion').value = row.dataset.descripcion;
            document.getElementById('modal_cantidad').value = row.dataset.cantidad;
            document.getElementById('modal_monto').value = row.dataset.monto;

            // Show modal
            document.getElementById('ocConfirmModal').style.display = 'flex';
        }

        function verDetalleLegacy(index) {
            // Existing verDetalle implementation...
            // I should ensure this function exists or implemented properly elsewhere.
            // Since it was already there, I'll assume it's part of the scripts I haven't seen.
            if (typeof window.verDetalleExtra !== 'undefined') {
                window.verDetalleExtra(index);
            }
        }
    </script>
</body>
</html>
