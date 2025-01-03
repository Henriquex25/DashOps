<div
    @project::created.window="$wire.$refresh(); $wire.set('data.project', $event.detail.projectId, false);"
    @project::deleted.window="$wire.set('data.project', null, false); $wire.$refresh();"
>
    <div class="w-full h-full select-none">
        <div class="sticky top-0 z-20 overflow-x-clip w-full">
            <div class="w-full h-16 bg-[#21252b] flex flex-row">

                {{-- Logo --}}
                <div class="w-80 flex items-center px-6 justify-between">
                    <a href="{{ route('dashboard') }}">
                        <h2 class="text-primary-500 font-bold text-xl">DashOps</h2>
                    </a>

                    {{-- Botão de recolher sidebar --}}
                    {{-- <button class="text-gray-500 hover:text-gray-400 transition duration-100"> --}}
                    {{--     <x-heroicon-o-chevron-left class="h-6 w-6" /> --}}
                    {{-- </button> --}}
                </div>

                {{-- Avatar --}}
                <div class="relative flex-1 flex justify-end items-center px-8" x-data>
                    <button class="shrink-0" @click="$refs.panel.toggle">
                        <img
                            class="object-cover object-center rounded-full h-8 w-8"
                            src="https://ui-avatars.com/api/?name=D+U&amp;color=FFFFFF&amp;background=09090b"
                            alt="Avatar of Demo User"
                        >
                    </button>

                    {{-- User Modal Info --}}
                    <div
                        x-float.placement.bottom-end.flip.teleport.offset="{ offset: 8, trap: true }"
                        class="absolute z-10 w-screen divide-y divide-gray-100 rounded-lg shadow-lg ring-1 transition divide-white/5 bg-[#21252b] ring-white/10 !max-w-[14rem] border border-white/5 text-gray-200 overflow-hidden"
                        x-transition:enter-start="opacity-0"
                        x-transition:leave-end="opacity-0"
                        x-ref="panel"
                        x-cloak
                    >
                        <div class="p-2 hover:bg-white/5">
                            <a href="#">Demo user</a>
                        </div>

                        <div class="p-2 hover:bg-white/5">
                            ...
                        </div>

                        <div class="p-2 hover:bg-white/5">
                            <span>Logout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside
            class="flex-grow flex flex-col gap-y-7 overflow-y-auto overflow-x-hidden w-80 min-w-34 fixed top-16 left-0 h-screen bg-[#21252b] border-t border-white/10 px-6 py-8"
        >
            <nav>

                <ul
                    class="text-gray-400 -mx-2 flex flex-col gap-y-7"
                    x-transition:enter="delay-100 lg:transition"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                >
                    <li class="flex flex-col gap-y-1">
                        <ul
                            class="flex flex-col gap-y-1"
                            x-transition:enter="delay-100 lg:transition"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                        >
                            <li class="mb-2">
                                <div class="dark bg-transparent">
                                    {{ $this->form }}
                                </div>
                            </li>

                            <x-navigation.group>
                                <x-navigation.item
                                    label="Dashboard" icon="heroicon-o-home" href="{{ route('dashboard') }}"
                                />
                            </x-navigation.group>

                            <x-navigation.group label="{{ __('General') }}">
                                <x-navigation.item
                                    label="{{ trans_choice('Projects', 2) }}" icon="heroicon-o-rectangle-stack" href="{{ route('projects') }}"
                                />
                            </x-navigation.group>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>
    </div>
</div>
