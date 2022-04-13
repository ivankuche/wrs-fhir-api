<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiagnosticReport>
 */
class DiagnosticReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        if ($this->faker->boolean())
            $status='registered';
        else
            $status='final';

        if ($this->faker->boolean())
            $code= [
                "coding" => [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"58410-2",
                        "display"=>"Complete blood count (hemogram) panel - Blood by Automated count"
                    ],
                ],
                "text"=>"Complete Blood Count"
            ];
        else
            $code= [
                "coding" => [
                    [
                        "system"=>"http://snomed.info/sct",
                        "code"=>"104177005",
                        "display"=>"Blood culture for bacteria, including anaerobic screen"
                    ],
                ],
            ];


        return [
            'status'=>$status,
            'code'=>$code,
            'effectiveDateTime' => $this->faker->dateTime(),
            'issued' => $this->faker->dateTime(),
            "presentedForm" => [
                "url"=>"http://www.demoreport.com/demoreport/".$this->faker->firstName,
            ],
    ]   ;
    }
}
