<div>
    <x-filament::modal id="open-public-key-modal" width="2xl">
        <x-slot name="heading">
            {{ __('Public key') }}
        </x-slot>

        {{-- Modal content --}}
        <div>
            <h3 class="text-center mb-5">{{ __("Copy the public key and paste it on a new line in the server's ~/.ssh/authorized_keys file.") }}</h3>

            <div
                class="fi-input-wrp w-full flex rounded-lg relative shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-textarea"
            >
                    <textarea
                        id="public-key-textarea"
                        class="block h-full w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6"
                        x-on::show-public-key.window="$el.value = $event.detail.public_key"
                        rows="22"
                        x-model="publicKeyContent"
                        readonly
                    ></textarea>

                <div class="absolute top-1 right-1">
                    <x-filament::icon-button
                        x-show="!showingCopiedPublicKeyMessage"
                        icon="heroicon-m-clipboard"
                        class="cursor-pointer p-2 !text-gray-400 opacity-60 hover:opacity-100 hover:!text-primary-500 transition duration-300"
                        x-on:click.stop="copyToClipboard()"
                    />

                    <x-filament::icon-button
                        x-show="showingCopiedPublicKeyMessage"
                        icon="heroicon-o-check-circle"
                        class="cursor-pointer p-2 !text-primary-500 transition duration-300"
                        x-on:click.stop=""
                    />

                    <div
                        x-show="showingCopiedPublicKeyMessage"
                        x-transition
                        class="absolute -top-10 -right-3 flex items-center justify-center text-white bg-black/30 py-1 px-2.5 rounded-xl"
                    >{{ __("Copied") . '!' }}</div>

                </div>
            </div>

            <x-slot name="footerActions">
                <x-filament::button
                    x-on:click="$dispatch('close-modal', { id: 'open-public-key-modal' })"
                >
                    {{ __('Close') }}
                </x-filament::button>
            </x-slot>
        </div>
    </x-filament::modal>
</div>
