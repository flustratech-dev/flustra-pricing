<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Flustra')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/auth.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    <div class="auth-page">
        <!-- WebGL Canvas untuk Latar Belakang Smoky. data-color diatur dinamis atau manual -->
        <div class="auth-canvas-wrap" aria-hidden="true">
            <canvas id="auth-smokey-canvas" data-color="{{ $shader_color ?? '#8B5E3C' }}"></canvas>
            <div class="auth-canvas-blur"></div>
        </div>

        <header class="auth-topbar">
            <a href="/" class="auth-brand d-inline-flex align-items-center gap-2">
                <img src="{{ asset('images/flustraa.png') }}" alt="Logo" style="height: 32px; width: auto; object-fit: contain;">
                <span>Flustra Plan</span>
            </a>
            @yield('topbar_action')
        </header>

        <main class="auth-main">
            @yield('content')
        </main>
    </div>

    <!-- Panggil Javascript WebGL Shader -->
    <script src="{{ asset('js/auth-smokey-bg.js') }}"></script>
    <script>
        // Mempertahankan label floating jika field terisi
        document.querySelectorAll('.auth-field input').forEach(function (input) {
            var wrap = input.closest('.auth-field');
            if (!wrap) return;
            function sync() {
                wrap.classList.toggle('has-value', input.value.length > 0);
            }
            input.addEventListener('input', sync);
            sync();
        });
    </script>
    @yield('scripts')
</body>
</html>
