<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms;

class NavigationBar extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project')
                    ->label('')
                    ->options(
                        fn(): array => Project::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->placeholder(__('Select a project...'))
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function (string $project): void {
                        dd($project);
                    }),
            ])
            ->statePath('data');
    }

    public function render()
    {
        return view('livewire.navigation-bar')
            ->layout('layouts.base');
    }
}
