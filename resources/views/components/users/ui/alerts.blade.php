{{-- File: resources/views/components/dashboard/ui/alerts.blade.php --}}

{{-- Menggunakan Alpine.js untuk membuat notifikasi bisa ditutup --}}
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
    @if (session('success'))
        <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-gray-800 dark:text-green-400" role="alert">
            <x-heroicon-s-check-circle class="flex-shrink-0 inline w-5 h-5 mr-3"/>
            <span class="font-medium">Sukses!</span>&nbsp;{{ session('success') }}
            <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5">
                 <x-heroicon-s-x-mark class="w-5 h-5"/>
            </button>
        </div>
    @endif

    @if (session('error'))
         <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
            <x-heroicon-s-x-circle class="flex-shrink-0 inline w-5 h-5 mr-3"/>
            <span class="font-medium">Error!</span>&nbsp;{{ session('error') }}
             <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5">
                 <x-heroicon-s-x-mark class="w-5 h-5"/>
            </button>
        </div>
    @endif
</div>