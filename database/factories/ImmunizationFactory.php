<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Immunization>
 */
class ImmunizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status'=>  $this->faker->randomElement(['completed', 'entered-in-error', 'not-done']),
            'vaccineCode' => [
                "coding"=> [
                    [
                        "system"=>"urn:oid:1.2.36.1.2001.1005.17",
                        "code" =>"FLUVAX"
                    ]
                ],
                "text"=>"Fluvax (Influenza)"
            ],
            'occurrenceDateTime' => $this->faker->dateTime(),
        ];
    }
}
