@props(['label', 'for'])

<div class="space-y-1">
    <x-auth.forms.label :for="$for" :value="$label" />

    {{ $slot }}

    <x-auth.forms.error :for="$for" />
</div>