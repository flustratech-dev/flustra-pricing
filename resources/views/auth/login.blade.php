<x-guest-layout>
    <div class="w-full max-w-5xl bg-[#FFFDF9] rounded-[2.5rem] shadow-[0_25px_60px_-15px_rgba(179,84,18,0.15)] overflow-hidden flex flex-col md:flex-row min-h-[600px] border border-[#F1E4D0]/50">
        
        <!-- Left Side: Sunset Workspace & Branding -->
        <div class="relative w-full md:w-1/2 min-h-[300px] md:min-h-full flex flex-col justify-end p-8 sm:p-12 text-white overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-10000 hover:scale-105" style="background-image: url('{{ asset('images/auth-hero.png') }}');"></div>
            <!-- Sunset Color Tint & Dark Gradients -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#4f2002]/95 via-[#B35412]/50 to-[#b35412]/10 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-[#b35412]/20 mix-blend-color"></div>

            <!-- Content overlay -->
            <div class="relative z-10">
                <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-normal leading-[1.15] mb-4 text-white tracking-wide">
                    Rasa Tenang Dimulai Dari Keuangan yang Tertata.
                </h1>
                <p class="font-sans text-sm sm:text-base font-light text-[#FFF5EA]/90 leading-relaxed max-w-md">
                    Flustra membantu Anda membangun kebiasaan finansial yang lebih baik — dengan pengalaman yang simpel dan elegan.
                </p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 md:p-16 flex flex-col justify-center bg-[#FFFDF9]">
            <div>
                <!-- Branding or minimal back button on mobile if needed -->
                <h2 class="font-serif text-3xl font-semibold text-[#8F3E0A] mb-2 tracking-wide">
                    Selamat Datang
                </h2>
                <p class="text-[#7A6E67] font-sans text-sm mb-8 leading-relaxed">
                    Silakan masuk ke akun Flustra Keuangan Anda.
                </p>
            </div>

            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-xs font-sans">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-[#8C827A] tracking-[0.1em] mb-2">EMAIL</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-[#BFB6AE]">
                            <!-- SVG User/Envelope Outline Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                            placeholder="Masukkan email Anda" 
                            class="w-full bg-white border border-[#EADFCF] rounded-xl py-3.5 pl-11 pr-4 text-[#4A403A] text-sm placeholder-[#BFB6AE] focus:outline-none focus:border-[#B35412] focus:ring-2 focus:ring-[#B35412]/20 transition-all duration-200 shadow-sm" />
                    </div>
                    @if ($errors->has('email'))
                        <p class="text-red-500 text-xs mt-2 font-sans">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="text-[11px] font-bold text-[#8C827A] tracking-[0.1em]">KATA SANDI</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-[#B35412] hover:text-[#9C430B] transition-colors font-sans">
                                Lupa Kata Sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-[#BFB6AE]">
                            <!-- SVG Lock Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                            placeholder="••••••••" 
                            class="w-full bg-white border border-[#EADFCF] rounded-xl py-3.5 pl-11 pr-11 text-[#4A403A] text-sm placeholder-[#BFB6AE] focus:outline-none focus:border-[#B35412] focus:ring-2 focus:ring-[#B35412]/20 transition-all duration-200 shadow-sm" />
                        
                        <!-- Toggle Show Password -->
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon')" class="absolute right-4 text-[#BFB6AE] hover:text-[#B35412] transition-colors focus:outline-none">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" class="eye-open" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="eye-open" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" class="eye-closed hidden" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <p class="text-red-500 text-xs mt-2 font-sans">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Consent Box / Remember Me -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="remember_me" name="remember" type="checkbox" 
                            class="w-4.5 h-4.5 rounded border-[#EADFCF] text-[#B35412] focus:ring-[#B35412]/30 bg-white transition duration-150 cursor-pointer" />
                    </div>
                    <label for="remember_me" class="ml-3 text-xs text-[#7A6E67] leading-relaxed cursor-pointer font-sans">
                        Saya menyetujui <a href="#" class="font-bold text-[#B35412] hover:text-[#9C430B] transition-colors">Syarat dan Ketentuan</a> serta <a href="#" class="font-bold text-[#B35412] hover:text-[#9C430B] transition-colors">Kebijakan Privasi</a> Flustra Financial.
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                        class="w-full bg-[#B35412] hover:bg-[#9C430B] text-white font-semibold py-3.5 px-4 rounded-xl shadow-[0_4px_12px_rgba(179,84,18,0.25)] hover:shadow-[0_6px_16px_rgba(179,84,18,0.35)] focus:outline-none focus:ring-2 focus:ring-[#B35412]/50 active:scale-[0.98] transition-all duration-150 text-center text-sm font-sans">
                        Masuk
                    </button>
                </div>
            </form>

            <!-- Bottom Navigation -->
            <p class="text-center text-xs sm:text-sm text-[#7A6E67] font-sans mt-8">
                Belum memiliki akun? <a href="{{ route('register') }}" class="font-bold text-[#B35412] hover:text-[#9C430B] hover:underline transition-all">Daftar Sekarang</a>
            </p>

            <!-- Carousel Indicators -->
            <div class="flex items-center justify-center space-x-2.5 mt-8">
                <span class="w-2.5 h-2.5 rounded-full bg-[#B35412] transition-colors duration-300"></span>
                <span class="w-2 h-2 rounded-full bg-[#EADFCF] hover:bg-[#B35412]/40 transition-colors duration-300 cursor-pointer"></span>
                <span class="w-2 h-2 rounded-full bg-[#EADFCF] hover:bg-[#B35412]/40 transition-colors duration-300 cursor-pointer"></span>
            </div>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.querySelectorAll('.eye-open').forEach(el => el.classList.add('hidden'));
                eyeIcon.querySelectorAll('.eye-closed').forEach(el => el.classList.remove('hidden'));
            } else {
                passwordInput.type = 'password';
                eyeIcon.querySelectorAll('.eye-open').forEach(el => el.classList.remove('hidden'));
                eyeIcon.querySelectorAll('.eye-closed').forEach(el => el.classList.add('hidden'));
            }
        }
    </script>
</x-guest-layout>
