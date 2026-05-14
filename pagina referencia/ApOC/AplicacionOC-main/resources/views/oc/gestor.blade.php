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
        
        .content {
            padding: 16px 10px 30px;
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
            padding: 16px 20px;
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
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
            border-radius: 0 0 16px 16px;
        }

        .table-wrap::-webkit-scrollbar {
            height: 8px;
        }

        .table-wrap::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .table-wrap::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #475569;
            background: #f1f5f9;
            border-bottom: 2px solid #cbd5e1;
            padding: 10px 12px;
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
            padding: 10px 12px;
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
            max-width: 150px;
            white-space: normal;
        }

        tbody td.col-descripcion {
            width: auto;
            max-width: 200px;
            white-space: normal;
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
        
        .status.enviada { background: #ecfdf5 !important; color: #059669 !important; border: 1px solid #10b98133; }
        .status.enviada .dot { background: #10b981 !important; transform: scale(1.2); }

        tbody td {
            padding: 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            color: #1f2937;
            vertical-align: middle;
        }

        /* Wrap solo en descripción y proveedor */
        .col-proveedor,
        .col-descripcion {
            min-width: 180px;
            white-space: normal !important;
            word-break: break-word;
        }

        /* No wrap en el resto */
        tbody td:not(.col-proveedor):not(.col-descripcion) {
            white-space: nowrap !important;
        }

        tbody td.actions-cell {
            overflow: visible !important;
            padding-right: 20px;
            width: 110px;
            min-width: 110px;
            position: relative;
        }

        .actions-dropdown {
            position: relative;
            display: inline-block;
        }

        .actions-btn {
            white-space: nowrap !important;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            border: 1.5px solid #e5e9f2;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(16, 24, 40, 0.20), 0 2px 8px rgba(16, 24, 40, 0.10);
            min-width: 200px;
            z-index: 1000;
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
                width: auto;
            }
        }
        
        /* Modal Styles Mejorados */
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
        .btn-modern {
            padding: 10px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-close { background: #f1f5f9; color: #475569; }
        .btn-close:hover { background: #e2e8f0; color: #0f172a; }
        .btn-download { background: #0f6bb6; color: white; box-shadow: 0 4px 6px rgba(15, 107, 182, 0.2); text-decoration: none;}
        .btn-download:hover { background: #0d5a9a; box-shadow: 0 6px 8px rgba(15, 107, 182, 0.3); transform: translateY(-1px); }

        #ocDetailModal .modal-container {
            max-width: 1040px;
            border-radius: 24px;
            border: 1px solid #d9e2ef;
        }

        #ocDetailModal .modal-header {
            padding: 18px 24px;
            background: linear-gradient(135deg, #f7f9fd 0%, #eef4ff 100%);
        }

        #ocDetailModal .modal-body {
            max-height: calc(100vh - 220px);
            padding: 16px;
            background: linear-gradient(170deg, #f2f6fd 0%, #e6edf8 100%);
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
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .ghost-action:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .ghost-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(1);
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

        #modal-detalle-extra-container {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        #modal-detalle-extra-container .detail-item {
            border: 1px dashed #dbe5f2;
            border-radius: 10px;
            padding: 10px;
            background: #f8fbff;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            font-weight: 700;
        }

        .detail-value {
            color: #1f2937;
            font-size: 14px;
            word-break: break-word;
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

            #modal-detalle-extra-container {
                grid-template-columns: 1fr;
            }
        }
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
</head>
<body>
    <div class="page">
        @include('oc.partials.sidebar', ['active' => 'gestor'])

        <!-- Main Content -->
        <div class="main-content">
            <x-page-header 
                title="" 
                subtitle=""
                :backRoute="null"
                :showLogout="true"
            />

            <script>
                function showAlert(icon, title) {
                    Swal.fire({
                        icon: icon,
                        title: title,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            </script>

            @if(session('success'))
                <script>showAlert('success', "{{ session('success') }}");</script>
            @endif

            @if(session('error'))
                <script>showAlert('error', "{{ session('error') }}");</script>
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
                
                    

                <section class="card" aria-label="Tabla de solicitudes">
                        <div class="toolbar">
                            <div>
                                <div class="toolbar-title" style="display:flex; align-items:center; gap:12px; margin-bottom: 4px;">
                                    <div style="background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%); width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(15, 107, 182, 0.05);">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#0f6bb6;">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                    <h1 style="font-size: 24px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; margin: 0;">Gestor de Aprobaciones</h1>
                                </div>
                                <div class="toolbar-subtitle" style="font-size: 14px; color: #64748b; margin-left: 52px;">Administre y procese las solicitudes de órdenes de compra.</div>
                            </div>
                    <div class="toolbar-actions" style="display: flex; align-items: center; gap: 16px;">
                        <div class="pending-summary">
                            <span class="pending-summary-dot"></span>
                            <span class="pending-summary-label">Pendientes Totales</span>
                            <span class="pending-summary-count">{{ \Illuminate\Support\Facades\DB::table('oc_solicitudes')->where('estado', 'Solicitada')->count() }}</span>
                        </div>
                        <a href="{{ route('oc.export') }}" class="btn btn-ghost">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Exportar
                        </a>
                    </div>
                </div>


                <div style="display: flex; gap: 24px; padding: 0 30px; border-bottom: 1px solid #e2e8f0; background: #fff;">
                    <a href="{{ route('oc.gestor') }}" style="padding: 16px 4px; font-weight: 600; font-size: 14px; text-decoration: none; color: {{ request('vista') !== 'historial' ? '#2563eb' : '#64748b' }}; border-bottom: 3px solid {{ request('vista') !== 'historial' ? '#2563eb' : 'transparent' }};">
                        Pendientes
                    </a>
                    <a href="{{ route('oc.gestor', ['vista' => 'historial']) }}" style="padding: 16px 4px; font-weight: 600; font-size: 14px; text-decoration: none; color: {{ request('vista') === 'historial' ? '#2563eb' : '#64748b' }}; border-bottom: 3px solid {{ request('vista') === 'historial' ? '#2563eb' : 'transparent' }};">
                        Historial y Facturadas
                    </a>
                </div>
                <div class="filters">
                    <div class="field">
                        <label for="search">Buscar</label>
                        <div class="search-container">
                            <i class="fas fa-search"></i>
                            <input id="search" class="input" type="search" placeholder="Buscar por proveedor, descripcion o rut" />
                        </div>
                    </div>
                    <div class="field">
                        <label for="filterTipo">Tipo solicitud</label>
                        <select id="filterTipo" class="select">
                            <option value="">Todos</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Interna">Interna</option>
                            <option value="Negocio">Negocio</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="filterSort">Orden</label>
                        <select id="filterSort" class="select">
                            <option value="newest">Recientes primero</option>
                            <option value="oldest">Antiguas primero</option>
                        </select>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th class="compact"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-building'></i> CECO</th>
                                <th class="col-tipo"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-tags'></i> Tipo</th>
                                <th class="col-doc"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-file-alt'></i> Documento</th>
                                <th class="col-status"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-info-circle'></i> Estado</th>
                                <th class="col-status"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-receipt'></i> Facturación</th>
                                <th class="col-proveedor"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-truck'></i> Proveedor</th>
                                <th class="col-desc"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-align-left'></i> Descripción</th>
                                <th class="compact"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-sort-amount-up'></i> Cant.</th>
                                <th class="compact"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-dollar-sign'></i> Monto</th>
                                <th class="col-actions" style="text-align: right; padding-right: 24px;"><i style='margin-right: 3px; color: #94a3b8; font-size: 11px;' class='fas fa-cog'></i> Acciones</th>
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
                                    <tr class="{{ strtolower((string) $estado) === 'solicitada' ? 'pending-row' : '' }}" data-text="{{ strtolower(($ceco ?? '').' '.($tipo ?? '').' '.($documento ?? '').' '.($estado ?? '').' '.($rut ?? '').' '.($proveedor ?? '').' '.($descripcion ?? '')) }}"
                                        data-tipo="{{ $tipo }}"
                                        data-estado="{{ $estado }}"
                                        data-index="{{ $index }}"
                                        @php
                                            $datosExtra = json_decode($row->datos_extra, true) ?? [];
                                            $emailProv = data_get($row, 'email_proveedor') ?: data_get($datosExtra, 'email', '');
                                        @endphp
                                        data-id="{{ $row->id }}"
                                        data-numero-oc="{{ $numeroOc }}"
                                        data-ceco="{{ $ceco }}"
                                        data-tipo-solicitud="{{ $tipo }}"
                                        data-tipo-documento="{{ $documento }}"
                                        data-proveedor="{{ $proveedor }}"
                                        data-email-proveedor="{{ $emailProv }}"
                                        data-rut="{{ $rut }}"
                                        data-descripcion="{{ $descripcion }}"
                                        data-cantidad="{{ $cantidad }}"
                                        data-monto="{{ $monto }}"
                                        data-observacion="{{ data_get(json_decode($row->datos_extra, true) ?? [], 'observacion', data_get(json_decode($row->datos_extra, true) ?? [], 'observacion2', 'No especificada')) }}"
                                        data-ruta-adjunto="{{ data_get(json_decode($row->datos_extra, true) ?? [], 'ruta_adjunto', '') }}"
                                        data-datos-extra="{{ isset($row->datos_extra) ? htmlspecialchars($row->datos_extra, ENT_QUOTES, 'UTF-8') : '{}' }}"
                                        data-fecha="{{ data_get($row, 'created_at') ? date('d/m/Y H:i', strtotime($row->created_at)) : 'N/A' }}"
                                         data-file-path="{{ $row->sent_file_path ?? '' }}">
                                        <td class="compact" data-label="CECO">{{ $cecoNumber }}</td>
                                    <td data-label="Tipo"><span class="chip chip-{{ $tipoClass }}">{{ $tipo }}</span></td>
                                    <td data-label="Documento">{{ $documento }}</td>
                                    <td data-label="Estado"><span class="status {{ $estadoClass }}">
                                            <span class="dot"></span>
                                            {{ $estado }}
                                        </span>
                                    </td>
                                    <td data-label="Facturación">@php $estadoFacturacion = $row->estado_facturacion ?? 'No Facturado';
                                            $facturadoClass = strtolower($estadoFacturacion) === 'facturado' ? 'ok' : 'pending';
                                        @endphp
                                        <span class="status {{ $facturadoClass }}">
                                            <span class="dot"></span>
                                            {{ $estadoFacturacion }}
                                        </span>
                                    </td>
                                    <td class="col-proveedor" title="{{ $proveedor }}" data-label="Proveedor">{{ $proveedor }}</td>
                                    <td class="col-descripcion" title="{{ $descripcion }}" data-label="Descripción">{{ $descripcion }}</td>
                                    <td class="compact" data-label="Cantidad">{{ $cantidad }}</td>
                                    <td class="compact" data-label="Monto">${{ number_format((float) $monto, 0, ',', '.') }}</td>
                                    <td class="actions-cell" style="padding: 12px; text-align: center; overflow: visible !important;">
                                        <div class="actions-dropdown">
                                            <button type="button" class="actions-btn" onclick="toggleDropdown(this, event)">
                                                Acciones
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item" onclick="verDetalle({{ $index }})">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    Ver Detalles
                                                </button>
                                                @if(strtolower((string)$estado) === 'solicitada')
                                                    <div class="dropdown-divider"></div>
                                                    <button type="button" class="dropdown-item" style="color: #059669;" onclick="promptAccept({{ $row->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                        Aceptar
                                                    </button>
                                                    <button type="button" class="dropdown-item" style="color: #0284c7; font-weight: 700;" onclick="enviarOC({{ $index }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
                                                        Subir / Enviar OC
                                                    </button>
                                                    <button type="button" class="dropdown-item" style="color: #dc2626;" onclick="promptReject({{ $row->id }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        Rechazar
                                                    </button>
                                                @elseif(strtolower((string)$estado) === 'aceptada')
                                                    <div class="dropdown-divider"></div>
                                                    <button type="button" class="dropdown-item" style="color: #0284c7; font-weight: 700;" onclick="enviarOC({{ $index }})">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
                                                        Subir / Enviar OC
                                                    </button>
                                                    <button type="button" class="dropdown-item" style="color: #0f172a;" onclick="marcarFacturado({{ $row->id }}, this)">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                                        Marcar Facturado
                                                    </button>
                                                @elseif(strtolower((string)$estado) === 'enviada' && (($row->solicitud_estado_facturacion ?? 'No Facturado') !== 'Facturado'))
                                                    <div class="dropdown-divider"></div>
                                                    <button type="button" class="dropdown-item @if(!($row->file_path ?? '')) disabled @endif" 
                                                            @if($row->file_path ?? '') onclick="enviarOcManualFromGestor({{ $row->id }}, this)" @endif
                                                            style="@if(!($row->file_path ?? '')) opacity: 0.5; cursor: not-allowed; @else color: #0f6bb6; font-weight: 600; @endif">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path></svg>
                                                        Re-enviar OC por Email
                                                    </button>
                                                    <button type="button" class="dropdown-item" style="color: #0f172a;" onclick="marcarFacturado({{ $row->id }}, this)">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                                        Marcar Facturado
                                                    </button>
                                                @endif
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
        const filterSort = document.getElementById('filterSort');

        // Restaurar filtros desde URL
        const restoreFiltersFromUrl = () => {
            const urlParams = new URLSearchParams(window.location.search);
            searchInput.value = urlParams.get('search') || '';
            filterTipo.value = urlParams.get('tipo') || '';
            filterSort.value = urlParams.get('sort') || 'newest';
        };

        // Aplicar filtros (recargar página con parámetros)
        const applyFilters = () => {
            const params = new URLSearchParams(window.location.search);
            const searchValue = searchInput.value.trim();
            const tipoValue = filterTipo.value;
            const sortValue = filterSort.value;

            if (searchValue) params.set('search', searchValue);
            else params.delete('search');
            
            if (tipoValue) params.set('tipo', tipoValue);
            else params.delete('tipo');
            
            if (sortValue) params.set('sort', sortValue);
            else params.delete('sort');

            // Siempre volver a la página 1 al filtrar
            params.set('page', '1');

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        };

        // Debounce para búsqueda de texto
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });

        // Aplicar filtros inmediatamente al cambiar select
        filterTipo.addEventListener('change', applyFilters);
        filterSort.addEventListener('change', applyFilters);

        // Polling para actualizaciones automáticas
        let lastId = {{ \Illuminate\Support\Facades\DB::table('oc_solicitudes')->max('id') ?? 0 }};
        
        setInterval(async () => {
            try {
                const response = await fetch('{{ route('oc.gestor.poll') }}');
                const data = await response.json();
                
                if (data.latest_id > lastId) {
                    // Si hay nuevos datos y el usuario no está escribiendo o en un modal, recargar
                    const isTyping = document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA';
                    const isModalOpen = document.body.classList.contains('swal2-shown') || document.getElementById('ocConfirmModal').style.display === 'flex';
                    
                    if (!isTyping && !isModalOpen) {
                        location.reload();
                    } else {
                        // Si está ocupado, solo actualizamos el ID para no molestar, 
                        // pero marcamos que hay cambios pendientes si quisiéramos mostrar un aviso
                        lastId = data.latest_id; 
                    }
                }
            } catch (error) {
                console.error('Error en polling:', error);
            }
        }, 15000); // Revisar cada 15 segundos

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
                
                // Set data to the confirmation modal form
                document.getElementById('modal_oc_solicitud_id').value = row.dataset.id;
                document.getElementById('modal_numero_oc').value = row.dataset.numeroOc;
                document.getElementById('modal_ceco').value = row.dataset.ceco || '';
                document.getElementById('modal_tipo_solicitud').value = row.dataset.tipoSolicitud || '';
                document.getElementById('modal_proveedor').value = row.dataset.proveedor || '';
                document.getElementById('modal_email_proveedor').value = row.dataset.emailProveedor || '';
                document.getElementById('modal_rut').value = row.dataset.rut || '';
                document.getElementById('modal_descripcion').value = row.dataset.descripcion || '';
                document.getElementById('modal_cantidad').value = row.dataset.cantidad || '';
                document.getElementById('modal_monto').value = row.dataset.monto || '';
                
                document.getElementById('confirmEnvioOCNumber').textContent = row.dataset.numeroOc || 'N/A';
                
                // Reset file and comment
                document.getElementById('modal_oc_file').value = '';
                document.getElementById('modal_comentario').value = '';
                
                // Show confirmation modal
                document.getElementById('ocConfirmModal').classList.add('show');
                document.getElementById('ocConfirmModal').style.display = 'flex';
            }
            
            // Cerrar el menú y restaurar z-index
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('tbody tr').forEach(tr => tr.style.zIndex = '');
        }

        function cerrarModalEnvio() {
            document.getElementById('ocConfirmModal').classList.remove('show');
            document.getElementById('ocConfirmModal').style.display = 'none';
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
                if (row.dataset.rutaAdjunto) {
                    document.getElementById('modal-detalle-adjunto-btn').href = '/oc/adjunto/' + row.dataset.rutaAdjunto;
                    document.getElementById('modal-detalle-adjunto-btn').style.display = 'inline-flex';
                    document.getElementById('modal-detalle-no-adjunto').style.display = 'none';
                } else {
                    document.getElementById('modal-detalle-adjunto-btn').style.display = 'none';
                    document.getElementById('modal-detalle-no-adjunto').style.display = 'inline-flex';
                }

                // Mostrar datos extra
                const extraContainer = document.getElementById('modal-detalle-extra-container');
                extraContainer.innerHTML = '';
                try {
                    const extraData = JSON.parse(row.dataset.datosExtra || '{}');
                    // Exclude specific generic fields if they are inside
                    const excludeKeys = ['_token', 'proveedor', 'rut', 'email_proveedor', 'descripcion', 'monto_mensual', 'ceco'];
                    const extraKeys = Object.keys(extraData).filter(k => !excludeKeys.includes(k));

                    if (extraKeys.length > 0) {
                        extraContainer.innerHTML = `
                            <div class="detail-item full-width" style="margin-top: 8px; padding-top: 16px; border-top: 1px dashed #e2e8f0;">
                                <span class="detail-label" style="color: #0f6bb6;">Datos Adicionales de la Solicitud</span>
                            </div>
                        `;
                        
                        extraKeys.forEach(key => {
                            // Formatear la clave para que sea legible (ej: fecha_inicio_curso -> Fecha Inicio Curso)
                            const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            let value = extraData[key];
                            
                            // Si el valor es de otro tipo (array, objeto), que se vea bien
                            if(typeof value === 'object' && value !== null) {
                                value = JSON.stringify(value, null, 2);
                            }
                            if(value === null || value === '') value = 'N/A';

                            extraContainer.innerHTML += `
                                <div class="detail-item">
                                    <span class="detail-label">${label}</span>
                                    <span class="detail-value" style="word-break: break-word;">${value}</span>
                                </div>
                            `;
                        });
                    }
                } catch (e) {
                    console.error('Error al parsear datos_extra', e);
                }

                // Configurar botones de gestión
                const btnEdicion = document.getElementById('btn-habilitar-edicion');
                const btnAjuste = document.getElementById('btn-solicitar-ajuste');
                const isAceptada = row.dataset.estado.toLowerCase() === 'aceptada';
                const solicitudId = row.dataset.id;

                if (isAceptada) {
                    btnEdicion.disabled = true;
                    btnAjuste.disabled = true;
                    btnEdicion.title = "No se puede habilitar edición en una solicitud aceptada";
                    btnAjuste.title = "No se puede solicitar ajuste en una solicitud aceptada";
                } else {
                    btnEdicion.disabled = false;
                    btnAjuste.disabled = false;
                    btnEdicion.title = "";
                    btnAjuste.title = "";
                    
                    // Asignar funciones
                    btnEdicion.onclick = () => habilitarEdicion(solicitudId);
                    btnAjuste.onclick = () => solicitarAjuste(solicitudId);
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

        async function habilitarEdicion(id) {
            const result = await Swal.fire({
                title: '¿Habilitar edición?',
                text: "El solicitante podrá modificar esta OC desde su panel.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b69950',
                confirmButtonText: 'Sí, habilitar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/oc/gestor/${id}/habilitar-edicion`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        }
                    });
                    if (response.ok) {
                        showAlert('success', 'Edición habilitada correctamente.');
                        setTimeout(() => location.reload(), 1500);
                    }
                } catch (e) { showAlert('error', 'Error al procesar la solicitud.'); }
            }
        }

        async function solicitarAjuste(id) {
            const { value: comentario } = await Swal.fire({
                title: 'Solicitar ajuste',
                input: 'textarea',
                inputLabel: 'Indique los cambios necesarios:',
                inputPlaceholder: 'Escriba aquí las observaciones...',
                inputAttributes: { 'aria-label': 'Escriba aquí las observaciones' },
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Enviar solicitud',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) return '¡Debes escribir un comentario!';
                }
            });

            if (comentario) {
                try {
                    const response = await fetch(`/oc/gestor/${id}/solicitar-ajuste`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ comentario })
                    });
                    if (response.ok) {
                        showAlert('success', 'Ajuste solicitado correctamente.');
                        setTimeout(() => location.reload(), 1500);
                    }
                } catch (e) { showAlert('error', 'Error al procesar la solicitud.'); }
            }
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
                <button class="modal-close" onclick="cerrarModalDetalle()" title="Cerrar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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

                        <article class="rendicion-card">
                            <h4 class="section-title">Datos adicionales</h4>
                            <div id="modal-detalle-extra-container"></div>
                        </article>
                    </section>

                    <aside class="rendicion-aside">
                        <article class="rendicion-card">
                            <h4 class="side-card-title">Gestión</h4>
                            <button type="button" id="btn-habilitar-edicion" class="ghost-action primary">Habilitar edición al solicitante</button>
                            <button type="button" id="btn-solicitar-ajuste" class="ghost-action">Solicitar ajuste</button>
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
                    </aside>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="cerrarModalDetalle()" style="margin: 0; width: auto; font-weight: 700;">
                    Cerrar Detalle
                </button>
            </div>
        </div>
    </div>


    <!-- Formulario oculto para envío de OC -->
    <!-- El formulario antiguo ha sido reemplazado por ocConfirmSendForm dentro del modal -->

    <!-- Modal Confirmar Envío OC -->
    <div class="modal-overlay" id="ocConfirmModal">
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title">Preparar Envío de OC</h3>
                <button class="modal-close" onclick="cerrarModalEnvio()" title="Cerrar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
                        <input type="file" name="oc_file" id="modal_oc_file" accept=".pdf,application/pdf" required style="width: 100%; padding: 10px; border: 1.5px solid #cbd5e1; border-radius: 12px; font-size: 14px; background: #f8fafc;">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="display: block; font-weight: 701; font-size: 13px; color: #475569; margin-bottom: 8px; text-transform: uppercase;">Comentario para el Proveedor</label>
                        <textarea name="comentario" id="modal_comentario" placeholder="Escriba un mensaje opcional..." style="width: 100%; min-height: 100px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="cerrarModalEnvio()" style="border-radius: 12px; margin: 0;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" style="margin: 0; font-weight: 700;">
                        Subir y Enviar OC
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('ocConfirmSendForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            setTimeout(() => {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            }, 10);
        });

        // Mostrar/ocultar modal de confirmación
        const ocConfirmModal = document.getElementById('ocConfirmModal');
        function showConfirmModal() {
            const modal = document.getElementById('ocConfirmModal');
            const container = modal.querySelector('.modal-container');
            modal.style.display = 'flex';
            setTimeout(() => { container.style.transform = 'scale(1)'; }, 10);
            document.body.style.overflow = 'hidden';
        }

        function hideConfirmModal() {
            const modal = document.getElementById('ocConfirmModal');
            const container = modal.querySelector('.modal-container');
            container.style.transform = 'scale(0.95)';
            setTimeout(() => { 
                modal.style.display = 'none'; 
                document.body.style.overflow = '';
            }, 200);
        }

        function cerrarModalEnvio() {
            hideConfirmModal();
            currentOcToSend = null;
        }

        function verDetalleLegacy(id) {
            const tr = document.querySelector(`.solicitud-row[data-id="${id}"]`);
            if(!tr) return;

            document.getElementById('modal-detalle-id').innerText = id;
            document.getElementById('modal-detalle-numero-oc').innerText = tr.dataset.numero || 'N/A';
            document.getElementById('modal-detalle-fecha').innerText = tr.dataset.fecha || 'N/A';
            
            const estado = tr.dataset.estado;
            const estadoEl = document.getElementById('modal-detalle-estado');
            estadoEl.innerText = estado;
            estadoEl.className = 'detail-value ' + (estado.toLowerCase() === 'enviada' ? 'status-enviada' : 'status-pendiente');

            document.getElementById('modal-detalle-ceco').innerText = tr.querySelector('td:nth-child(1)').innerText;
            document.getElementById('modal-detalle-tipo').innerText = tr.dataset.tipo || 'N/A';
            document.getElementById('modal-detalle-documento').innerText = tr.querySelector('td:nth-child(3)').innerText;
            document.getElementById('modal-detalle-rut').innerText = tr.dataset.rut || 'N/A';
            document.getElementById('modal-detalle-proveedor').innerText = tr.querySelector('td:nth-child(5)').innerText;
            document.getElementById('modal-detalle-email').innerText = tr.dataset.emailProveedor || 'N/A';
            document.getElementById('modal-detalle-cantidad').innerText = tr.dataset.cantidad || '0';
            document.getElementById('modal-detalle-monto').innerText = tr.querySelector('td:nth-child(7)').innerText;
            document.getElementById('modal-detalle-descripcion').innerText = tr.dataset.descripcion || 'Sin descripción';
            document.getElementById('modal-detalle-observacion').innerText = tr.dataset.observacion || 'Sin observación';

            const modal = document.getElementById('ocDetailModal');
            const container = modal.querySelector('.modal-container');
            modal.style.display = 'flex';
            setTimeout(() => { container.style.transform = 'scale(1)'; }, 10);
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalDetalle() {
            const modal = document.getElementById('ocDetailModal');
            const container = modal.querySelector('.modal-container');
            container.style.transform = 'scale(0.95)';
            setTimeout(() => { 
                modal.style.display = 'none'; 
                document.body.style.overflow = '';
            }, 200);
        }
    </script>

<script>
        function toggleDropdown(btn, event) {
            event.stopPropagation();
            const allMenus = document.querySelectorAll('.dropdown-menu');
            const currentMenu = btn.nextElementSibling;
            const isVisible = currentMenu.style.display === 'block';

            allMenus.forEach(menu => {
                menu.style.display = 'none';
            });

            if (!isVisible) {
                const rect = btn.getBoundingClientRect();
                currentMenu.style.display = 'block';
                currentMenu.style.position = 'fixed';
                currentMenu.style.width = '200px';
                currentMenu.style.left = (rect.right - 200) + 'px';
                currentMenu.style.zIndex = '9999';
                
                const spaceBelow = window.innerHeight - rect.bottom;
                if (spaceBelow < 250) {
                    currentMenu.style.bottom = (window.innerHeight - rect.top + 5) + 'px';
                    currentMenu.style.top = 'auto';
                } else {
                    currentMenu.style.top = (rect.bottom + 5) + 'px';
                    currentMenu.style.bottom = 'auto';
                }
            } else {
                currentMenu.style.display = 'none';
            }
        }

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });
    function updateStatus(id, estado, observacion = null) {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        
        fetch(`/oc/gestor/${id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ estado, observacion })
        }).then(async res => {
            const isJson = res.headers.get('content-type')?.includes('application/json');
            if (!res.ok) {
                if (isJson) {
                    const errorData = await res.json();
                    throw new Error(errorData.error || errorData.message || 'Error en el servidor (' + res.status + ')');
                } else {
                    const text = await res.text();
                    if (text.includes('Page Expired')) throw new Error('La sesión ha expirado. Por favor, recarga la página.');
                    throw new Error('Error inesperado: ' + (text.substring(0, 50) || res.statusText));
                }
            }
            return res.json();
        }).then(data => {
            if(data.success) {
                let text = `La solicitud #${id} ha sido ${estado.toLowerCase()} correctamente.`;
                if (estado.toLowerCase() === 'rechazada') {
                    text = `La solicitud ha sido rechazada y movida al historial.`;
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success', 
                    title: '¡Listo!', 
                    text: text,
                    timer: 3000, 
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Aviso',
                    text: data.error || 'No se pudo completar la acción.'
                });
            }
        }).catch(err => {
            console.error('Update Status Error:', err);
            Swal.fire({
                icon: 'error', 
                title: 'Error de proceso', 
                text: err.message || 'Ocurrió un error al intentar actualizar el estado.'
            });
        });
    }

    async function enviarOcManualFromGestor(id, btn) {
        if (btn.classList.contains('disabled')) return;
        
        const result = await Swal.fire({
            title: '¿Enviar OC al Proveedor?',
            text: "Se notificará al proveedor con el archivo adjunto.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f6bb6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, enviar ahora',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Enviando OC...',
            text: 'Notificando al proveedor...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const response = await fetch(`/oc/enviadas/${id}/send`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errData = await response.json().catch(() => ({ message: 'Error interno en el servidor.' }));
                throw new Error(errData.message || `Error del servidor (${response.status})`);
            }

            const data = await response.json();
            if (data.success) {
                showAlert('success', 'Email procesado correctamente (Modo Log activo).');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showAlert('error', data.message);
            }
        } catch (error) {
            showAlert('error', error.message || 'No se pudo conectar con el servidor.');
        }
    }

    function promptAccept(id) {
        Swal.fire({
            title: 'Aceptar Solicitud',
            icon: 'question',
            iconColor: '#10b981',
            html: '<p style="color: #64748b; font-size: 14px; margin-bottom: 10px;">¿Estás seguro de que deseas aprobar esta solicitud de OC?</p><p style="color: #94a3b8; font-size: 13px;">Esta acción cambiará el estado a <strong>Aceptada</strong>.</p>',
            showCancelButton: true,
            confirmButtonText: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:4px;"><polyline points="20 6 9 17 4 12"></polyline></svg> Sí, Aceptar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            buttonsStyling: true,
            customClass: {
                popup: 'swal-modern-popup',
                title: 'swal-modern-title',
                confirmButton: 'swal-modern-confirm',
                cancelButton: 'swal-modern-cancel'
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                updateStatus(id, 'Aceptada');
            }
        }).catch(err => console.log('Swal Error:', err));
    }

    function promptReject(id) {
        Swal.fire({
            title: 'Rechazar Solicitud',
            icon: 'warning',
            iconColor: '#ef4444',
            html: '<p style="color: #64748b; font-size: 14px; margin-bottom: 10px;">Por favor, indica el motivo del rechazo.</p>',
            input: 'textarea',
            inputPlaceholder: 'Escribe aquí el motivo detallado...',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-times"></i> Rechazar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            buttonsStyling: true,
            customClass: {
                popup: 'swal-modern-popup',
                title: 'swal-modern-title',
                input: 'swal-modern-input',
                confirmButton: 'swal-modern-confirm',
                cancelButton: 'swal-modern-cancel'
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'El motivo de rechazo es obligatorio para continuar'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus(id, 'Rechazada', result.value);
            }
        }).catch(err => console.log('Swal Error:', err));
    }

    function marcarFacturado(id, button) {
        Swal.fire({
            title: '¿Marcar como facturado?',
            text: 'Esta solicitud se marcará como facturada y se moverá al historial.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, facturar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch(`/oc/gestor/${id}/facturacion`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', 'OC marcada como facturada correctamente.');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showAlert('error', data.message || 'Error al procesar.');
                    }
                } else {
                    showAlert('error', 'Error en la respuesta del servidor.');
                }
            }
        });
    }

    function toggleFacturacion(id, isChecked) {
        const estado_facturacion = isChecked ? 'Facturado' : 'No Facturado';
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
        
        fetch(`/oc/gestor/${id}/facturacion`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ estado_facturacion })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                // Notificar guardado silencioso sin recargar obligatoriamente
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Facturación actualizada',
                    showConfirmButton: false,
                    timer: 1500
                }).then(()=>location.reload());
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        const pdfInputs = document.querySelectorAll('input[type="file"][accept*="pdf"]');
        pdfInputs.forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.type !== 'application/pdf') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Archivo no válido',
                        text: 'Por favor, seleccione un archivo PDF válido.'
                    });
                    this.value = '';
                }
            });
        });
    });
</script>

</body>
</html>
