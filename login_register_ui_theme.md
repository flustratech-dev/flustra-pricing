# Panduan Implementasi Tema UI Login & Register Flustra

Dokumen ini berisi panduan lengkap, kode sumber (HTML, CSS, JS, Blade), serta langkah-langkah untuk menerapkan tampilan UI Login & Register bertema **Glassmorphism + Cream/Brown (dengan WebGL Smoky Animated Background)** ke proyek web Anda yang lain.

---

## 1. Persiapan Dependensi

Salin kode berikut ke dalam tag `<head>` pada file layout utama Anda untuk memuat font Google Fonts dan ikon Bootstrap:

```html
<!-- Google Fonts: Outfit & Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
```

---

## 2. Design Tokens & CSS Variables

Tema ini menggunakan variabel warna bertema Cream, Espresso, dan Brown. Tambahkan variabel ini di bagian paling atas stylesheet global Anda (misalnya di `:root`):

```css
:root {
  /* Brown/Gold Accent Palette */
  --color-brown: #8B5E3C;
  --color-brown-10: rgba(139, 94, 60, 0.06);
  --color-brown-20: rgba(139, 94, 60, 0.12);
  --color-brown-80: rgba(139, 94, 60, 0.8);

  /* Cream/Espresso Color Palette */
  --color-cream-50: #FAF8F5;       /* Latar belakang warm off-white */
  --color-cream-100: #FFFFFF;      /* Putih solid */
  --color-cream-200: #F3EEE4;      /* Beige hangat untuk hover/interaksi */
  --color-cream-300: #EBE5DC;      /* Border tipis */
  --color-cream-400: #DDD5C9;
  --color-cream-700: #80776E;      /* Teks sekunder/muted */
  --color-cream-800: #544E48;      /* Teks body default */
  --color-cream-900: #5D4E44;      /* Teks judul/espresso */

  /* Semantic Colors */
  --color-success: #10B981;
  --color-warning: #F59E0B;
  --color-danger: #EF4444;

  /* Typography */
  --font-outfit: 'Outfit', sans-serif;
  --font-body: 'Plus Jakarta Sans', sans-serif;

  /* Border Radius */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 20px;
  --radius-full: 9999px;

  /* Transitions */
  --transition-fast: 0.15s ease;
  --transition-base: 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
```

---

## 3. Stylesheet Halaman Auth (`auth.css`)

Simpan kode CSS berikut sebagai `auth.css` dan muat di halaman autentikasi Anda:

