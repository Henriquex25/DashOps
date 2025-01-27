<div>
    <x-section>
        <x-header :title="__('Projects')">
            <x-header.actions>
                <livewire:project.create-project @project::created="$refresh" />
            </x-header.actions>
        </x-header>
    </x-section>

    {{ $this->table }}

</div>
