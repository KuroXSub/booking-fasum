<section class="settings-container">
    <div class="settings-header">
        <h1 class="settings-title">Account Security</h1>
        <p class="settings-subtitle">Manage your password and security settings</p>
    </div>

    <div class="settings-grid">
        <!-- Sidebar Navigation -->
        <div>
            <x-settings.navigation active="password" />
        </div>

        <!-- Main Content -->
        <div>
            <div class="settings-card">
                <h2 class="settings-card-title">Change Password</h2>
                
                <form wire:submit="updatePassword" class="settings-form">
                    <div class="settings-form-group">
                        <label for="current_password" class="settings-label">Current Password</label>
                        <input wire:model="current_password" type="password" id="current_password" required
                            class="settings-input">
                        @error('current_password')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="settings-form-group">
                        <label for="password" class="settings-label">New Password</label>
                        <input wire:model="password" type="password" id="password" required
                            class="settings-input">
                        @error('password')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="settings-form-group">
                        <label for="password_confirmation" class="settings-label">Confirm New Password</label>
                        <input wire:model="password_confirmation" type="password" id="password_confirmation" required
                            class="settings-input">
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <x-action-message class="settings-action-message" on="password-updated">
                            {{ __('Password updated successfully!') }}
                        </x-action-message>
                        
                        <button type="submit" class="settings-button settings-button-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>