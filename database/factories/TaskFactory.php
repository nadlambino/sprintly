<?php

namespace Database\Factories;

use App\Models\PriorityLevel;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'status_id' => Status::where('user_id', 1)->inRandomOrder()->first()->id,
            'user_id' => 1,
            'published_at' => $publishedAt = Carbon::yesterday()->subDays(rand(1, 30)),
            'started_at' => $startedAt = Carbon::parse($publishedAt)->addDays(rand(1, 14)),
            'ended_at' => Carbon::parse($startedAt)->addDays(rand(1, 14)),
            'priority_level_id' => PriorityLevel::where('user_id', 1)->inRandomOrder()->first()->id
        ];
    }
}
