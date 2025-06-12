<div class="auth-container dark:auth-dark">
    <div class="auth-card dark:auth-dark">
        <div class="auth-text-center mb-8">
            <h2 class="auth-title dark:auth-dark">Buat Akun Baru</h2>
            <p class="auth-subtitle dark:auth-dark">Masukkan data Anda untuk mendaftar</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="auth-status dark:auth-dark">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="register" class="auth-form">
            <!-- Nama -->
            <div class="auth-form-group">
                <label for="name" class="auth-label dark:auth-dark">Nama Lengkap</label>
                <input wire:model="name" type="text" id="name" autocomplete="name" required
                       placeholder="Nama lengkap"
                       class="auth-input dark:auth-dark" />
                @error('name') <span class="auth-error">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div class="auth-form-group">
                <label for="email" class="auth-label dark:auth-dark">Email</label>
                <input wire:model="email" type="email" id="email" autocomplete="email" required
                       placeholder="email@example.com"
                       class="auth-input dark:auth-dark" />
                @error('email') <span class="auth-error">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="auth-form-group">
                <label for="password" class="auth-label dark:auth-dark">Password</label>
                <input wire:model="password" type="password" id="password" autocomplete="new-password" required
                       placeholder="••••••••"
                       class="auth-input dark:auth-dark" />
                @error('password') <span class="auth-error">{{ $message }}</span> @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="auth-form-group">
                <label for="password_confirmation" class="auth-label dark:auth-dark">Konfirmasi Password</label>
                <input wire:model="password_confirmation" type="password" id="password_confirmation" autocomplete="new-password" required
                       placeholder="Ulangi password"
                       class="auth-input dark:auth-dark" />
            </div>

            <!-- Tombol Daftar -->
            <button type="submit"
                    class="auth-btn auth-btn-primary">
                Daftar
            </button>
        </form>

        <!-- Divider -->
        <div class="auth-divider">
            <div class="auth-divider-line dark:auth-dark"></div>
            <span class="auth-divider-text dark:auth-dark">atau</span>
            <div class="auth-divider-line dark:auth-dark"></div>
        </div>

        <!-- Google Button -->
        <a href="{{ route('google.redirect') }}"
           class="auth-btn auth-btn-secondary dark:auth-dark flex items-center justify-center gap-3">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.545 10.239v3.821h5.445c-0.712 2.315-2.647 3.972-5.445 3.972-3.332 0-6.033-2.701-6.033-6.032s2.701-6.032 6.033-6.032c1.498 0 2.866 0.549 3.921 1.453l2.814-2.814c-1.784-1.664-4.152-2.675-6.735-2.675-5.522 0-10 4.479-10 10s4.478 10 10 10c8.396 0 10-7.524 10-10 0-0.61-0.056-1.201-0.158-1.768h-9.842z"/>
            </svg>
            Lanjutkan dengan Google
        </a>

        <!-- Link Login -->
        <p class="auth-text-sm auth-text-gray dark:auth-dark auth-text-center mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" wire:navigate
               class="auth-link dark:auth-dark">
                Masuk di sini
            </a>
        </p>
    </div>
</div>