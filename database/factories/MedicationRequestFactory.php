<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicationRequest>
 */
class MedicationRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['active', 'on-hold', 'cancelled', 'completed', 'entered-in-error', 'stopped', 'draft', 'unknown']),
            //'intent' => $this->faker->randomElement(['proposal', 'plan', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'option']),
            'medicationReference' => [
                "reference"=> "Medication/1",
                "type"=>"Medication"
            ],
            'authoredOn' => $this->faker->dateTime(),
            "requester"=> [
                "reference"=>"Practitioner/1",
                "type"=> "Practitioner"
            ],
            "reportedBoolean"=>true,
            "encounter" => [
                "reference"=>"Encounter/1",
                "type"=>"Encounter",
            ],
            /*
            "dose"=>[
                "dosageInstruction"=> [
                    [
                        "text"=>"Take 4 tablets daily for 7 days starting January 16, 2015",
                    ]
                ],
            ],*/
            "dosageInstruction"=> [
                [
                    "text"=>"Take 4 tablets daily for 7 days starting January 16, 2015",
                ]
            ],
    ];
    }
}
