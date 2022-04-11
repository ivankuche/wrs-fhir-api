<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medication>
 */
class MedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "code" => [
                "coding"=> [
                    [
                        "system"=>"http://snomed.info/sct",
                        "code"=>"430127000",
                        "display"=>"Oral Form Oxycodone (product)"
                    ]
                ]
            ],
        ];
    }
}
