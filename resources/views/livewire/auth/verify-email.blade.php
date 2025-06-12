<div class="auth-container dark:auth-dark">
    <div class="auth-card dark:auth-dark text-center">
        <h2 class="auth-title dark:auth-dark mb-4">Verifikasi Email</h2>
        <p class="auth-subtitle dark:auth-dark mb-6">
            Silakan klik tautan verifikasi yang telah kami kirim ke email Anda.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="auth-status dark:auth-dark mb-6">
                Tautan verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <div class="flex flex-col gap-4">
            <button wire:click="sendVerification"
                    class="auth-btn auth-btn-primary">
                Kirim Ulang Email Verifikasi
            </button>

            <button wire:click="logout"
                    class="auth-text-sm auth-text-gray hover:text-red-600 hover:underline dark:hover:text-red-400 transition-all duration-200">
                Keluar
            </button>
        </div>
    </div>
</div>