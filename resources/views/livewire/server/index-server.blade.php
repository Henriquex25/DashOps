<div x-data="indexServer">
    <x-section>
        <x-header :title="__('Servers')">
            <livewire:server.create-server @server::created="$refresh"/>
        </x-header>
    </x-section>

    {{ $this->table }}

    @include('livewire.server.show-public-key-modal')
</div>

@script
<script>
    Alpine.data('indexServer', () => {
        return {
            publicKeyContent: @entangle('publicKeyContent'),
            showingCopiedPublicKeyMessage: false,
            copyToClipboard() {
                if (this.showingCopiedPublicKeyMessage) return;

                this.$clipboard(this.publicKeyContent)
                this.showCopiedMessage()
            },
            showCopiedMessage() {
                this.showingCopiedPublicKeyMessage = true
                setTimeout(() => this.showingCopiedPublicKeyMessage = false, 4000)
            }
        }
    })
</script>
@endscript
