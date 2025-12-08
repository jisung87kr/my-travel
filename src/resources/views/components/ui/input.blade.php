{{--
    Input Component

    Props:
    - label: string (optional)
    - name: string (required)
    - type: 'text' | 'email' | 'password' | 'number' | etc (default: 'text')
    - error: string (optional) - error message to display
    - placeholder: string (optional)
    - value: string (optional)
    - required: boolean (default: false)
    - disabled: boolean (default: false)
    - icon: string (optional) - icon slot name

    Usage:
    <x-ui.input label="이메일" type="email" name="email" :error="$errors->first('email')" />
--}}

@props([
    'label' => null,
    'name',
    'type' => 'text',
    'error' => null,
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
])

@php
$hasError = !empty($error);
$inputClasses = 'block w-full px-4 py-3 text-base border rounded-xl transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-pink-500/20 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed';

if ($hasError) {
    $inputClasses .= ' border-red-300 text-red-900 placeholder-red-300 focus:border-red-500';
} else {
    $inputClasses .= ' border-gray-200 focus:border-pink-500 placeholder-gray-400';
}
@endphp

<div {{ $attributes->only('class') }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-pink-500" aria-label="required">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if(isset($icon))
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                {{ $icon }}
            </div>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->except(['class', 'label', 'error'])->merge(['class' => $inputClasses . (isset($icon) ? ' pl-11' : '')]) }}
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            @if($hasError) aria-describedby="{{ $name }}-error" @endif
        />
    </div>

    @if($hasError)
        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1" id="{{ $name }}-error" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
