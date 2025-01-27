<?php

namespace App\Livewire\Server\Actions;

use App\Enums\SSHServerResponse;
use App\Models\Server;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DisconnectServer extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    #[Locked]
    public Server $server;

    public function disconnectServerAction(): Action
    {
        return Action::make('disconnectServer')
            ->label(__("Disconnect"))
            ->color('danger')
            ->icon('heroicon-o-signal-slash')
            ->requiresConfirmation()
            ->action(function () {
                $response = $this->server
                    ->ssh()
                    ->disconnect()
                    ->run();

                if ($response->get('status') === SSHServerResponse::Success->value) {
                    Notification::make()
                        ->success()
                        ->title(__('Disconnected'))
                        ->send();

                    return redirect()->route('servers.index');
                }

                Notification::make()
                    ->danger()
                    ->title(__('Disconnect failed'))
                    ->send();
            });
    }

    public function render(): View
    {
        return view('livewire.server.actions.disconnect-server');
    }
}
