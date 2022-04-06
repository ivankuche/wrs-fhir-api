<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AllergyIntolerance>
 */
class AllergyIntoleranceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        // Random status
        if ($this->faker->boolean())
        {
            $clinicalStatus= [
                "coding"=>[
                    [
                      "system"=> "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                      "code"=> "active",
                      "display"=> "Active"
                    ]
                ]
            ];
        }
        else
        {
            $clinicalStatus= [
                "coding"=>[
                    [
                      "system"=> "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                      "code"=> "inactive",
                      "display"=> "Inactive"
                    ]
                ]
            ];
        }

        if ($this->faker->boolean())
        {
            $verificationStatus= [
                "coding"=>[
                    [
                      "system"=> "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                      "code"=> "confirmed",
                      "display"=> "Confirmed"
                    ]
                ]
            ];

        }
        else
        {
            $verificationStatus= [
                "coding"=>[
                    [
                      "system"=> "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                      "code"=> "unconfirmed",
                      "display"=> "Unconfirmed"
                    ]
                ]
            ];
        }

        if ($this->faker->boolean())
        {
            $code= [
                "coding"=>[
                    [
                      "system"=> "http://snomed.info/sct",
                      "code"=> "227037002",
                      "display"=> "Fish - dietary (substance)"
                    ]
                ],
                "text"=>"Allergic to fresh fish. Tolerates canned fish"
            ];
        }
        else
        {
            $code= [
                "coding"=>[
                    [
                      "system"=> "http://www.nlm.nih.gov/research/umls/rxnorm",
                      "code"=> "7980",
                      "display"=> "Penicillin G"
                    ]
                ],
                "text"=>"Allergic to Penicillin G"
            ];
        }

        return [
            'clinicalStatus' => $clinicalStatus,
            'verificationStatus' => $verificationStatus,
            'code' => $code
        ];
    }
}
