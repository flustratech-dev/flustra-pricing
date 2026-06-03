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
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        /* Ambient Glow Backgrounds */
        .ambient-glow-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }

        .glow-top {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139, 94, 60, 0.12) 0%, rgba(253, 251, 247, 0) 70%);
            top: -200px;
            right: -100px;
        }

        .glow-bottom {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(92, 82, 67, 0.08) 0%, rgba(253, 251, 247, 0) 70%);
            bottom: -100px;
            left: -100px;
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
            background: linear-gradient(135deg, #A07C5F 0%, #825E43 100%);
            color: #ffffff !important;
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(130, 94, 67, 0.12), 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-neon-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(130, 94, 67, 0.18), 0 2px 5px rgba(0, 0, 0, 0.08);
            background: linear-gradient(135deg, #8D6B4E 0%, #704D34 100%);
            color: #ffffff !important;
        }

        .btn-neon-secondary {
            background: #ffffff;
            border: 1px solid var(--border-color);
            color: var(--text-main) !important;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-neon-secondary:hover {
            background: var(--surface-color);
            color: var(--text-main) !important;
            border-color: var(--primary-neon);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* Footers */
        footer {
            background: var(--surface-color);
            border-top: 1px solid var(--border-color);
            padding: 3rem 0;
            margin-top: 5rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            flex-shrink: 0;
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

        /* Custom Font Mappings */
        .font-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .font-serif-italic {
            font-family: 'Lora', Georgia, serif;
            font-style: italic;
        }

        .font-neue {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Scroll / Load Reveal Animations */
        @keyframes maskUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal-mask-up {
            animation: maskUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes scaleUp {
            from {
                opacity: 0;
                transform: scale(0.96);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .reveal-scale {
            animation: scaleUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Micro-animations */
        .magnetic-btn {
            position: relative;
            display: inline-block;
            transition: transform 0.3s cubic-bezier(0.25, 1, 0.2, 1);
        }

        .magnetic-btn:hover {
            transform: scale(1.04) translateY(-1px);
        }

        .magnetic-btn-content {
            display: inline-block;
        }

        /* Social icons & link styles */
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .social-icon:hover {
            transform: translateY(-3px) scale(1.08);
            color: var(--primary-neon) !important;
        }

        .footer-link {
            display: inline-block;
            transition: color 0.25s ease, transform 0.25s ease;
        }

        .footer-link:hover {
            transform: translateX(4px);
            color: var(--primary-neon) !important;
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
    <footer class="py-5 border-top relative z-20" style="background-color: var(--surface-color); border-color: var(--border-color) !important;">
        <div class="container reveal-mask-up">
            <div class="row g-4 text-start">
                <div class="col-12 col-md-6">
                    <a href="{{ route('home') }}"
                        class="font-brand fs-2 text-decoration-none mb-3 d-block anim-fill-text-light magnetic-btn"
                        style="color: var(--text-main);">
                        <span class="magnetic-btn-content">FLUSTRA.</span>
                    </a>
                    <p class="font-serif-italic max-w-sm anim-fill-text-light" style="color: var(--text-muted); max-width: 380px;">
                        Meningkatkan kejelasan keuangan melalui desain yang indah dan rekayasa yang tangguh.
                    </p>
                </div>
                
                <div class="col-6 col-md-3">
                    <h5 class="font-neue fw-bold mb-3 uppercase tracking-wider small anim-fill-text-brown reveal-scale"
                        style="color: var(--primary-neon); letter-spacing: 0.05em;">
                        PRODUK
                    </h5>
                    <ul class="space-y-2 font-neue small ps-0 list-unstyled">
                        <li class="anim-fill-text-light mb-2">
                            <a href="{{ route('home') }}#features" class="footer-link text-decoration-none" style="color: var(--text-muted);">Fitur</a>
                        </li>
                        <li class="anim-fill-text-light mb-2">
                            <a href="{{ route('plans.index') }}" class="footer-link text-decoration-none" style="color: var(--text-muted);">Harga</a>
                        </li>
                        <li class="anim-fill-text-light mb-2">
                            <a href="#" class="footer-link text-decoration-none" style="color: var(--text-muted);">Keamanan</a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-6 col-md-3">
                    <h5 class="font-neue fw-bold mb-3 uppercase tracking-wider small anim-fill-text-brown reveal-scale"
                        style="color: var(--primary-neon); letter-spacing: 0.05em;">
                        PERUSAHAAN
                    </h5>
                    <ul class="space-y-2 font-neue small ps-0 list-unstyled">
                        <li class="anim-fill-text-light mb-2">
                            <a href="#" class="footer-link text-decoration-none" style="color: var(--text-muted);">Tentang Kami</a>
                        </li>
                        <li class="anim-fill-text-light mb-2">
                            <a href="#" class="footer-link text-decoration-none" style="color: var(--text-muted);">Kontak</a>
                        </li>
                        <li class="anim-fill-text-light mb-2">
                            <a href="#" class="footer-link text-decoration-none" style="color: var(--text-muted);">Kebijakan Privasi</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 pt-4 border-top" 
                 style="border-color: var(--border-color) !important;">
                <p class="anim-fill-text-light small mb-0" style="color: var(--text-muted);">&copy; {{ date('Y') }} Flustra Financial. Hak cipta dilindungi undang-undang.</p>
                <div class="d-flex gap-4 mt-3 mt-md-0">
                    <a href="https://www.instagram.com/flustra.id?igsh=MWN0ejAzOHZ5aGdxbw=="
                        class="footer-link social-icon text-decoration-none" aria-label="Instagram" style="color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                        </svg>
                    </a>

                    <a href="#" class="footer-link social-icon text-decoration-none" aria-label="Threads" style="color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                 d="M22 11c-1.4-4.8-5.3-8.5-9.9-8.5C5.9 2.5 2 7.2 2 12c0 4.9 3.9 9.5 10.2 9.5 2.6 0 5-.8 6.8-2.3 1.3-1 1.9-2.6 2-4.1v-.3c0-1.8-1.1-3.3-2.7-3.3-1 0-1.9.6-2.4 1.5-1.1 1.9-2.1 3-4.2 3-2.1 0-4-1.6-4-4s1.9-4 4-4c1.7 0 3.3 1.1 3.7 2.7.2.7.1 1.4-.3 2-.4.6-1 .9-1.7.9h-2.1">
                            </path>
                        </svg>
                    </a>

                    <a href="https://x.com/flustraid?s=21"
                        class="footer-link social-icon text-decoration-none" aria-label="X" style="color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
                            <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
                        </svg>
                    </a>

                    <a href="https://www.tiktok.com/@flustra.id?_r=1&_t=ZS-96WZbhM441c"
                        class="footer-link social-icon text-decoration-none" aria-label="TikTok" style="color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                        </svg>
                    </a>

                    <a href="#" class="footer-link social-icon text-decoration-none" aria-label="LinkedIn" style="color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                            <rect width="4" height="12" x="2" y="9"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS & Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
