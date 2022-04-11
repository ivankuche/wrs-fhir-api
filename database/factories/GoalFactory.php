<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'lifecycleStatus' => $this->faker->randomElement(['proposed', 'planned', 'accepted', 'active', 'on-hold', 'completed', 'cancelled', 'entered-in-error', 'rejected']),
            'description' => [
                'text' => $this->faker->text('100'),
            ],
            'startDate' => $this->faker->date(),
            'target'=> [
                'dueDate' => $this->faker->date('Y-m-d'),
            ]
        ];
    }
}
