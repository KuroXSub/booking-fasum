<section class="settings-container">
    <div class="settings-card">
        <h2 class="settings-card-title">Delete Account</h2>
        <p class="settings-subtitle">Permanently delete your account and all of its data.</p>
        
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="settings-button settings-button-danger">
            Delete Account
        </button>
    </div>

    <div x-data="{ open: @entangle('confirmingUserDeletion') }" x-show="open" class="settings-modal">
        <div class="settings-modal-content">
            <h3 class="settings-modal-title">Are you sure you want to delete your account?</h3>
            <p class="settings-modal-description">
                Once your account is deleted, all of its resources and data will be permanently deleted. 
                Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <form wire:submit="deleteUser" class="settings-form">
                <div class="settings-form-group">
                    <label for="delete_password" class="settings-label">Password</label>
                    <input wire:model="password" type="password" id="delete_password" required
                        class="settings-input">
                    @error('password')
                        <p class="settings-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="settings-modal-actions">
                    <button type="button" x-on:click="open = false"
                        class="settings-button">
                        Cancel
                    </button>

                    <button type="submit"
                        class="settings-button settings-button-danger">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>