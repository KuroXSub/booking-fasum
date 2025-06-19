@section('title', 'Login to your account')

<div>
    <x-auth.background />
    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        <div class="mb-8">
            <a href="{{ route('home') }}">
                <x-users.application-logo class="w-24 h-24" />
            </a>
        </div>

        <x-auth.card>
            <x-auth.text.header title="Sign in to your account" subtitle="Welcome back!" />
            
            <form wire:submit.prevent="authenticate" class="mt-8 space-y-6">
                
                {{-- Email Address --}}
                <x-auth.forms.input-group label="Email Address" for="email">
                    <x-auth.forms.text-input wire:model.lazy="email" id="email" type="email" required autofocus />
                </x-auth.forms.input-group>

                {{-- Password --}}
                <x-auth.forms.input-group label="Password" for="password">
                    <x-auth.forms.text-input wire:model.lazy="password" id="password" type="password" required />
                </x-auth.forms.input-group>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model.lazy="remember" id="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>

                    <x-auth.text.link href="{{ route('password.request') }}" text="Forgot your password?" />
                </div>

                {{-- Submit Button --}}
                <div>
                    <x-auth.forms.primary-button>
                        Sign In
                    </x-auth.forms.primary-button>
                </div>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div>
                <x-auth.forms.google-button />
            </div>

            <div class="mt-6">
                <x-auth.text.link href="{{ route('register') }}" text="Don't have an account? Sign up" />
            </div>
        </x-auth.card>
    </div>
</div>