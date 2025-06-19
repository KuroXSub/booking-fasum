@props(['align' => 'right'])

@php
    $alignmentClasses = [
        'right' => 'origin-top-right right-0',
        'left' => 'origin-top-left left-0',
    ][$align];
@endphp

<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    @if(isset($trigger))
        <div @click="open = !open">
            {{ $trigger }}
        </div>
    @endif

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none {{ $alignmentClasses }}">
        @if(isset($content))
            {{ $content }}
        @endif
    </div>
</div>