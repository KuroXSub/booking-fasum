<div class="auth-container dark:auth-dark">
    <div class="auth-card dark:auth-dark">
        <div class="auth-text-center mb-8">
            <h2 class="auth-title dark:auth-dark">Konfirmasi Password</h2>
            <p class="auth-subtitle dark:auth-dark">
                Area ini aman. Silakan masukkan password Anda sebelum melanjutkan.
            </p>
        </div>

        @if (session('status'))
            <div class="auth-status dark:auth-dark">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="confirmPassword" class="auth-form">
            <div class="auth-form-group">
                <label for="password" class="auth-label dark:auth-dark">Password</label>
                <input wire:model="password" type="password" id="password" required
                       placeholder="••••••••"
                       class="auth-input dark:auth-dark" />
                @error('password') <span class="auth-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="auth-btn auth-btn-primary">
                Konfirmasi
            </button>
        </form>
    </div>
</div>