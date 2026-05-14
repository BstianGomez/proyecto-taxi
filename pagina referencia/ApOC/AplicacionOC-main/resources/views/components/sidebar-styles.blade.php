<style>
    .page {
        min-height: 100vh;
        display: grid;
        grid-template-columns: auto 1fr;
    }

    .sidebar {
        background: linear-gradient(180deg, #0b5fa5 0%, #0f6bb6 50%, #1b7dc8 100%);
        color: #fff;
        width: 260px;
        display: flex;
        flex-direction: column;
        box-shadow: 5px 0 30px rgba(15, 107, 182, 0.2);
        transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 100;
    }

    .sidebar.collapsed {
            width: 80px;
    }

    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 88px;
    }

    .sidebar.collapsed .sidebar-header {
        padding: 24px 10px;
        justify-content: center;
    }

    .brand-badge {
            width: 80px !important;
            height: 60px !important;
            min-width: 80px;
            min-height: 60px;
            max-width: 80px;
            max-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: transparent;
            border: none;
            box-shadow: none;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

    .brand-badge:hover {
            transform: translateY(-2px) scale(1.02);
            background: transparent;
            border: none;
            box-shadow: none;
        }

    .brand-badge img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.04));
        transition: filter 0.3s ease;
    }

    .brand-badge:hover img {
        filter: drop-shadow(0 2px 4px rgba(15, 107, 182, 0.15)) brightness(1.03);
    }

    .brand-text {
        font-family: "Space Grotesk", "DM Sans", ui-sans-serif, system-ui, sans-serif;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: opacity 250ms, transform 250ms;
        line-height: 1.3;
    }

    .sidebar.collapsed .brand-text {
        opacity: 0;
        transform: translateX(-10px);
        pointer-events: none;
        position: absolute;
    }

    .brand-subtitle {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
        margin-top: 2px;
    }

    .sidebar-nav {
        flex: 1;
        padding: 12px 0;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 20px;
        margin: 4px 12px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        font-weight: 500;
        border-radius: 10px;
        position: relative;
    }

    .nav-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: #fff;
        border-radius: 0 2px 2px 0;
        transition: height 220ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
    }

    .nav-item:hover::before {
        height: 60%;
    }

    .nav-item.active {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
        font-weight: 600;
    }

    .nav-item.active::before {
        height: 70%;
    }

    .nav-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
        stroke-width: 2;
    }

    .nav-label {
        transition: opacity 250ms, transform 250ms;
        white-space: nowrap;
    }

    .sidebar.collapsed .nav-label {
        opacity: 0;
        transform: translateX(-10px);
        pointer-events: none;
        position: absolute;
    }

    .sidebar.collapsed .nav-item {
        padding: 14px;
        margin: 4px 10px;
        justify-content: center;
    }

    .sidebar.collapsed .nav-item::before {
        display: none;
    }

    .toggle-btn {
        position: absolute;
        bottom: 24px;
        right: 20px;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        display: grid;
        place-items: center;
        cursor: pointer;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
    }

    .sidebar.collapsed .toggle-btn {
        right: 50%;
        transform: translateX(50%);
    }

    .toggle-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: scale(1.05);
    }

    .sidebar.collapsed .toggle-btn:hover {
        transform: translateX(50%) scale(1.05);
    }

    .toggle-btn:active {
        transform: scale(0.95);
    }

    .sidebar.collapsed .toggle-btn:active {
        transform: translateX(50%) scale(0.95);
    }

    .toggle-icon {
        width: 20px;
        height: 20px;
        stroke: #fff;
        transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar.collapsed .toggle-icon {
        transform: rotate(180deg);
    }

    /* Tooltip for collapsed state */
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
</style>
