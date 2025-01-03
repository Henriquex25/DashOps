<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;
use Filament\Forms;

class CreateProject extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function createPostAction(): Action
    {
        return Action::make('createPost')
            ->label(__('Create project'))
            ->form([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->unique()
                    ->required(),

                Forms\Components\TextInput::make('description')
                    ->label(__('Description')),

                Forms\Components\FileUpload::make('logo')
                    ->label(__('Logo'))
                    ->image()
                    ->imageEditor(),
            ])
            ->action(function (array $data, Action $action): Project {
                $data['owner_id'] = auth()->id();

                $newProject = Project::create($data);

                auth()->user()->update(['selected_project_id' => $newProject->id]);

                $this->dispatch('project::created', projectId: $newProject->id);

                Notification::make()
                    ->success()
                    ->title(__('Project created successfully') . '!')
                    ->send();

                return $newProject;
            });
    }

    public function render()
    {
        return view('livewire.project.create-project');
    }
}
