<?php

namespace App\Livewire\Server;

use App\Models\Server;
use Closure;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class IndexServer extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    #[Locked]
    public string|bool $publicKeyContent = false;

    #[On("server::get-public-key")]
    #[On("server::created")]
    public function getPublicKey(int $serverId): void
    {
        $server = Server::with('project')->findOrFail($serverId);
        $keyFileName = $server->key_file_name;
        $keyPath = $server->getKeyPath();

        $this->publicKeyContent = file_get_contents($keyPath . $keyFileName . '.pub');

        $this->dispatch('open-modal', id: 'open-public-key-modal');
    }

    public function mount(): void
    {
        if (!auth()->user()->hasSelectedProject()) {
            $this->redirectRoute('projects.index');

            Notification::make()
                ->warning()
                ->title(__('Please select a project'))
                ->send();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Server::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('username')
                    ->label(__('Username'))
                    ->copyable()
                    ->copyMessage('Username copied!')
                    ->copyMessageDuration(1500)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ip')
                    ->label(__('IP'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage('IP copied!')
                    ->copyMessageDuration(1500)
                    ->sortable(),

                Tables\Columns\TextColumn::make('port')
                    ->label(__('Port'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    //->color(fn(string $state): string => match ($state) {
                    //    'Online' => 'success',
                    //    'Offline' => 'danger',
                    //})
                    ->color(function (string $state): string {
                        dd($state);
                    })
                    ->formatStateUsing(function (Server $record) {
                        $result = $record->ssh()->ping();
                        ds($result);
                        return $result ? __('Online') : __('Offline');
                    }),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\Action::make('getPublicKey')
                        ->label(__("Public key"))
                        ->color('info')
                        ->icon('heroicon-o-key')
                        ->action(fn(Server $record
                        ) => $this->dispatch('server::get-public-key', serverId: $record->id)),

                    Tables\Actions\EditAction::make()
                        ->label(__('Edit'))
                        ->modalHeading(__('Edit') . ' ' . mb_strtolower(__('Server')))
                        ->color('warning')
                        ->form($this->getFormSchema())
                        ->using(fn(Server $record, array $data) => $record->update([
                            'name'     => $data['name'],
                            'username' => $data['username'],
                            'ip'       => $data['ip'],
                            'ip_hash'  => Server::resolveIpHashHmacKey($data['ip']),
                            'port'     => $data['port'],
                        ]))
                        ->successNotificationTitle(sprintf("%s %s!", __('Server'), __('updated successfully'))),

                    Tables\Actions\DeleteAction::make()
                        ->label(__('Delete'))
                        ->color('danger')
                        ->using(function (Tables\Actions\DeleteAction $action, Model $record): void {
                            $result = $record->delete();

                            if (!$result) {
                                $action->failure();

                                return;
                            }

                            $action->success();
                        })
                        ->successNotificationTitle(sprintf("%s %s!", __('Server'), __('deleted successfully'))),

                    Tables\Actions\Action::make('force delete')
                        ->label(__('Force delete'))
                        ->icon("heroicon-o-exclamation-circle")
                        ->visible(fn(Server $record) => $record->trashed())
                        ->action(function (Server $record) {
                            $record->forceDelete();

                            Notification::make()
                                ->success()
                                ->title(sprintf("%s %s!", __('Server'), __('force deleted successfully')))
                                ->send();
                        })
                ])
            ])
            ->bulkActions([
                // ...
            ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label(__('Name'))
                ->unique(
                    table          : 'servers',
                    column         : 'name',
                    ignoreRecord   : true,
                    modifyRuleUsing: fn(Unique $rule
                    ) => $rule->where('project_id', auth()->user()->selected_project_id))
                ->required(),

            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\TextInput::make('username')
                        ->label(__("Username"))
                        ->required(),

                    Forms\Components\TextInput::make('ip')
                        ->ipv4()
                        ->rules([
                            fn(Model $record): Closure => function (string $attribute, $value, Closure $fail) use (
                                $record
                            ) {
                                if (
                                    Server::query()
                                        ->where('ip_hash', Server::resolveIpHashHmacKey($value))
                                        ->where('project_id', auth()->user()->selected_project_id)
                                        ->where('id', '!=', $record->id)
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
        ];
    }

    public function render(): View
    {
        return view('livewire.server.index-server');
    }
}
