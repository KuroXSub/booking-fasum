{{-- resources/views/components/bookings/forms/feedback-box.blade.php --}}
@props(['feedback'])

<div x-show="{{ $feedback }}" class="mt-2 p-2 rounded text-sm"
    :class="({{ $feedback }})?.type === 'error' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'">
    <p x-text="({{ $feedback }})?.message"></p>
</div>