@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}" @class([
    'relative inline-flex items-center px-3 py-2 text-sm font-medium',
    'text-gray-900 after:absolute after:left-0 after:bottom-0 after:w-full after:h-[2px] after:bg-indigo-500' => $active,
    'text-gray-500 hover:text-gray-700 hover:after:bg-gray-400 hover:after:h-[2px]' => !$active
])>
    {{ $slot }}
</a>
