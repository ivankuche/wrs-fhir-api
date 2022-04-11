<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Observation>
 */
class ObservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['registered', 'preliminary', 'final', 'amended']),
            "code"=> [
                "coding"=> [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"72166-2",
                        "display"=>"Tobacco smoking status"
                    ]
                ]
            ],
            //
        ];
    }
}
