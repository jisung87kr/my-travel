{{--
    Dropdown Component (Alpine.js required)

    Props:
    - align: 'left' | 'right' (default: 'left')
    - width: 'w-48' | 'w-56' | 'w-64' (default: 'w-48')

    Slots:
    - trigger: Dropdown trigger button
    - content: Dropdown menu content

    Usage:
    <x-ui.dropdown>
        <x-slot name="trigger">
            <button>메뉴</button>
        </x-slot>

        <x-slot name="content">
            <a href="#">항목 1</a>
            <a href="#">항목 2</a>
        </x-slot>
    </x-ui.dropdown>
--}}

@props([
    'align' => 'left',
    'width' => 'w-48',
])

@php
$alignmentClasses = [
    'left' => 'origin-top-left left-0',
    'right' => 'origin-top-right right-0',
];
@endphp

<div
    x-data="{
        open: false,
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    this.$refs.menu.focus();
                });
            }
        },
        close(focusAfter) {
            this.open = false;
            if (focusAfter) {
                focusAfter.focus();
            }
        }
    }"
    @keydown.escape.prevent.stop="close($refs.button)"
    @focusin.window="! $refs.menu.contains($event.target) && close()"
    x-id="['dropdown-button']"
    class="relative"
>
    <div @click="toggle()" x-ref="button" :aria-expanded="open" :aria-controls="$id('dropdown-button')">
        {{ $trigger }}
    </div>

    <div
        x-ref="menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        :id="$id('dropdown-button')"
        @click.outside="close($refs.button)"
        class="absolute z-50 mt-2 {{ $width }} rounded-xl bg-white shadow-dropdown py-1 focus:outline-none"
        {{ $attributes->merge(['class' => $alignmentClasses[$align]]) }}
        style="display: none;"
        tabindex="-1"
        role="menu"
        aria-orientation="vertical"
    >
        <div class="py-1" role="none">
            {{ $content }}
        </div>
    </div>
</div>