```css
/* Auth pages — glassmorphism + cream/brown themed to elegant light theme */

.auth-page {
  position: relative;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background: var(--color-cream-50);
  font-family: var(--font-body);
}

.auth-canvas-wrap {
  position: absolute;
  inset: 0;
  z-index: 0;
}

.auth-canvas-wrap canvas {
  width: 100%;
  height: 100%;
  display: block;
}

.auth-canvas-blur {
  position: absolute;
  inset: 0;
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
  background: linear-gradient(
    165deg,
    rgba(255, 255, 255, 0.4) 0%,
    rgba(243, 238, 228, 0.5) 50%,
    rgba(235, 229, 220, 0.6) 100%
  );
  pointer-events: none;
}

.auth-topbar {
  position: relative;
  z-index: 20;
  padding: 1.25rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.auth-brand {
  font-family: var(--font-outfit);
  font-size: 1.15rem;
  font-weight: 500;
  color: var(--color-cream-900);
  text-decoration: none;
  letter-spacing: 0.3px;
  transition: color var(--transition-fast);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.auth-brand:hover {
  color: var(--color-brown);
}

.auth-topbar-link {
  font-family: var(--font-outfit);
  font-size: 0.85rem;
  color: var(--color-cream-800);
  text-decoration: none;
  padding: 0.4rem 0.9rem;
  border: 1px solid var(--color-cream-300);
  border-radius: var(--radius-full);
  transition: all var(--transition-fast);
}

.auth-topbar-link:hover {
  color: var(--color-cream-900);
  border-color: var(--color-cream-400);
  background: var(--color-cream-200);
}

.auth-main {
  position: relative;
  z-index: 10;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem 1.25rem 2.5rem;
}

.auth-card {
  width: 100%;
  max-width: 26rem;
  padding: 2rem 2rem 1.75rem;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.45);
  border-radius: var(--radius-xl);
  box-shadow: 0 24px 60px rgba(93, 78, 68, 0.08);
}

.auth-card--wide {
  max-width: 32rem;
}

.auth-card-header {
  text-align: center;
  margin-bottom: 1.75rem;
}

.auth-card-header h1 {
  font-family: var(--font-outfit);
  font-size: 1.75rem;
  font-weight: 400;
  color: var(--color-cream-900);
  letter-spacing: -0.5px;
  margin-bottom: 0.35rem;
}

.auth-card-header p {
  font-size: 0.875rem;
  font-weight: 300;
  color: var(--color-cream-700);
  margin: 0;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.auth-field {
  position: relative;
}

.auth-field input[type="text"],
.auth-field input[type="email"],
.auth-field input[type="password"],
.auth-field input[type="tel"] {
  display: block;
  width: 100%;
  padding: 0.65rem 0;
  font-size: 0.9rem;
  font-weight: 300;
  color: var(--color-cream-900);
  background: transparent;
  border: none;
  border-bottom: 2px solid var(--color-cream-300);
  border-radius: 0;
  outline: none;
  transition: border-color var(--transition-fast);
}

.auth-field input:focus {
  border-bottom-color: var(--color-brown);
  box-shadow: none;
}

.auth-field input::placeholder {
  color: transparent;
}

.auth-field label.auth-float-label {
  position: absolute;
  left: 0;
  top: 0.65rem;
  font-size: 0.875rem;
  font-weight: 300;
  color: var(--color-cream-700);
  pointer-events: none;
  transform-origin: left top;
  transition: transform 0.25s ease, color 0.2s ease, top 0.25s ease;
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

/* Floating Label Animation */
.auth-field input:focus ~ label.auth-float-label,
.auth-field input:not(:placeholder-shown) ~ label.auth-float-label,
.auth-field.has-value label.auth-float-label {
  transform: translateY(-1.35rem) scale(0.85);
  color: var(--color-cream-800);
}

.auth-field input:focus ~ label.auth-float-label {
  color: var(--color-brown);
}

.auth-field.is-error input {
  border-bottom-color: var(--color-danger);
}

.auth-field-error {
  display: block;
  margin-top: 0.35rem;
  font-size: 0.75rem;
  color: var(--color-danger);
}

.auth-field-row {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.auth-field-row .auth-field {
  flex: 1 1 calc(50% - 0.5rem);
  min-width: 140px;
}

.auth-checkbox {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  gap: 0.5rem;
}

.auth-checkbox input {
  margin-top: 0.2rem;
  accent-color: var(--color-brown);
  cursor: pointer;
}

.auth-checkbox label {
  font-size: 0.8rem;
  font-weight: 300;
  color: var(--color-cream-800);
  line-height: 1.45;
  cursor: pointer;
}

.auth-checkbox label a {
  color: var(--color-brown);
  text-decoration: underline;
  text-underline-offset: 2px;
}

.auth-forgot {
  text-align: right;
  margin-top: -0.75rem;
}

.auth-forgot a {
  font-size: 0.75rem;
  color: var(--color-cream-700);
  text-decoration: none;
}

.auth-forgot a:hover {
  color: var(--color-brown);
}

.auth-submit {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  width: 100%;
  padding: 0.85rem 1rem;
  font-family: var(--font-outfit);
  font-size: 0.95rem;
  font-weight: 500;
  color: var(--color-cream-100);
  background: var(--color-brown);
  border: none;
  border-radius: var(--radius-lg);
  cursor: pointer;
  transition: background var(--transition-fast), transform var(--transition-fast);
}

.auth-submit:hover {
  background: #6f4a2f;
  color: #fff;
  transform: translateY(-1px);
}

.auth-submit .bi {
  transition: transform var(--transition-fast);
}

.auth-submit:hover .bi-arrow-right {
  transform: translateX(4px);
}

.auth-footer-text {
  text-align: center;
  margin-top: 1.25rem;
  font-size: 0.8rem;
  color: var(--color-cream-700);
}

.auth-footer-text a {
  color: var(--color-brown);
  font-weight: 500;
  text-decoration: none;
}

.auth-footer-text a:hover {
  color: var(--color-cream-900);
  text-decoration: underline;
}

.auth-alert {
  margin-bottom: 1rem;
  padding: 0.75rem 1rem;
  border-radius: var(--radius-md);
  font-size: 0.85rem;
  background: rgba(16, 185, 129, 0.08);
  border: 1px solid rgba(16, 185, 129, 0.25);
  color: #065f46;
}

.auth-alert--error {
  background: rgba(239, 68, 68, 0.06);
  border-color: rgba(239, 68, 68, 0.25);
  color: #991b1b;
}
```

