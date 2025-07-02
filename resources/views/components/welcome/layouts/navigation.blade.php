<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo & Menu Desktop --}}
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}">
                    <x-users.application-logo class="h-9 w-auto text-gray-800" />
                </a>

                {{-- Menu (Desktop) --}}
                <div class="hidden sm:flex space-x-6 text-sm font-medium text-gray-600">
                    <x-welcome.navigation.nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Beranda
                    </x-welcome.navigation.nav-link>
                    <x-welcome.navigation.nav-link :href="route('about')" :active="request()->routeIs('about')">
                        Tentang
                    </x-welcome.navigation.nav-link>
                </div>
            </div>

            {{-- Auth Action --}}
            <div class="hidden sm:flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 transition">
                        Login
                    </a>
                @endauth
            </div>

            {{-- Hamburger Button --}}
            <div class="sm:hidden">
                <button @click="open = ! open"
                        class="p-2 text-gray-600 rounded hover:bg-gray-100 focus:outline-none focus:ring">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menu Mobile --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1 px-4 text-sm text-gray-700">
            <x-welcome.navigation.mobile-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Beranda
            </x-welcome.navigation.mobile-nav-link>
            <x-welcome.navigation.mobile-nav-link :href="route('about')" :active="request()->routeIs('about')">
                Tentang
            </x-welcome.navigation.mobile-nav-link>
        </div>

        <div class="border-t border-gray-200 pt-4 pb-1 px-4 text-sm">
            @auth
                <div class="mb-2">
                    <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <x-welcome.navigation.mobile-nav-link :href="route('dashboard')">
                    Dashboard
                </x-welcome.navigation.mobile-nav-link>
            @else
                <x-welcome.navigation.mobile-nav-link :href="route('login')">
                    Login
                </x-welcome.navigation.mobile-nav-link>
            @endauth
        </div>
    </div>
</nav>
