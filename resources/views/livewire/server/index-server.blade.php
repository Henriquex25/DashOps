<div>
    <x-section>
        <x-header :title="__('Servers')">
            <livewire:server.create-server @server::created="$refresh"/>
        </x-header>
    </x-section>

    {{ $this->table }}
</div>
