@section('title', 'Masuk ke Akun Anda')

<div>
    <x-auth.background />

    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        {{-- Logo --}}
        <div class="mb-6">
            <a href="{{ route('home') }}">
                <x-users.application-logo class="w-20 h-20 text-indigo-600" />
            </a>
        </div>

        {{-- Kartu Form Login --}}
        <x-auth.card>
            {{-- Judul --}}
            <x-auth.text.header 
                title="Masuk ke Akun Anda" 
                subtitle="Selamat datang kembali di Sistem Peminjaman Fasilitas Desa" />

            {{-- Form --}}
            <form wire:submit.prevent="authenticate" class="mt-6 space-y-5">

                {{-- Alamat Email --}}
                <x-auth.forms.input-group label="Alamat Email" for="email">
                    <x-auth.forms.text-input 
                        wire:model.lazy="email" 
                        id="email" 
                        type="email" 
                        required 
                        autofocus />
                </x-auth.forms.input-group>

                {{-- Kata Sandi --}}
                <x-auth.forms.input-group label="Kata Sandi" for="password">
                    <x-auth.forms.text-input 
                        wire:model.lazy="password" 
                        id="password" 
                        type="password" 
                        required />
                </x-auth.forms.input-group>

                {{-- Ingat Saya & Lupa Sandi --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input 
                            wire:model.lazy="remember" 
                            id="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-gray-700">Ingat saya</span>
                    </label>

                    <x-auth.text.link 
                        href="{{ route('password.request') }}" 
                        text="Lupa kata sandi?" />
                </div>

                {{-- Tombol Masuk --}}
                <div>
                    <x-auth.forms.primary-button>
                        Masuk
                    </x-auth.forms.primary-button>
                </div>
            </form>

            {{-- Atau dengan Google --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white px-2 text-gray-500">Atau masuk dengan</span>
                </div>
            </div>

            <div>
                <x-auth.forms.google-button />
            </div>

            {{-- Link ke register --}}
            <div class="mt-6 text-center">
                <x-auth.text.link 
                    href="{{ route('register') }}" 
                    text="Belum punya akun? Daftar sekarang" />
            </div>
        </x-auth.card>
    </div>
</div>
