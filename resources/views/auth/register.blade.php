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
            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder=" " required autocomplete="name" autofocus>
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
        <div class="auth-checkbox @error('terms') is-error @enderror">
            <input type="checkbox" name="terms" id="terms" value="1" required @checked(old('terms'))>
            <label for="remember">
                Saya menyetujui <a href="https://flustra.jagoankode.my.id/syarat-dan-ketentuan">Syarat & Ketentuan</a> serta <a href="https://flustra.jagoankode.my.id/kebijakan-privasi">Kebijakan Privasi</a> Flustra.
            </label>
            @error('terms')
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
