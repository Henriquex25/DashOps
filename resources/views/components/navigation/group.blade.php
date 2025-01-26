@props([
    'label' => null,
])

<li
    class="flex flex-col gap-y-1"
    x-data="{
        open: true,
        toggleCollapsedGroup() {
            this.open = !this.open;
        }
    }"
>

    {{-- Label --}}
    @if($label)
        <div
            class="flex items-center gap-x-3 px-2 py-2 cursor-pointer"
            x-transition:enter="delay-100 lg:transition"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-on:click="toggleCollapsedGroup()"
        >

            <span class="flex-1 text-sm font-medium leading-6 text-gray-400">
                {{ $label }}
            </span>

            <button
                class="relative flex items-center justify-center rounded-lg outline-none transition duration-75 focus-visible:ring-2 -m-2 h-9 w-9 text-gray-500 hover:text-gray-400 focus-visible:ring-primary-500"
                type="button"
                title="{{ $label }}"
                wire:loading.attr="disabled"
                x-on:click.stop="toggleCollapsedGroup()"
            >
                <span class="sr-only">
                    {{ $label }}
                </span>

                <svg
                    class="h-5 w-5 transform transition-transform"
                    :class="{ '-rotate-180': !open }"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                    data-slot="icon"
                >
                    <path
                        fill-rule="evenodd"
                        d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z"
                        clip-rule="evenodd"
                    ></path>
                </svg>

            </button>
        </div>
    @endif

    {{-- Items --}}
    <ul
        class="flex flex-col gap-y-1"
        x-collapse.duration.200ms
        x-transition:enter="delay-100 lg:transition"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-show="open"
        style="height: auto;"
    >
        {{ $slot }}
    </ul>
</li>