---

## 4. Background WebGL Smoky (`auth-smokey-bg.js`)

Skrip WebGL Shader ini menciptakan gelombang asap abstrak lembut yang bereaksi secara halus dengan gerakan kursor pengguna. Simpan file ini sebagai `auth-smokey-bg.js`:

```javascript
/**
 * WebGL Smokey Background — Flustra Custom Shader
 */
(function () {
  const canvas = document.getElementById('auth-smokey-canvas');
  if (!canvas) return;

  const colorHex = canvas.dataset.color || '#8B5E3C';

  const vertexSource = `
    attribute vec4 a_position;
    void main() {
      gl_Position = a_position;
    }
  `;

  const fragmentSource = `
    precision mediump float;
    uniform vec2 iResolution;
    uniform float iTime;
    uniform vec2 iMouse;
    uniform vec3 u_color;

    void mainImage(out vec4 fragColor, in vec2 fragCoord) {
      vec2 centeredUV = (2.0 * fragCoord - iResolution.xy) / min(iResolution.x, iResolution.y);
      float time = iTime * 0.5;
      vec2 mouse = iMouse / iResolution;
      vec2 rippleCenter = 2.0 * mouse - 1.0;
      vec2 distortion = centeredUV;
      for (float i = 1.0; i < 8.0; i++) {
        distortion.x += 0.5 / i * cos(i * 2.0 * distortion.y + time + rippleCenter.x * 3.1415);
        distortion.y += 0.5 / i * cos(i * 2.0 * distortion.x + time + rippleCenter.y * 3.1415);
      }
      float wave = abs(sin(distortion.x + distortion.y + time));
      float glow = smoothstep(0.9, 0.2, wave);
      
      // Elegant light cream background mix
      vec3 bgColor = vec3(250.0/255.0, 248.0/255.0, 245.0/255.0);
      vec3 finalColor = mix(bgColor, u_color, glow * 0.35);
      fragColor = vec4(finalColor, 1.0);
    }

    void main() {
      mainImage(gl_FragColor, gl_FragCoord.xy);
    }
  `;

  function hexToRgb(hex) {
    const h = hex.replace('#', '');
    return [
      parseInt(h.substring(0, 2), 16) / 255,
      parseInt(h.substring(2, 4), 16) / 255,
      parseInt(h.substring(4, 6), 16) / 255,
    ];
  }

  const gl = canvas.getContext('webgl');
  if (!gl) return;

  function compileShader(type, source) {
    const shader = gl.createShader(type);
    gl.shaderSource(shader, source);
    gl.compileShader(shader);
    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
      console.error(gl.getShaderInfoLog(shader));
      gl.deleteShader(shader);
      return null;
    }
    return shader;
  }

  const vs = compileShader(gl.VERTEX_SHADER, vertexSource);
  const fs = compileShader(gl.FRAGMENT_SHADER, fragmentSource);
  if (!vs || !fs) return;

  const program = gl.createProgram();
  gl.attachShader(program, vs);
  gl.attachShader(program, fs);
  gl.linkProgram(program);
  if (!gl.getProgramParameter(program, gl.LINK_STATUS)) return;

  gl.useProgram(program);

  const buffer = gl.createBuffer();
  gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
  gl.bufferData(
    gl.ARRAY_BUFFER,
    new Float32Array([-1, -1, 1, -1, -1, 1, -1, 1, 1, -1, 1, 1]),
    gl.STATIC_DRAW
  );

  const posLoc = gl.getAttribLocation(program, 'a_position');
  gl.enableVertexAttribArray(posLoc);
  gl.vertexAttribPointer(posLoc, 2, gl.FLOAT, false, 0, 0);

  const iRes = gl.getUniformLocation(program, 'iResolution');
  const iTime = gl.getUniformLocation(program, 'iTime');
  const iMouse = gl.getUniformLocation(program, 'iMouse');
  const uColor = gl.getUniformLocation(program, 'u_color');

  const [r, g, b] = hexToRgb(colorHex);
  gl.uniform3f(uColor, r, g, b);

  let start = Date.now();
  let mouse = { x: 0, y: 0 };
  let hovering = false;

  function render() {
    const w = canvas.clientWidth;
    const h = canvas.clientHeight;
    if (canvas.width !== w || canvas.height !== h) {
      canvas.width = w;
      canvas.height = h;
    }
    gl.viewport(0, 0, w, h);
    const t = (Date.now() - start) / 1000;
    gl.uniform2f(iRes, w, h);
    gl.uniform1f(iTime, t);
    gl.uniform2f(
      iMouse,
      hovering ? mouse.x : w / 2,
      hovering ? h - mouse.y : h / 2
    );
    gl.drawArrays(gl.TRIANGLES, 0, 6);
    requestAnimationFrame(render);
  }

  canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    mouse.x = e.clientX - rect.left;
    mouse.y = e.clientY - rect.top;
  });
  canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    mouse.x = e.clientX - rect.left;
    mouse.y = e.clientY - rect.top;
  });
  canvas.addEventListener('mouseenter', () => { hovering = true; });
  canvas.addEventListener('mouseleave', () => { hovering = false; });

  render();
})();
```

