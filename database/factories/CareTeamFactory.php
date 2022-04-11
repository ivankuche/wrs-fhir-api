<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CareTeam>
 */
class CareTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        if ($this->faker->boolean())
        {
            $category= [
                "coding"=> [
                    [
                        "system"=> "http://loinc.org",
                        "code"=> "LA27976-2",
                        "display"=> "Encounter-focused care team"
                    ]
                ],
            ];
        }
        else
        {
            $category= [
                "coding"=> [
                    [
                        "system"=> "http://loinc.org",
                        "code"=> "LA27980-4",
                        "display"=> "Clinical research-focused care team"
                    ]
                ],
            ];
        }

        if ($this->faker->boolean())
        {
            $participant= [
                "role"=> [
                    [
                        "coding"=> [
                            [
                                "system"=> "http://snomed.info/sct",
                                "code"=> "17561000",
                                "display"=> "Cardiologist"
                            ]
                        ],
                    ]
                ],
                "member" => [
                    'reference'=>"Practitioner/1",
                    'type'=>"Practitioner",
                    //"display" => "Lawrence Gordon, MD"
                ]

            ];
        }
        else
        {
            $participant= [
                "role"=> [
                    "coding"=> [
                        "system"=> "http://snomed.info/sct",
                        "code"=> "453231000124104",
                        "display"=> "Primary care provider"
                    ],
                ],
                "member" => [
                    "reference" => "Practitioner/2",
                    //"display" => "Giselle Rimolo, MD"
                ]

            ];
        }

        return [
            'category' => $category,
            'participant' => $participant

        ];
    }
}
