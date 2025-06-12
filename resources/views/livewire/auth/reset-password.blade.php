<div class="auth-container dark:auth-dark">
    <div class="auth-card dark:auth-dark">
        <div class="auth-text-center mb-8">
            <h2 class="auth-title dark:auth-dark">Reset Password</h2>
            <p class="auth-subtitle dark:auth-dark">Silakan masukkan password baru Anda</p>
        </div>

        @if (session('status'))
            <div class="auth-status dark:auth-dark">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="resetPassword" class="auth-form">
            <!-- Email -->
            <div class="auth-form-group">
                <label for="email" class="auth-label dark:auth-dark">Email</label>
                <input wire:model="email" type="email" id="email" required autocomplete="email"
                       class="auth-input dark:auth-dark" />
                @error('email') <span class="auth-error">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="auth-form-group">
                <label for="password" class="auth-label dark:auth-dark">Password Baru</label>
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
                @error('password_confirmation') <span class="auth-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="auth-btn auth-btn-primary">
                Reset Password
            </button>
        </form>
    </div>
</div>