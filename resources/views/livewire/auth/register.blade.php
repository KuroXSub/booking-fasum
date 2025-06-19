@section('title', 'Create a new account')

<div>
    <x-auth.background />
    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-12">
        <div class="mb-8">
            <a href="{{ route('home') }}">
                <x-users.application-logo class="w-24 h-24" />
            </a>
        </div>

        <x-auth.card>
            <x-auth.text.header title="Create an account" subtitle="Join us and start booking facilities" />

            <form wire:submit.prevent="register" class="mt-8 space-y-6">
                {{-- Name --}}
                <x-auth.forms.input-group label="Name" for="name">
                    <x-auth.forms.text-input wire:model.lazy="name" id="name" type="text" required autofocus />
                </x-auth.forms.input-group>

                {{-- Email Address --}}
                <x-auth.forms.input-group label="Email Address" for="email">
                    <x-auth.forms.text-input wire:model.lazy="email" id="email" type="email" required />
                </x-auth.forms.input-group>

                {{-- Password --}}
                <x-auth.forms.input-group label="Password" for="password">
                    <x-auth.forms.text-input wire:model.lazy="password" id="password" type="password" required />
                </x-auth.forms.input-group>

                {{-- Confirm Password --}}
                <x-auth.forms.input-group label="Confirm Password" for="password_confirmation">
                    <x-auth.forms.text-input wire:model.lazy="password_confirmation" id="password_confirmation" type="password" required />
                </x-auth.forms.input-group>

                {{-- Submit Button --}}
                <div>
                    <x-auth.forms.primary-button>
                        Register
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
                <x-auth.text.link href="{{ route('login') }}" text="Already have an account? Sign in" />
            </div>
        </x-auth.card>
    </div>
</div>