---

## 5. Implementasi HTML & JS Statis (Universal)

Format di bawah ini dapat langsung diintegrasikan pada framework frontend apa pun atau halaman HTML murni.

### A. Halaman Login (`login.html`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Flustra</title>
  
  <!-- Google Fonts & Bootstrap Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- CSS Utama -->
  <link rel="stylesheet" href="auth.css">
</head>
<body>

  <div class="auth-page">
    <!-- WebGL Background Canvas -->
    <div class="auth-canvas-wrap" aria-hidden="true">
      <canvas id="auth-smokey-canvas" data-color="#8B5E3C"></canvas>
      <div class="auth-canvas-blur"></div>
    </div>

    <!-- Topbar Header -->
    <header class="auth-topbar">
      <a href="#" class="auth-brand">
        <img src="logo.png" alt="Logo" style="height: 32px; width: auto; object-fit: contain;">
        <span>Flustra Web</span>
      </a>
      <a href="register.html" class="auth-topbar-link">Daftar Akun</a>
    </header>

    <!-- Main Content -->
    <main class="auth-main">
      <div class="auth-card">
        <div class="auth-card-header">
          <h1>Selamat Datang Kembali</h1>
          <p>Masuk menggunakan alamat email dan kata sandi Anda</p>
        </div>

        <!-- Formulir Login -->
        <form class="auth-form" action="/login" method="POST">
          
          <!-- Field Email -->
          <div class="auth-field">
            <input type="email" name="email" id="email" placeholder=" " required autocomplete="email">
            <label class="auth-float-label" for="email">
              <i class="bi bi-envelope"></i> Alamat Email
            </label>
          </div>

          <!-- Field Password -->
          <div class="auth-field">
            <input type="password" name="password" id="password" placeholder=" " required>
            <label class="auth-float-label" for="password">
              <i class="bi bi-lock"></i> Kata Sandi
            </label>
          </div>

          <!-- Fitur Lupa Password & Ingat Saya -->
          <div class="auth-forgot">
            <a href="#">Lupa kata sandi?</a>
          </div>

          <div class="auth-checkbox">
            <input type="checkbox" name="remember" id="remember" value="1">
            <label for="remember">Ingat saya di perangkat ini</label>
          </div>

          <!-- Tombol Submit -->
          <button type="submit" class="auth-submit">
            Masuk Sekarang
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>

        <p class="auth-footer-text">
          Belum punya akun? <a href="register.html">Daftar di sini</a>
        </p>
      </div>
    </main>
  </div>

  <!-- Script Animasi & Float Label Sync -->
  <script src="auth-smokey-bg.js"></script>
  <script>
    // Sinkronisasi kelas untuk menjaga label tetap terapung jika ada nilai default
    document.querySelectorAll('.auth-field input').forEach(function (input) {
      const wrap = input.closest('.auth-field');
      if (!wrap) return;
      function sync() {
        wrap.classList.toggle('has-value', input.value.length > 0);
      }
      input.addEventListener('input', sync);
      sync();
    });
  </script>
