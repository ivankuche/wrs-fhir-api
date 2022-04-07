<?php

namespace Database\Factories;

use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Condition>
 */
class ConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $clinicalStatus= [
            "coding" => [
                [
                    "system"=>"http://terminology.hl7.org/CodeSystem/condition-clinical",
                    "code"=>"active"
                ]
            ]
        ];

        $verificationStatus= [
            "coding" => [
                [
                    "system"=>"http://terminology.hl7.org/CodeSystem/condition-ver-status",
                    "code"=>"confirmed"
                ]
            ]
        ];

        if ($this->faker->boolean())
        {
            $category= [
                "coding"=> [
                    "system"=> "http://terminology.hl7.org/CodeSystem/condition-category",
                    "code"=> "encounter-diagnosis",
                    "display"=> "Encounter Diagnosis"
                ],
            ];
        }
        else
        {
            $category= [
                "coding"=> [
                    "system"=> "http://snomed.info/sct",
                    "code"=> "439401001",
                    "display"=> "Diagnosis"
                ],
            ];
        }

        if ($this->faker->boolean())
        {
            $code= [
                "coding"=> [
                    "system"=> "http://snomed.info/sct",
                    "code"=> "39065001",
                    "display"=> "Burn of ear"
                ],
                "text"=>"Burnt Ear"
            ];
        }
        else
        {
            $code= [
                "coding"=> [
                    "system"=> "http://snomed.info/sct",
                    "code"=> "25906001",
                    "display"=> "Disorder of ear"
                ],
                "text"=>"Ear disorder"
            ];
        }

        return [
            'clinicalStatus' => $clinicalStatus,
            'verificationStatus'=>$verificationStatus,
            'category'=>$category,
            'code'=>$code
        ];
    }
}
