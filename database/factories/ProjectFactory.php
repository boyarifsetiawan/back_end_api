<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'skill_id' => Skill::factory(),
            'name' => fake()->catchPhrase(),
            'image' => fake()->imageUrl(640, 480, 'business', true),
            'project_url' => fake()->url(),
        ];
    }
}
