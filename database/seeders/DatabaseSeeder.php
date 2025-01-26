<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'dev@dev.com',
        ]);

        $newProject = Project::create([
            'owner_id' => 1,
            'name'     => 'Project 1',
        ]);

        User::first()->update(['selected_project_id' => $newProject->id]);
    }
}
