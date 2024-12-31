<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Filament\Actions\EditAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Component;
use Filament\Tables\Actions;

class IndexProject extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;


    public function table(Table $table): Table
    {
        return $table
            ->query(Project::query())
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('Logo')),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Actions\ActionGroup::make([

                    Actions\EditAction::make()
                        ->label(__('Edit'))
                        ->modalHeading(__('Edit') . ' ' . mb_strtolower(__('Project')))
                        ->color('warning'),

                    Actions\DeleteAction::make()
                        ->modalHeading(__('Delete') . ' ' . mb_strtolower(__('Project'))),
                ])
            ])
            ->bulkActions([
                Actions\BulkAction::make('delete')
                    ->label(__('Delete'))
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
            ]);
    }

    public function render()
    {
        return view('livewire.project.index-project')
            ->title(__('Projects'));
    }
}
