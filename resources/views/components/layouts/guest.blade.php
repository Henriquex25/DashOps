<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen dark">
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

<body class="antialiased bg-[#1c1f25] min-h-screen font-normal text-white">

    <div class="flex min-h-screen flex-col items-center">
        <div class="flex w-full flex-grow items-center justify-center">
            <main class="my-16 w-full bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-[#282c34] dark:ring-white/10 sm:rounded-xl sm:px-12 max-w-lg">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScriptConfig
    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
