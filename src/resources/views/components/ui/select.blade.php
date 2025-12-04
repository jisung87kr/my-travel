{{--
    Select Component

    Props:
    - label: string (optional)
    - name: string (required)
    - error: string (optional) - error message to display
    - required: boolean (default: false)
    - disabled: boolean (default: false)
    - placeholder: string (optional)

    Usage:
    <x-ui.select label="국가" name="country" :error="$errors->first('country')">
        <option value="">선택하세요</option>
        <option value="kr">대한민국</option>
        <option value="us">미국</option>
    </x-ui.select>
--}}

@props([
    'label' => null,
    'name',
    'error' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
])

@php
$hasError = !empty($error);
$selectClasses = 'block w-full px-4 py-2.5 text-base border rounded-xl transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed appearance-none bg-white';

if ($hasError) {
    $selectClasses .= ' border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500';
} else {
    $selectClasses .= ' border-gray-300 focus:ring-primary-500 focus:border-primary-500';
}
@endphp

<div {{ $attributes->only('class') }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-red-500" aria-label="required">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'label', 'error'])->merge(['class' => $selectClasses]) }}
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            @if($hasError) aria-describedby="{{ $name }}-error" @endif
        >
            @if($placeholder)
                <option value="" disabled selected>{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    @if($hasError)
        <p class="mt-1.5 text-sm text-red-600" id="{{ $name }}-error" role="alert">
            {{ $error }}
        </p>
    @endif
</div>