</body>
</html>
```

### B. Halaman Register (`register.html`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun - Flustra</title>
  
  <!-- Google Fonts & Bootstrap Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- CSS Utama -->
  <link rel="stylesheet" href="auth.css">
</head>
<body>

  <div class="auth-page">
    <!-- WebGL Background Canvas -->
    <div class="auth-canvas-wrap" aria-hidden="true">
      <canvas id="auth-smokey-canvas" data-color="#8B5E3C"></canvas>
      <div class="auth-canvas-blur"></div>
    </div>

    <!-- Topbar Header -->
    <header class="auth-topbar">
      <a href="#" class="auth-brand">
        <img src="logo.png" alt="Logo" style="height: 32px; width: auto; object-fit: contain;">
        <span>Flustra Web</span>
      </a>
      <a href="login.html" class="auth-topbar-link">Masuk</a>
    </header>

    <!-- Main Content -->
    <main class="auth-main">
      <div class="auth-card auth-card--wide"> <!-- Menggunakan auth-card--wide untuk kolom berdampingan -->
        <div class="auth-card-header">
          <h1>Daftar Akun Baru</h1>
          <p>Mulai pengalaman baru dengan membuat akun hari ini</p>
        </div>

        <!-- Formulir Pendaftaran -->
        <form class="auth-form" action="/register" method="POST">
          
          <!-- Field Nama Lengkap -->
          <div class="auth-field">
            <input type="text" name="name" id="name" placeholder=" " required autocomplete="name">
            <label class="auth-float-label" for="name">
              <i class="bi bi-person"></i> Nama Lengkap
            </label>
          </div>

          <!-- Baris Input Email & Telepon berdampingan -->
          <div class="auth-field-row">
            <div class="auth-field">
              <input type="email" name="email" id="email" placeholder=" " required autocomplete="email">
              <label class="auth-float-label" for="email">
                <i class="bi bi-envelope"></i> Alamat Email
              </label>
            </div>
            <div class="auth-field">
              <input type="tel" name="phone" id="phone" placeholder=" ">
              <label class="auth-float-label" for="phone">
                <i class="bi bi-telephone"></i> No. Telepon <span style="opacity: 0.75;">(Opsional)</span>
              </label>
            </div>
          </div>

          <!-- Baris Input Sandi & Konfirmasi Sandi berdampingan -->
          <div class="auth-field-row">
            <div class="auth-field">
              <input type="password" name="password" id="password" placeholder=" " required autocomplete="new-password">
              <label class="auth-float-label" for="password">
                <i class="bi bi-lock"></i> Kata Sandi
              </label>
            </div>
            <div class="auth-field">
              <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" " required autocomplete="new-password">
              <label class="auth-float-label" for="password_confirmation">
                <i class="bi bi-lock"></i> Konfirmasi Sandi
              </label>
            </div>
          </div>

          <!-- Checkbox Persetujuan Syarat & Ketentuan -->
          <div class="auth-checkbox">
            <input type="checkbox" name="agree" id="agree" value="1" required>
            <label for="agree">
              Saya menyetujui <a href="#">Syarat & Ketentuan Layanan</a> yang berlaku.
            </label>
          </div>

          <!-- Tombol Submit -->
          <button type="submit" class="auth-submit">
            Daftar Akun Baru
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>

        <p class="auth-footer-text">
          Sudah punya akun? <a href="login.html">Masuk sekarang</a>
        </p>
      </div>
    </main>
  </div>

  <!-- Script Animasi & Float Label Sync -->
  <script src="auth-smokey-bg.js"></script>
  <script>
    // Sinkronisasi kelas untuk menjaga label tetap terapung jika ada nilai default
    document.querySelectorAll('.auth-field input').forEach(function (input) {
      const wrap = input.closest('.auth-field');
      if (!wrap) return;
      function sync() {
        wrap.classList.toggle('has-value', input.value.length > 0);
      }
      input.addEventListener('input', sync);
      sync();
    });
  </script>
</body>
</html>
```

---

## 6. Integrasi Menggunakan Laravel Blade

Jika proyek tujuan Anda adalah Laravel, Anda dapat memecahnya menjadi potongan kode Blade modular berikut:

### A. Layout Utama (`resources/views/layouts/auth.blade.php`)

```blade
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
    
    <!-- Vite / Link CSS stylesheet proyek Anda -->
    @vite(['resources/css/style.css', 'resources/css/auth.css', 'resources/js/app.js'])
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
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 32px; width: auto; object-fit: contain;">
                <span>Flustra Web</span>
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
```

### B. View Login (`resources/views/auth/login.blade.php`)

