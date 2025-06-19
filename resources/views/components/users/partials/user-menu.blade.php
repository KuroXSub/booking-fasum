<div class="ml-3 relative" x-data="{ open: false }"> {{-- x-data="{ open: false }" is moved here --}}
    <button @click="open = !open" class="flex items-center space-x-2">
        <span class="sr-only">Open user menu</span>
        <img class="h-8 w-8 rounded-full" 
             src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" 
             alt="{{ Auth::user()->name }}">
        <span class="hidden md:inline text-sm font-medium text-gray-700">
            {{ Auth::user()->name }}
        </span>
    </button>

    <div x-show="open" 
         @click.away="open = false" 
         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95">
        <div class="py-1">
            <a href="#" {{-- Assuming a profile edit route --}}
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                <x-users.ui.icons.user class="h-4 w-4 mr-2" /> {{-- Using your user icon --}}
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                    <x-users.ui.icons.logout class="h-4 w-4 mr-2" /> {{-- Using your logout icon --}}
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>