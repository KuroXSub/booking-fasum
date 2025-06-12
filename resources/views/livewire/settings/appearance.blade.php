<section class="settings-container">
    <div class="settings-header">
        <h1 class="settings-title">Appearance Settings</h1>
        <p class="settings-subtitle">Customize how the application looks on your device</p>
    </div>

    <div class="settings-grid">
        <!-- Sidebar Navigation -->
        <div>
            <x-settings.navigation active="appearance" />
        </div>

        <!-- Main Content -->
        <div>
            <div class="settings-card">
                <h2 class="settings-card-title">Theme Preferences</h2>
                
                <div class="settings-theme-selector">
                    <button x-on:click="$flux.appearance = 'light'"
                        :class="{ 'settings-theme-option-active': $flux.appearance === 'light' }"
                        class="settings-theme-option">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="settings-theme-icon text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">Light</span>
                        </div>
                    </button>

                    <button x-on:click="$flux.appearance = 'dark'"
                        :class="{ 'settings-theme-option-active': $flux.appearance === 'dark' }"
                        class="settings-theme-option">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="settings-theme-icon text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <span class="font-medium">Dark</span>
                        </div>
                    </button>

                    <button x-on:click="$flux.appearance = 'system'"
                        :class="{ 'settings-theme-option-active': $flux.appearance === 'system' }"
                        class="settings-theme-option">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="settings-theme-icon text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">System</span>
                        </div>
                    </button>
                </div>

                <p class="settings-subtitle mt-4">
                    The "System" option will automatically switch between light and dark based on your device's preferences.
                </p>
            </div>
        </div>
    </div>
</section>