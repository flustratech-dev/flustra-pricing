<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title', 'Admin Panel') - Flustra</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom Dashboard Styles -->
    <style>
        :root {
            --bg-color: #f8f9fa; /* very light grey */
            --sidebar-bg: #ffffff; /* white */
            --surface-color: #ffffff; /* white */
            --primary-neon: #4f46e5; /* indigo-600 */
            --secondary-neon: #4338ca; /* indigo-700 */
            --text-main: #1f2937; /* gray-800 */
            --text-muted: #6b7280; /* gray-500 */
            --border-color: #e5e7eb; /* gray-200 */
            
            --hover-bg: #f3f4f6; /* gray-100 */
            --active-bg: #eef2ff; /* indigo-50 */
            --active-text: #4f46e5; /* indigo-600 */
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: -0.025em;
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            z-index: 100;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 0 1.5rem;
            border-bottom: 1px solid var(--border-color);
            height: 65px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-decoration: none;
            line-height: 1.2;
        }

        .sidebar-brand-title {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--primary-neon);
            letter-spacing: 0.5px;
        }
        
        .sidebar-brand-subtitle {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .sidebar-category {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-main); /* Diperjelas agar tidak nyaru */
            letter-spacing: 0.05em;
            margin-top: 0.5rem;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            padding: 0.6rem 1rem;
            margin: 0.2rem 1rem;
            border-radius: 8px;
            color: var(--text-main) !important;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .sidebar-nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            color: var(--text-muted);
        }

        .sidebar-nav-link:hover {
            background: var(--hover-bg);
        }

        .sidebar-nav-link.active {
            color: var(--active-text) !important;
            background: var(--active-bg);
            font-weight: 600;
        }

        .sidebar-nav-link.active i {
            color: var(--active-text) !important;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            margin-top: auto;
        }

        /* Sidebar Collapsed States */
        .sidebar.collapsed {
            width: 75px;
        }

        .sidebar.collapsed .sidebar-brand {
            padding: 1.5rem 0.5rem;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-brand-title span,
        .sidebar.collapsed .sidebar-brand-subtitle,
        .sidebar.collapsed .sidebar-category,
        .sidebar.collapsed .sidebar-nav-link span,
        .sidebar.collapsed .sidebar-footer .user-profile .ms-2,
        .sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-brand-title {
            font-size: 1.5rem;
        }

        .sidebar.collapsed .sidebar-nav-link {
            padding: 0.8rem 0;
            justify-content: center;
            margin: 0.2rem 0.5rem;
        }

        .sidebar.collapsed .sidebar-nav-link i {
            margin-right: 0 !important;
            font-size: 1.3rem;
        }

        /* Main Content wrapper */
        .main-wrapper {
            margin-left: 220px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-wrapper.collapsed {
            margin-left: 75px;
        }

        /* Header Navbar */
        .admin-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding: 0 2rem;
            height: 65px;
            background-color: var(--sidebar-bg);
        }

        .page-content-wrapper {
            padding: 1.5rem 2rem;
            flex-grow: 1;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        /* Desktop Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: transparent;
            color: var(--text-muted);
            border: none;
            padding: 0;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .sidebar-toggle-btn:hover {
            color: var(--text-main);
        }

        /* Card Overrides */
        .card {
            background-color: var(--surface-color) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden;
        }

        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: var(--text-main) !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-neon) !important;
            border-color: var(--primary-neon) !important;
            color: #ffffff !important;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-neon) !important;
            border-color: var(--secondary-neon) !important;
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-muted);
            border-radius: 8px;
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--hover-bg);
            color: var(--text-main);
        }

        /* Table styles */
        .table {
            color: var(--text-main) !important;
            margin-bottom: 0;
        }
        .table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
            background-color: #f9fafb;
        }
        .table td {
            font-size: 0.85rem;
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        .table tbody tr:hover {
            background-color: var(--hover-bg) !important;
        }

        /* Forms styling for admin panels */
        .form-control, .form-select, .form-check-input {
            background-color: #ffffff !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-neon) !important;
            box-shadow: 0 0 0 0.15rem rgba(79, 70, 229, 0.25) !important;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
        }
        
        /* Metric Card Icon Container */
        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Responsive Mobile Layout */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .admin-header {
                padding: 0 1rem;
            }
            .page-content-wrapper {
                padding: 1rem;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(2px);
                z-index: 99;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
            
            /* Sembunyikan efek toggle pada desktop di mobile */
            .main-wrapper.collapsed {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Overlay untuk Sidebar Mobile -->
    <div class="sidebar-overlay d-md-none" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-title">F<span>LUSTRA</span></div>
            <div class="sidebar-brand-subtitle">Pricing Admin</div>
        </a>

        <div class="sidebar-menu-wrapper flex-grow-1 overflow-auto" style="scrollbar-width: thin;">
            <ul class="sidebar-nav">
                <li class="sidebar-category">UTAMA</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.plans.index') }}" class="sidebar-nav-link {{ Route::is('admin.plans.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i><span>Paket</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.subscriptions.index') }}" class="sidebar-nav-link {{ Route::is('admin.subscriptions.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i><span>Langganan</span>
                    </a>
                </li>

                <li class="sidebar-category mt-3">MANAJEMEN</li>
                <li>
                    <a href="{{ route('admin.invoices.index') }}" class="sidebar-nav-link {{ Route::is('admin.invoices.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i><span>Tagihan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.analytics') }}" class="sidebar-nav-link {{ Route::is('admin.analytics') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i><span>Statistik</span>
                    </a>
                </li>

                <li class="sidebar-category mt-3">LAINNYA</li>
                <li>
                    <a href="{{ route('plans.index') }}" class="sidebar-nav-link">
                        <i class="bi bi-box-arrow-up-right"></i><span>Form Publik</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="user-profile d-flex align-items-center mb-3">
                <div class="avatar text-white d-flex align-items-center justify-content-center fw-bold rounded-circle flex-shrink-0" style="width: 36px; height: 36px; background-color: var(--primary-neon); font-size: 0.85rem;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="ms-2 text-truncate">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;">{{ auth()->user()->name ?? 'Admin User' }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0 w-100">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                    <i class="bi bi-box-arrow-right"></i> <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="admin-header">
            <button id="sidebarToggle" class="sidebar-toggle-btn d-md-none">
                <i class="bi bi-list fs-3"></i>
            </button>
            <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
        </header>

        <!-- Page Content -->
        <div class="page-content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainWrapper = document.querySelector('.main-wrapper');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Hapus sisa state lama (jika ada) agar desktop selalu terbuka
            localStorage.removeItem('sidebar-collapsed');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.toggle('mobile-open');
                        if(overlay) {
                            if(sidebar.classList.contains('mobile-open')) {
                                overlay.style.display = 'block';
                                setTimeout(() => overlay.classList.add('show'), 10);
                            } else {
                                overlay.classList.remove('show');
                                setTimeout(() => overlay.style.display = 'none', 300);
                            }
                        }
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('show');
                    setTimeout(() => overlay.style.display = 'none', 300);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
