<div class="auth-container dark:auth-dark">
    <div class="auth-card dark:auth-dark">
        <div class="auth-text-center mb-8">
            <h2 class="auth-title dark:auth-dark">Lupa Password</h2>
            <p class="auth-subtitle dark:auth-dark">Masukkan email Anda untuk mendapatkan tautan reset password.</p>
        </div>

        @if (session('status'))
            <div class="auth-status dark:auth-dark">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="sendPasswordResetLink" class="auth-form">
            <div class="auth-form-group">
                <label for="email" class="auth-label dark:auth-dark">Email</label>
                <input wire:model="email" type="email" id="email" required
                       placeholder="email@example.com"
                       class="auth-input dark:auth-dark" />
                @error('email') <span class="auth-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="auth-btn auth-btn-primary">
                Kirim Tautan Reset
            </button>
        </form>

        <p class="auth-text-sm auth-text-gray dark:auth-dark auth-text-center mt-6">
            Atau kembali ke
            <a href="{{ route('login') }}" wire:navigate
               class="auth-link dark:auth-dark">
                halaman login
            </a>
        </p>
    </div>
</div>