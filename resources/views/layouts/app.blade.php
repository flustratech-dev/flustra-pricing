<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Flustra') }} - Pricing & Subscription</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom Premium Styles -->
    <style>
        :root {
            --bg-color: #FDFBF7; /* cream-50 */
            --surface-color: #F9F6EE; /* cream-100 */
            --surface-accent: #EFEAE0; /* cream-200 */
            --primary-neon: #8B5E3C; /* brown */
            --secondary-neon: rgba(139, 94, 60, 0.8); /* brown/80 */
            --accent-neon: #5E3E25; /* dark accent brown */
            --text-main: #332D24; /* cream-900 */
            --text-muted: #5C5243; /* cream-800 */
            --border-color: #EFEAE0; /* cream-200 */
            --glass-bg: rgba(249, 246, 238, 0.85); /* cream-100/80 */
            --glass-border: rgba(239, 234, 224, 0.8); /* cream-200/50 */
            --hover-bg: rgba(139, 94, 60, 0.1); /* brown/10 */
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glow Backgrounds */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139, 94, 60, 0.12) 0%, rgba(253, 251, 247, 0) 70%);
            top: -200px;
            right: -100px;
            z-index: -1;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(92, 82, 67, 0.08) 0%, rgba(253, 251, 247, 0) 70%);
            bottom: -100px;
            left: -100px;
            z-index: -1;
            pointer-events: none;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
        }

        /* Google Antigravity-Style Premium Navbar */
        .premium-nav {
            background: transparent;
            border-bottom: none;
            padding: 1.2rem 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--text-main) !important;
            background: none !important;
            -webkit-background-clip: unset !important;
            -webkit-text-fill-color: unset !important;
            letter-spacing: -0.5px;
            transition: opacity 0.2s ease;
        }

        .navbar-brand:hover {
            opacity: 0.85;
        }

        .nav-link {
            color: var(--text-main) !important;
            font-weight: 500;
            font-size: 0.92rem;
            padding: 0.5rem 1.1rem !important;
            border-radius: 9999px;
            transition: all 0.2s ease;
            margin: 0 0.15rem;
        }

        .nav-link:hover {
            color: var(--text-main) !important;
            background-color: rgba(139, 94, 60, 0.08); /* subtle warm pill background */
        }

        .nav-link.active {
            color: var(--text-main) !important;
            font-weight: 600;
            background-color: rgba(139, 94, 60, 0.05); /* subtle warm active pill */
        }

        /* Google Antigravity-Style Pill Buttons */
        .nav-btn-primary {
            background: var(--text-main) !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 1.4rem;
            border-radius: 9999px !important;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .nav-btn-primary:hover {
            background: var(--accent-neon) !important;
            color: #ffffff !important;
        }

        .nav-btn-secondary {
            background: transparent !important;
            color: var(--text-main) !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 1.4rem;
            border-radius: 9999px !important;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .nav-btn-secondary:hover {
            background: rgba(139, 94, 60, 0.08) !important;
            color: var(--text-main) !important;
        }

        .nav-btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
        }

        /* Premium Buttons */
        .btn-neon-primary {
            background: linear-gradient(135deg, var(--primary-neon) 0%, var(--accent-neon) 100%);
            color: #ffffff !important;
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(139, 94, 60, 0.25);
        }

        .btn-neon-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(139, 94, 60, 0.35);
            color: #ffffff !important;
        }

        .btn-neon-secondary {
            background: var(--surface-accent);
            border: 1px solid var(--border-color);
            color: var(--text-main) !important;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-neon-secondary:hover {
            background: var(--hover-bg);
            color: var(--text-main) !important;
            border-color: var(--primary-neon);
        }

        /* Footers */
        footer {
            background: var(--surface-color);
            border-top: 1px solid var(--border-color);
            padding: 3rem 0;
            margin-top: 5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--surface-accent);
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-neon);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg premium-nav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/flustraa.png') }}" alt="Flustra Logo" class="me-2" style="height: 32px; width: auto; object-fit: contain;">
                <span>FLUSTRA</span>
            </a>
            <button class="navbar-toggler border-0 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('plans.*') ? 'active' : '' }}" href="{{ route('plans.index') }}">Paket Langganan</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('subscription.*') ? 'active' : '' }}" href="{{ route('subscription.index') }}">Dashboard Saya</a>
                        </li>
                        @if(auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <span class="badge bg-danger px-2 py-1"><i class="bi bi-shield-lock me-1"></i>Admin Panel</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <div class="d-flex align-items-center gap-3">
                    @auth
                        <span class="small d-none d-sm-inline" style="color: #5c5243;">Halo, <strong>{{ auth()->user()->name }}</strong></span>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="nav-btn-secondary nav-btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-btn-secondary">Masuk</a>
                        <a href="{{ route('register') }}" class="nav-btn-primary">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <div class="mb-3">
                <a class="navbar-brand d-inline-flex align-items-center fs-4" href="{{ route('home') }}">
                    <img src="{{ asset('images/flustraa.png') }}" alt="Flustra Logo" class="me-2" style="height: 34px; width: auto; object-fit: contain; filter: drop-shadow(0px 2px 4px rgba(139, 94, 60, 0.15));">
                    <span>FLUSTRA</span>
                </a>
            </div>
            <p class="mb-1">&copy; {{ date('Y') }} Flustra. Hak Cipta Dilindungi Undang-Undang.</p>
            <p class="small text-secondary">Premium Pricing & Subscription Management Suite.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS & Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
