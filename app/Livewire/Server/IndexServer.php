<?php

namespace App\Livewire\Server;

use App\Models\Server;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IndexServer extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

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
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render(): View
    {
        return view('livewire.server.index-server');
    }
}
