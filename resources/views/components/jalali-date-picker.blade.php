@php
    $id = $getId();
    $statePath = $getStatePath();
    $displayFormat = $getDisplayFormat() ?? 'Y/m/d';
    $placeholder = $getPlaceholder();
    $isDisabled = $isDisabled();
    $isRequired = $isRequired();
    $extraAttributes = $getExtraInputAttributes();
@endphp

<x-filament::input.wrapper
    :valid="! $errors->has($statePath)"
    :attributes="\Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())"
>
    <div
        x-data="jalaliDatePickerComponent({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            displayFormat: @js($displayFormat),
            placeholder: @js($placeholder),
            disabled: @js($isDisabled),
            required: @js($isRequired),
        })"
        class="fi-input-wrp"
        dir="rtl"
    >
        <div class="relative">
            <input
                type="text"
                x-ref="input"
                :value="displayValue"
                @input="handleInput($event)"
                :placeholder="placeholder"
                :disabled="disabled"
                :required="required"
                class="fi-input block w-full rounded-lg border-none bg-white px-3 py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-2 focus:ring-primary-500 disabled:bg-gray-50 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:bg-white/5 dark:text-white dark:placeholder:text-gray-500 dark:focus:ring-primary-400 dark:disabled:bg-transparent dark:disabled:text-gray-400"
                {!! $extraAttributes ? \Filament\Support\prepare_inherited_attributes($extraAttributes)->merge($getExtraInputAttributes()) : $getExtraInputAttributes() !!}
            />
            <button
                type="button"
                @click="toggleCalendar"
                class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                :disabled="disabled"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </button>
        </div>

        <div
            x-show="showCalendar"
            @click.away="showCalendar = false"
            x-cloak
            class="absolute z-50 mt-2 rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
            style="display: none;"
        >
            <div class="p-4" x-html="calendarHTML"></div>
        </div>
    </div>
</x-filament::input.wrapper>

@once
    @push('scripts')
        <script src="{{ asset('vendor/filament-persian-suite/js/jalali-date-picker.js') }}" defer></script>
    @endpush
@endonce

