<div>
    <x-section>
        <x-header :title="__('Projects')">
            <div>
                <livewire:project.create-project @project::created="$refresh" />
            </div>
        </x-header>
    </x-section>

    {{ $this->table }}

</div>
