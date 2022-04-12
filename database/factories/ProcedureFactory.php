<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Procedure>
 */
class ProcedureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['preparation', 'in-progress', 'not-done', 'on-hold', 'stopped', 'completed', 'entered-in-error', 'unknown']),
            "performedDateTime"  => $this->faker->date()
            "code"=> [
                "coding" => [
                    [
                        "system"=>"http://snomed.info/sct",
                        "code"=>"24165007",
                        "display"=>"Alcoholism counseling"
                    ],
                    [
                        "system"=>"http://www.cms.gov/Medicare/Coding/ICD10",
                        "code"=>"HZ30ZZZ",
                        "display"=>"Individual Counseling for Substance Abuse Treatment, Cognitive"
                    ]
                ],
                "text"=>"Alcohol rehabilitation"
            ],
        ];
    }
}
