<nav class="bg-white shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex-shrink-0">
                    <x-welcome.navigation.application-logo class="h-10 w-auto" />
                </a>
            </div>

            <!-- Desktop Menu (Hidden di Mobile) -->
            <div class="hidden sm:flex sm:space-x-8">
                <x-welcome.navigation.nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                    Beranda
                </x-welcome.navigation.nav-link>
                <x-welcome.navigation.nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                    Tentang
                </x-welcome.navigation.nav-link>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Buka menu utama</span>
                    <!-- Icon Menu (Ketika menu tertutup) -->
                    <svg x-show="!open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Icon Close (Ketika menu terbuka) -->
                    <svg x-show="open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (Toggle dengan Alpine.js) -->
    <div x-show="open" x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95" 
         class="sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <x-welcome.navigation.mobile-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                Beranda
            </x-welcome.navigation.mobile-nav-link>
            <x-welcome.navigation.mobile-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                Tentang
            </x-welcome.navigation.mobile-nav-link>
        </div>
    </div>
</nav>