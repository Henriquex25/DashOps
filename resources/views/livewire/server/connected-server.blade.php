<div>
    <x-section>
        <x-header>
            <div class="w-full flex justify-between">
                <div>
                    <h2 class="text-center text-primary-500 text-2xl font-bold">{{ $server->name }}</h2>
                </div>

                <div>
                    <x-header.actions>
                        <livewire:server.actions.disconnect-server :server="$server" />
                    </x-header.actions>
                </div>
            </div>
        </x-header>
    </x-section>

    <livewire:server.stats :server="$server" />

    <livewire:server.list-services :server="$server" />
</div>
