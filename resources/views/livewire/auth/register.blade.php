@section('title', 'Buat Akun Baru')

<div>
    <x-auth.background />

    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-12">
        {{-- Logo --}}
        <div class="mb-6">
            <a href="{{ route('home') }}">
                <x-users.application-logo class="w-20 h-20 text-indigo-600" />
            </a>
        </div>

        {{-- Kartu Form Register --}}
        <x-auth.card>
            {{-- Judul --}}
            <x-auth.text.header 
                title="Daftar Akun Baru" 
                subtitle="Bergabunglah dan mulai meminjam fasilitas desa" />

            {{-- Form --}}
            <form wire:submit.prevent="register" class="mt-6 space-y-5">

                {{-- Nama Lengkap --}}
                <x-auth.forms.input-group label="Nama Lengkap" for="name">
                    <x-auth.forms.text-input 
                        wire:model.lazy="name" 
                        id="name" 
                        type="text" 
                        required 
                        autofocus />
                </x-auth.forms.input-group>

                {{-- Email --}}
                <x-auth.forms.input-group label="Alamat Email" for="email">
                    <x-auth.forms.text-input 
                        wire:model.lazy="email" 
                        id="email" 
                        type="email" 
                        required />
                </x-auth.forms.input-group>

                {{-- Kata Sandi --}}
                <x-auth.forms.input-group label="Kata Sandi" for="password">
                    <x-auth.forms.text-input 
                        wire:model.lazy="password" 
                        id="password" 
                        type="password" 
                        required />
                </x-auth.forms.input-group>

                {{-- Konfirmasi Sandi --}}
                <x-auth.forms.input-group label="Konfirmasi Kata Sandi" for="password_confirmation">
                    <x-auth.forms.text-input 
                        wire:model.lazy="password_confirmation" 
                        id="password_confirmation" 
                        type="password" 
                        required />
                </x-auth.forms.input-group>

                {{-- Tombol Daftar --}}
                <div>
                    <x-auth.forms.primary-button>
                        Daftar
                    </x-auth.forms.primary-button>
                </div>
            </form>

            {{-- Atau Lanjutkan Dengan Google --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="bg-white px-2 text-gray-500">Atau daftar dengan</span>
                </div>
            </div>

            <div>
                <x-auth.forms.google-button />
            </div>

            {{-- Link ke Login --}}
            <div class="mt-6 text-center">
                <x-auth.text.link 
                    href="{{ route('login') }}" 
                    text="Sudah punya akun? Masuk di sini" />
            </div>
        </x-auth.card>
    </div>
</div>
