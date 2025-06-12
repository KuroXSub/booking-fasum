<section class="settings-container">
    <div class="settings-header">
        <h1 class="settings-title">Profile Settings</h1>
        <p class="settings-subtitle">Manage your personal information and account preferences</p>
    </div>

    <div class="settings-grid">
        <!-- Sidebar Navigation -->
        <div>
            <x-settings.navigation active="profile" />
        </div>

        <!-- Main Content - Enhanced -->
        <div>
            <div class="settings-card">
                <h2 class="settings-card-title">Personal Information</h2>
                
                <form wire:submit="updateProfileInformation" class="settings-form">
                    <div class="settings-form-group">
                        <label for="name" class="settings-label">Full Name</label>
                        <input wire:model="name" type="text" id="name" required 
                               class="settings-input" placeholder="Enter your full name">
                        @error('name')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="settings-form-group">
                        <label for="email" class="settings-label">Email Address</label>
                        <input wire:model="email" type="email" id="email" required 
                               class="settings-input" placeholder="Enter your email address">
                        @error('email')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <x-action-message class="settings-action-message" on="profile-updated">
                            {{ __('Saved successfully!') }}
                        </x-action-message>
                        
                        <button type="submit" 
                                class="settings-button settings-button-primary">
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>