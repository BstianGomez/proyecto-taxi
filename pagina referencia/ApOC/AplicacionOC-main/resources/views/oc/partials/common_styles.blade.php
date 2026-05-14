    *, *::before, *::after {
        box-sizing: border-box;
    }

    :root {
        --bg: #f5f7fb;
        --bg-accent: #eef2ff;
        --ink: #101828;
        --muted: #5b6473;
        --brand: #0f6bb6;
        --brand-2: #0a4f86;
        --line: #e3e8f0;
        --card: #ffffff;
        --chip: #e8f1fb;
        --success: #0f7a3e;
        --warning: #b97700;
        --container-max-width: 1440px;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Transiciones suaves */
    .sidebar, .sidebar *, .nav-label, .brand-text {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* SideBar Base */
    .sidebar {
        background: linear-gradient(180deg, #0b5fa5 0%, #0f6bb6 50%, #1b7dc8 100%);
        color: #fff;
        width: 240px;
        display: flex;
        flex-direction: column;
        box-shadow: 5px 0 30px rgba(15, 107, 182, 0.2);
        position: relative;
        z-index: 1000;
        min-height: 100vh;
        flex-shrink: 0;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    /* Sidebar Header & Brand */
    .sidebar-header {
        padding: 20px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 80px;
    }

    .sidebar.collapsed .sidebar-header {
        padding: 20px 10px;
        justify-content: center;
    }

    .brand-badge {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 4px;
    }

    .brand-badge img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .brand-title {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: -0.3px;
        white-space: nowrap;
    }

    .brand-subtitle {
        font-size: 10px;
        opacity: 0.7;
        white-space: nowrap;
    }

    .sidebar.collapsed .brand-text, 
    .sidebar.collapsed .nav-label {
        opacity: 0;
        visibility: hidden;
        position: absolute;
        pointer-events: none;
    }

    /* Nav Items */
    .sidebar-nav {
        flex: 1;
        padding: 12px 0;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        margin: 4px 12px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        position: relative;
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .nav-item.active {
        background: rgba(255, 255, 255, 0.15); 
        color: #fff;
        font-weight: 700;
    }

    .nav-item.active::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 60%;
        background: #fff;
        border-radius: 0 4px 4px 0;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .sidebar.collapsed .nav-item {
        justify-content: center;
        padding: 12px;
        margin: 4px 10px;
    }

    .nav-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
        stroke-width: 2;
    }

    /* Toggle Button */
    .toggle-btn {
        position: fixed;
        bottom: 24px;
        left: 17px;
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        display: grid;
        place-items: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        z-index: 2000;
        color: white;
        transition: all 0.3s ease;
    }

    .toggle-btn:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: scale(1.1);
    }

    /* Layout Base */
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100vh;
    }

    body {
        background-color: var(--bg);
        font-family: "DM Sans", "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
        color: var(--ink);
        font-size: 13px;
    }

    .page {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        background: var(--bg);
    }

    /* TopBar & Header */
    .topbar {
        background: linear-gradient(90deg, #0b5fa5 0%, #0f6bb6 50%, #0b5fa5 100%);
        color: white;
        padding: 0 24px;
        box-shadow: 0 4px 20px rgba(15, 107, 182, 0.15);
        position: static !important;
        z-index: 100;
        min-height: 64px;
        display: flex;
        align-items: center;
        width: 100%;
        backdrop-filter: blur(10px);
    }

    .topbar-inner {
        width: 100%;
        max-width: var(--container-max-width);
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .topbar-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        pointer-events: none;
        display: flex;
        align-items: center;
    }

    .topbar-badge {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.12);
        padding: 6px 20px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }

    .topbar-badge img {
        width: 24px;
        height: 24px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }

    .topbar-badge span {
        font-weight: 600;
        font-size: 16px;
        color: white;
    }

    .user-info-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px 12px;
        background: rgba(255,255,255,0.12);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.2s ease;
    }

    .user-info-badge:hover {
        background: rgba(255,255,255,0.18);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: white;
    }

    .btn-logout, .btn-topbar {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
    }
    .btn-logout svg, .btn-topbar svg {
        flex-shrink: 0;
    }
    .btn-logout:hover, .btn-topbar:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-2px);
    }
    .btn-logout:active, .btn-topbar:active {
        transform: translateY(0);
    }

    .btn-logout:hover {
        background: #ef4444;
        border-color: #ef4444;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .btn-logout:hover {
        background: #ef4444;
        border-color: #ef4444;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    /* Content Area */
    .content {
        padding: 24px 24px 60px;
        max-width: var(--container-max-width);
        margin: 0 auto;
        width: 100%;
        flex: 1;
    }

    /* Banner/Header style */
    .banner {
        background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%);
        color: white;
        padding: 40px;
        border-radius: 0 0 30px 30px;
        margin-bottom: 40px;
        box-shadow: 0 10px 25px rgba(15, 107, 182, 0.15);
    }

    .banner h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .banner p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
    }

    /* Cards */
    .btn-ghost {
        background: white;
        color: var(--muted);
        border: 1px solid var(--line);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .btn-ghost:hover {
        background: var(--bg-accent);
        color: var(--brand);
        border-color: var(--brand);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 107, 182, 0.08);
    }
    
    .btn-add-option {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--bg-accent);
        color: var(--brand);
        border: 1px solid var(--line);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.2s ease;
        margin-left: 8px;
        vertical-align: middle;
    }
    
    .btn-add-option:hover {
        background: var(--brand);
        color: white;
        border-color: var(--brand);
        transform: scale(1.1);
    }
    .card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(16, 24, 40, 0.05);
        padding: 16px;
        width: 100%;
        margin-bottom: 30px;
    }

    /* Forms */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-grid.three-col {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .form-field.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--ink);
    }

    .input, .select, .textarea, .form-control, .form-select {
        width: 100% !important;
        padding: 8px 12px !important;
        border: 1px solid var(--line) !important;
        border-radius: 12px !important;
        font-family: inherit !important;
        font-size: 13px !important;
        color: var(--ink) !important;
        background: #fff !important;
        transition: all 0.2s ease !important;
    }

    .input:focus, .select:focus, .textarea:focus, .form-control:focus, .form-select:focus {
        outline: none !important;
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 4px rgba(15, 107, 182, 0.1) !important;
    }

    .textarea {
        min-height: 100px;
        resize: vertical;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0b5fa5 0%, #0f6bb6 100%) !important;
        color: white !important;
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(15, 107, 182, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary svg {
        flex-shrink: 0;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 107, 182, 0.3) !important;
    }

    .btn-ghost {
        background: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        padding: 10px 20px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-ghost svg {
        flex-shrink: 0;
    }
    .btn-ghost:hover {
        background: #e2e8f0 !important;
        transform: translateY(-1px);
    }

    /* Actions row */
    .actions {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--line);
    }

    /* Alerts */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        gap: 12px;
        font-size: 14px;
        font-weight: 500;
    }
    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .alert-list {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    /* Attachments */
    .attachment-box {
        background: #f8fafc;
        border: 2px dashed #e2e8f0;
        border-radius: 14px;
        padding: 24px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .attachment-box:hover {
        border-color: var(--brand);
        background: #f1f5f9;
    }
    .file-input {
        display: inline-block;
        background: #fff;
        border: 1px solid var(--line);
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 12px;
        transition: all 0.2s ease;
    }
    .file-input:hover {
        border-color: var(--brand);
        color: var(--brand);
    }
    .file-input input { display: none; }

    /* Tables */
    .table-container {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid var(--line);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    td {
        padding: 10px 12px;
        font-size: 12px;
        color: var(--ink);
        border-bottom: 1px solid var(--line);
        vertical-align: middle;
        white-space: normal;
        word-break: break-word;
    }

    .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 16px;
        border: 1px solid var(--line);
        background: white;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        table-layout: auto;
        border-collapse: collapse;
        background: #fff;
    }

    th {
        background: #f8fafc;
        padding: 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--muted);
        border-bottom: 1px solid var(--line);
        vertical-align: middle;
        white-space: nowrap;
    }

    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .compact { width: auto; }
    .col-tipo { width: auto; }
    .col-doc { width: auto; }
    .col-status { width: auto; }
    .col-proveedor { width: auto; }
    .col-desc { width: auto; }
    .col-actions { width: auto; }

    .chip, .status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .chip {
        background: var(--chip);
        color: var(--brand);
    }

    /* Modal styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-content, .modal-container {
        background: #fff;
        border-radius: 24px;
        padding: 0;
        width: 100%;
        max-width: 600px;
        max-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
        transform: scale(0.95);
        transition: transform 0.2s ease;
        overflow: hidden;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 24px 32px;
        border-bottom: 1px solid var(--line);
    }
    .modal-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color: var(--ink);
    }
    .modal-close {
        background: #f1f5f9;
        border: none;
        color: #64748b;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .modal-body {
        padding: 32px;
        overflow-y: auto;
        flex: 1;
    }
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        padding: 24px 32px;
        background: #f8fafc;
        border-top: 1px solid var(--line);
    }

    @media (max-width: 1024px) {
        :root {
            --container-max-width: 100%;
        }
        .sidebar {
            position: fixed;
            left: -240px;
            height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            z-index: 9999;
        }
        .sidebar.active {
            left: 0;
        }
        .main-content {
            margin-left: 0;
        }
        .sidebar.collapsed {
            width: 240px; /* Reset collapsed width on mobile */
            left: -240px;
        }
        .sidebar.collapsed.active {
            left: 0;
        }
        .toggle-btn {
            display: none; /* Hide standard toggle on mobile */
        }
    }

    /* Responsive Table Utils */
    @media (max-width: 768px) {
        .content { padding: 16px; }
        .card { padding: 20px; border-radius: 16px; }
        
        .responsive-table thead {
            display: none;
        }
        
        .responsive-table tbody tr {
            display: block;
            margin-bottom: 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px;
            background: #fff !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        
        .responsive-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            text-align: right !important;
            max-width: none !important;
            white-space: normal !important;
            width: 100% !important;
            min-width: 0 !important;
        }
        
        .responsive-table td:last-child {
            border-bottom: none;
        }
        
        .responsive-table td::before {
            content: attr(data-label);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            color: var(--muted);
            text-align: left;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .topbar-center { display: none; }
        .topbar { padding: 0 16px; }
        
        .banner { padding: 24px; border-radius: 0 0 20px 20px; margin-bottom: 24px; }
        .banner h1 { font-size: 24px; }
        
        .form-grid { grid-template-columns: 1fr; gap: 16px; }
        .actions { flex-direction: column; width: 100%; }
        .actions button, .actions a { width: 100%; }
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: white;
        padding: 8px;
        border-radius: 8px;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    @media (max-width: 1024px) {
        .mobile-menu-toggle {
            display: flex;
        }
    }
