<div class="w-full h-full">
    <div class="sticky top-0 z-20 overflow-x-clip w-full">
        <div class="w-full h-16 bg-[#21252b] flex flex-row">
            <div class="w-80 flex items-center px-6 justify-between">
                <a href="{{ route('dashboard') }}">
                    <h2 class="text-primary-500 font-bold text-xl">DashOps</h2>
                </a>

                {{-- <button class="text-gray-500 hover:text-gray-400 transition duration-100"> --}}
                {{--     <x-heroicon-o-chevron-left class="h-6 w-6" /> --}}
                {{-- </button> --}}
            </div>

            <nav class="flex-1 flex justify-end items-center px-8">
                <button class="shrink-0">
                    <img class="object-cover object-center rounded-full h-8 w-8" src="https://ui-avatars.com/api/?name=D+U&amp;color=FFFFFF&amp;background=09090b" alt="Avatar of Demo User">
                </button>
            </nav>
        </div>
    </div>

    <nav class="w-80 min-w-34 fixed top-16 left-0 h-screen bg-[#21252b] border-t border-white/10 px-6 py-8">
        <ul class="text-gray-400">
            <li>Dashboard</li>
            <li>Projetos</li>
        </ul>
    </nav>

    <main class="pl-[20.4rem] w-full px-2 max-h-full overflow-y-auto text-gray-300">
        {{ $slot }}
    </main>
</div>
