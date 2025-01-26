<?php

namespace App\Livewire\Server;

use App\Models\Server;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ConnectServer extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function connectServerAction(): Action
    {
        return Action::make('connectServer')
            ->label(__("Connect"))
            ->color('warning')
            ->modalWidth('xl')
            ->form([
                Forms\Components\Select::make('serverId')
                    ->label(__("Server"))
                    ->options(fn() => Server::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required()
            ])
            ->modalSubmitActionLabel(__("Connect"))
            ->action(function (array $data) {
                $this->redirectRoute('servers.connected', ['server' => $data['serverId']]);
            });
    }

    public function render(): View
    {
        return view('livewire.server.connect-server');
    }
}
