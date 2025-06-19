{{-- File: resources/views/components/dashboard/ui/validation-errors.blade.php --}}
@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'p-4 bg-red-50 border border-red-200 rounded-lg']) }}>
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <x-heroicon-s-x-circle class="w-5 h-5 text-red-400"/>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Terdapat {{ $errors->count() }} kesalahan pada input Anda:
                </h3>
            </div>
        </div>
        <div class="mt-2 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif