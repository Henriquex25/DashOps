<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\RawJs;
use Livewire\Attributes\On;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms;

class NavigationBar extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(['project' => auth()->user()->selected_project_id]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project')
                    ->label('')
                    ->searchable()
                    ->options(
                        fn(): array => Project::query()
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->placeholder(__('Select a project...'))
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn($state) => auth()->user()->update(['selected_project_id' => $state])),
            ])
            ->statePath('data');
    }


    public function render()
    {
        return view('livewire.navigation-bar')
            ->layout('layouts.base');
    }
}
