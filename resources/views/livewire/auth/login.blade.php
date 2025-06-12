<div class="bg-white p-8 rounded-lg shadow-md">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Welcome Back</h1>
        <p class="text-gray-600 mt-2">Sign in to your account</p>
    </div>

    <form wire:submit.prevent="login" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input 
                wire:model="email"
                type="email"
                id="email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
                autofocus
            >
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input 
                wire:model="password"
                type="password"
                id="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    wire:model="remember"
                    id="remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>

            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                Forgot password?
            </a>
        </div>

        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Sign in
        </button>
    </form>

    @if (Route::has('register'))
    <div class="mt-4 text-center text-sm text-gray-600">
        Don't have an account? 
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
            Sign up
        </a>
    </div>
    @endif
</div>