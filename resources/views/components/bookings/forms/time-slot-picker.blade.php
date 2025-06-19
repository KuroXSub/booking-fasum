{{-- Available Time Slots --}}
<div x-show="availableStartHours.length > 0" class="mt-2">
    <p class="text-sm font-medium text-gray-700 mb-1">Slot waktu tersedia:</p>
    <div class="flex flex-wrap gap-2">
        <template x-for="hour in availableStartHours" :key="hour.value">
            <span @click="selectStartTime(hour.value)"
                class="px-3 py-1 rounded-full text-xs font-medium cursor-pointer"
                :class="{
                    'bg-green-100 text-green-800 hover:bg-green-200': !hour.disabled,
                    'bg-red-100 text-red-800 line-through': hour.disabled,
                    'bg-indigo-100 text-indigo-800': hour.value === startTime && !hour.disabled
                }"
                x-text="hour.label + (hour.disabled ? ' (Penuh)' : '')">
            </span>
        </template>
    </div>
</div>