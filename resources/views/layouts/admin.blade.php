<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - Flustra</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom Dashboard Styles -->
    <style>
        :root {
            --bg-color: #FDFBF7; /* cream-50 */
            --sidebar-bg: #F9F6EE; /* cream-100 */
            --surface-color: #F9F6EE; /* cream-100 */
            --primary-neon: #8B5E3C; /* brown */
            --secondary-neon: rgba(139, 94, 60, 0.8); /* brown/80 */
            --text-main: #332D24; /* cream-900 */
            --text-muted: #5C5243; /* cream-800 */
            --border-color: #EFEAE0; /* cream-200 */
            
            --hover-bg: rgba(139, 94, 60, 0.1); /* brown/10 */
            --active-bg: #EFEAE0; /* cream-200 */
            --active-text: #332D24; /* cream-900 */
            --toggle-bg: #8B5E3C; /* brown */
            --toggle-hover: #5E3E25; /* dark accent brown */
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
        }

        /* Sidebar styling */
        .sidebar {
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            z-index: 100;
            padding-top: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--text-muted) !important;
            text-decoration: none;
            padding: 0 1.5rem 1.5rem 1.5rem;
            display: block;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: var(--text-muted) !important;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }

        .sidebar-nav-link i {
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            color: var(--text-muted);
            transition: margin 0.3s ease;
        }

        .sidebar-nav-link:hover {
            color: var(--active-text) !important;
            background: var(--hover-bg);
        }

        .sidebar-nav-link:hover i {
            color: var(--active-text) !important;
        }

        .sidebar-nav-link.active {
            color: var(--active-text) !important;
            background: var(--active-bg);
            border-left-color: var(--primary-neon);
        }

        .sidebar-nav-link.active i {
            color: var(--active-text) !important;
        }

        /* Sidebar Collapsed States */
        .sidebar.collapsed {
            width: 75px;
        }

        .sidebar.collapsed .sidebar-brand {
            padding: 1.5rem 0.5rem;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-brand span {
            display: none;
        }

        .sidebar.collapsed .sidebar-brand i {
            margin: 0 !important;
            font-size: 1.5rem;
        }

        .sidebar.collapsed .sidebar-nav-link {
            padding: 0.8rem 0;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-nav-link span {
            display: none;
        }

        .sidebar.collapsed .sidebar-nav-link i {
            margin-right: 0 !important;
            font-size: 1.3rem;
        }

        /* Main Content wrapper */
        .main-wrapper {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-wrapper.collapsed {
            margin-left: 75px;
        }

        /* Header Navbar */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        /* Desktop Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background-color: var(--toggle-bg);
            color: #ffffff !important;
            border: none;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .sidebar-toggle-btn:hover {
            background-color: var(--toggle-hover);
            color: #ffffff !important;
            transform: scale(1.05);
        }

        /* Card Overrides */
        .card {
            background-color: var(--surface-color) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.05), 0 2px 4px -2px rgba(30, 58, 138, 0.05) !important;
            overflow: hidden;
        }

        .card-header {
            background-color: var(--hover-bg) !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 1.2rem 1.5rem;
            font-weight: 600;
            color: var(--text-main) !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Global overrides for dark mode classes inside admin layout */
        .text-white {
            color: var(--text-main) !important;
        }

        .text-secondary {
            color: var(--text-muted) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .bg-opacity-10 {
            background-color: rgba(59, 130, 246, 0.12) !important;
        }

        /* Premium Buttons */
        .btn-neon-primary {
            background: linear-gradient(135deg, var(--primary-neon) 0%, var(--secondary-neon) 100%) !important;
            color: #ffffff !important;
            border: 1px solid var(--border-color) !important;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);
        }

        .btn-neon-primary:hover {
            background: linear-gradient(135deg, var(--secondary-neon) 0%, var(--primary-neon) 100%) !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
            color: #ffffff !important;
        }

        .btn-neon-secondary {
            background: var(--active-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--active-text) !important;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-neon-secondary:hover {
            background: var(--hover-bg) !important;
            border-color: var(--primary-neon) !important;
            color: var(--active-text) !important;
        }

        /* Table styles */
        .table {
            color: var(--text-main) !important;
        }
        .table-light {
            --bs-table-bg: var(--active-bg) !important;
            --bs-table-border-color: var(--border-color) !important;
            color: var(--active-text) !important;
        }
        .table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }
        .table tbody tr:hover {
            background-color: var(--hover-bg) !important;
        }

        /* Pagination overrides */
        .pagination .page-link {
            background-color: var(--surface-color);
            border-color: var(--border-color);
            color: var(--text-main);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary-neon);
            border-color: var(--primary-neon);
            color: #ffffff !important;
        }

        /* Ambient badge colors */
        .badge {
            font-weight: 600;
            padding: 0.4em 0.8em;
            border-radius: 6px;
        }

        /* Forms styling for admin panels */
        .form-control, .form-select, .form-check-input {
            background-color: var(--bg-color) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-neon) !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="bi bi-shield-lock-fill text-warning me-2"></i><span>FLUSTRA ADMIN</span>
        </a>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.plans.index') }}" class="sidebar-nav-link {{ Route::is('admin.plans.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i><span>Kelola Paket</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.subscriptions.index') }}" class="sidebar-nav-link {{ Route::is('admin.subscriptions.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i><span>Kelola Langganan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.invoices.index') }}" class="sidebar-nav-link {{ Route::is('admin.invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i><span>Invoices / Billing</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.analytics') }}" class="sidebar-nav-link {{ Route::is('admin.analytics') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i><span>Statistik & MRR</span>
                </a>
            </li>
            <li class="mt-4 pt-3 border-top" style="border-color: var(--border-color) !important;">
                <a href="{{ route('plans.index') }}" class="sidebar-nav-link">
                    <i class="bi bi-arrow-left-circle"></i><span>Kembali ke Web</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle-btn btn">
                    <i class="bi bi-chevron-left" id="toggleIcon"></i>
                </button>
                <div>
                    <span class="text-muted small">Halo Admin,</span>
                    <h5 class="mb-0 fw-bold text-dark">{{ auth()->user()->name }}</h5>
                </div>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Keluar
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainWrapper = document.querySelector('.main-wrapper');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const toggleIcon = document.getElementById('toggleIcon');
            
            // Check local storage for preference
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainWrapper.classList.add('collapsed');
                if (toggleIcon) {
                    toggleIcon.classList.replace('bi-chevron-left', 'bi-chevron-right');
                }
            }
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('collapsed');
                    
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebar-collapsed', isCollapsed);
                    
                    if (toggleIcon) {
                        if (isCollapsed) {
                            toggleIcon.classList.replace('bi-chevron-left', 'bi-chevron-right');
                        } else {
                            toggleIcon.classList.replace('bi-chevron-right', 'bi-chevron-left');
                        }
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
