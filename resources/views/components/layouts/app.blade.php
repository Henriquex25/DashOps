<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-[#2c313c]">

    <livewire:navigation-bar />

    <main class="pl-[20.4rem] w-full px-4 max-h-full overflow-y-auto text-gray-300 mx-auto md:px-6 lg:px-8 max-w-7xl">
        {{ $slot }}
    </main>

    @livewireScriptConfig
    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
