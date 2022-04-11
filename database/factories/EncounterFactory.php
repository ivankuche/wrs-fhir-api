<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Encounter>
 */
class EncounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status'=> $this->faker->randomElement(['planned', 'arrived', 'triaged', 'in-progress', 'onleave', 'finished', 'cancelled']),
            'class'=> [
                [
                    "system"=>"http://terminology.hl7.org/CodeSystem/v3-ActCode",
                    "code"=>"IMP",
                    "display"=>"inpatient encounter"
                ]
            ]
        ];
    }
}
