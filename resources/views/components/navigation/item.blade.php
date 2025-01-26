@props([
    'href' => '#',
    'label' => '',
    'icon' => '',
])
<li>
    <a
        href="{{ $href }}"
        class="relative flex items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-white/5 focus-visible:bg-white/5 text-gray-300 group"
        :class="href && currentRoute.includes(href) ? 'bg-white/5' : 'bg-[#21252b]'"
        x-data="{ href: '{{ $href }}', currentRoute: window.location.href }"
        @if($href)
        wire:navigate
        @endif
    >
        @if($icon)
        <x-dynamic-component
            :component="$icon" class="h-6 w-6"
            ::class="href && currentRoute.includes(href) ? 'text-primary-500' : 'text-gray-500'"
        />
        @endif

        @if($label)
        <span
            class="flex-1 truncate text-sm font-medium text-gray-200"
            :class="href && currentRoute.includes(href) ? 'text-primary-500' : 'text-gray-200'"
        >
            {{ $label }}
        </span>
        @endif

        @if($slot)
        <span
            class="flex-1 truncate text-sm font-medium text-gray-200"
            :class="href && currentRoute.includes(href) ? 'text-primary-500' : 'text-gray-200'"
        >
            {{ $slot }}
        </span>
        @endif


    </a>
</li>
