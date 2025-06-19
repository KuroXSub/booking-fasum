@props(['href', 'text'])

<div class="text-sm text-center">
    <a href="{{ $href }}" class="font-medium text-indigo-600 hover:text-indigo-500">
        {{ $text }}
    </a>
</div>