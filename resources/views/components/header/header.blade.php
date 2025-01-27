@props([
    'title' => '',
])

<header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    @if($title)
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-primary-500 sm:text-3xl">{{ $title }}</h1>
    </div>
    @endif

    @if($slot)
    {{ $slot }}
    @endif
</header>
