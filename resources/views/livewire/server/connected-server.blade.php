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

    <div class="flex flex-col">
        <div>
            <input type="text" wire:model="command" class="text-black" />
            <button wire:click='runCommand'>
                Executar comando
            </button>
        </div>
        <span>Saída: </span>
        <textarea wire:model="output" class="text-black"></textarea>
        <textarea wire:model="erroOutput" class="mt-5 text-red-500"></textarea>
    </div>
</div>
