<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarePlan>
 */
class CarePlanFactory extends Factory
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
            $status= "active";
        else
            $status= "inactive";

        // Random status
        if ($this->faker->boolean())
            $intent= "proposal";
        else
            $intent= "plan";

        if ($this->faker->boolean())
        {
            $category= [
                "coding"=> [
                    "system"=> "http://hl7.org/fhir/us/core/CodeSystem/careplan-category",
                    "code"=> "assess-plan",
                    "display"=> "Assessment and Plan of Treatment"
                ],
                "text"=>"Weight management plan"
            ];

        }
        else
        {
            $category= [
                "coding"=> [
                    "system"=> "http://hl7.org/fhir/us/core/CodeSystem/careplan-category",
                    "code"=> "assess-plan",
                    "display"=> "Assessment and Plan of Treatment"
                ],
                "text"=>"Assessment and Plan of Treatment"
            ];
        }


        return [
            'status' => $status,
            'intent' => $intent,
            'category' => $category
        ];
    }
}
