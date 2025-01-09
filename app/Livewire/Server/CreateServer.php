<?php

namespace App\Livewire\Server;

use App\Models\Server;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component;

class CreateServer extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function createServerAction(): Action
    {
        return Action::make('createServer')
            ->label(__("Create") . " " . strtolower(__("Server")))
            ->modalSubmitActionLabel(__('Create'))
            ->form([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->unique(table: 'servers', column: 'name', modifyRuleUsing: fn(Unique $rule) => $rule->where('project_id', auth()->user()->selected_project_id))
                    ->required(),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label(__("Username"))
                            ->required(),

                        Forms\Components\TextInput::make('ip')
                            ->ipv4()
                            ->rules([
                                fn(): Closure => function (string $attribute, $value, Closure $fail) {
                                    if (
                                        Server::query()
                                            ->where('ip_hash', $this->resolveIpHashHmacKey($value))
                                            ->where('project_id', auth()->user()->selected_project_id)
                                            ->exists()
                                    ) {
                                        $fail(__('validation.unique', ['attribute' => 'ip']));
                                    }
                                },
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('port')
                            ->label(__('Port'))
                            ->default(22)
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ])
            ])
            ->action(function (array $data): Server {
                $selectedProjectId = auth()->user()->selected_project_id;
                $keyFileName = "DashOps_{$selectedProjectId}_" . uniqid();

                $newServer = Server::create([
                    ...$data,
                    'ip_hash'       => $this->resolveIpHashHmacKey($data['ip']),
                    'project_id'    => $selectedProjectId,
                    'passphrase'    => Str::password(length: 12, symbols: false),
                    'key_file_name' => $keyFileName,
                ]);

                Notification::make()
                    ->title(__('Server created successfully') . '!')
                    ->success()
                    ->send();

                $this->dispatch('server::created', serverId: $newServer);

                return $newServer;
            });

    }

    protected function resolveIpHashHmacKey(string $ip): string
    {
        $key = auth()->user()->selected_project_id . '_' . config('app.hmac_secret');

        return hash_hmac('sha256', $ip, $key);
    }

    public function render(): View
    {
        return view('livewire.server.create-server');
    }
}
