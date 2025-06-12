@props(['title', 'subtitle'])

<div class="bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
    @if($subtitle)
    <p class="mt-1 text-gray-600">{{ $subtitle }}</p>
    @endif
</div>