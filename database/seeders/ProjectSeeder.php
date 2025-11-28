<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = Skill::all();

        foreach ($skills as $skill) {
            Project::factory()->count(3)->create([
                'skill_id' => $skill->id,
            ]);
        }
    }
}
