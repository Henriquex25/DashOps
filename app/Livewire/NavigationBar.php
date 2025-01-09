<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class NavigationBar extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    #[Locked]
    public bool $hasSelectedProject = false;

    public function mount(): void
    {
        $this->form->fill(['project' => auth()->user()->selected_project_id]);

        $this->checkIfHasSelectedProject();
    }

    protected function checkIfHasSelectedProject(): void
    {
        $this->hasSelectedProject = auth()->user()->hasSelectedProject();
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
                    ->afterStateUpdated(function ($state) {
                        if (is_null($state)) {
                            $this->hasSelectedProject = false;
                        }

                        auth()->user()->update(['selected_project_id' => $state]);

                        $this->checkIfHasSelectedProject();
                    }),
            ])
            ->statePath('data');
    }

    #[On('project::created')]
    public function refresh(int $projectId): void
    {
        $this->form->fill(['project' => $projectId]);

        auth()->user()->refresh();

        $this->checkIfHasSelectedProject();
    }


    public function render()
    {
        return view('livewire.navigation-bar')
            ->layout('layouts.base');
    }
}