```blade
@extends('layouts.auth')

@section('title', 'Masuk - Flustra')

@section('topbar_action')
    <a href="{{ route('register') }}" class="auth-topbar-link">Daftar Akun</a>
@endsection

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <h1>Selamat Datang Kembali</h1>
        <p>Masuk menggunakan akun terdaftar Anda</p>
    </div>

    @if(session('success'))
        <div class="auth-alert">{{ session('success') }}</div>
    @endif

    @if($errors->any() && !$errors->has('email'))
        <div class="auth-alert auth-alert--error">{{ $errors->first() }}</div>
    @endif

    <form class="auth-form" action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Field Email -->
        <div class="auth-field @error('email') is-error @enderror {{ old('email') ? 'has-value' : '' }}">
            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder=" " required autocomplete="email">
            <label class="auth-float-label" for="email">
                <i class="bi bi-envelope"></i> Alamat Email
            </label>
            @error('email')
                <span class="auth-field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
            @enderror
        </div>

        <!-- Field Password -->
        <div class="auth-field @error('password') is-error @enderror">
            <input type="password" name="password" id="password" placeholder=" " required autocomplete="current-password">
            <label class="auth-float-label" for="password">
                <i class="bi bi-lock"></i> Kata Sandi
            </label>
            @error('password')
                <span class="auth-field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-forgot">
            <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
        </div>

        <!-- Checkbox Ingat Saya -->
        <div class="auth-checkbox">
            <input type="checkbox" name="remember" id="remember" value="1" @checked(old('remember'))>
            <label for="remember">Ingat saya di perangkat ini</label>
        </div>

        <button type="submit" class="auth-submit">
            Masuk Sekarang
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <p class="auth-footer-text">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
    </p>
</div>
@endsection
```

### C. View Register (`resources/views/auth/register.blade.php`)

```blade
@extends('layouts.auth')

@section('title', 'Daftar Akun - Flustra')

@section('topbar_action')
    <a href="{{ route('login') }}" class="auth-topbar-link">Masuk</a>
@endsection

@section('content')
<div class="auth-card auth-card--wide">
    <div class="auth-card-header">
        <h1>Daftar Akun Baru</h1>
        <p>Isi formulir pendaftaran di bawah ini untuk membuat akun baru</p>
    </div>

    <form class="auth-form" action="{{ route('register') }}" method="POST">
        @csrf

        <!-- Field Nama Lengkap -->
        <div class="auth-field @error('name') is-error @enderror {{ old('name') ? 'has-value' : '' }}">
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder=" " required autocomplete="name">
            <label class="auth-float-label" for="name">
                <i class="bi bi-person"></i> Nama Lengkap
            </label>
            @error('name')
                <span class="auth-field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
            @enderror
        </div>

        <!-- Baris Input Email & Telepon berdampingan -->
        <div class="auth-field-row">
            <div class="auth-field @error('email') is-error @enderror {{ old('email') ? 'has-value' : '' }}">
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder=" " required autocomplete="email">
                <label class="auth-float-label" for="email">
                    <i class="bi bi-envelope"></i> Alamat Email
                </label>
                @error('email')
                    <span class="auth-field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <div class="auth-field @error('phone') is-error @enderror {{ old('phone') ? 'has-value' : '' }}">
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder=" ">
                <label class="auth-float-label" for="phone">
                    <i class="bi bi-telephone"></i> No. Telepon <span style="opacity: 0.75;">(Opsional)</span>
                </label>
                @error('phone')
                    <span class="auth-field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Baris Input Sandi & Konfirmasi Sandi berdampingan -->
        <div class="auth-field-row">
            <div class="auth-field @error('password') is-error @enderror">
                <input type="password" name="password" id="password" placeholder=" " required autocomplete="new-password">
                <label class="auth-float-label" for="password">
                    <i class="bi bi-lock"></i> Kata Sandi
                </label>
                @error('password')
                    <span class="auth-field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <div class="auth-field @error('password_confirmation') is-error @enderror">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" " required autocomplete="new-password">
                <label class="auth-float-label" for="password_confirmation">
                    <i class="bi bi-lock"></i> Konfirmasi Sandi
                </label>
                @error('password_confirmation')
                    <span class="auth-field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Checkbox Persetujuan Syarat & Ketentuan -->
        <div class="auth-checkbox @error('agree') is-error @enderror">
            <input type="checkbox" name="agree" id="agree" value="1" required @checked(old('agree'))>
            <label for="agree">
                Saya menyetujui <a href="#">Syarat & Ketentuan Layanan</a> yang berlaku.
            </label>
            @error('agree')
                <span class="auth-field-error d-block w-100"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="auth-submit">
            Daftar Akun Baru
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <p class="auth-footer-text">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
    </p>
</div>
@endsection
```
