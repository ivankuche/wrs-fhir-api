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
            $category= [
                "coding" => [
                    "system"=>"http://snomed.info/sct",
                    "code"=>"252275004",
                    "display"=>"Haematology test"
                ]
            ];
        else
            $category= [
                "coding" => [
                    "system"=>"http://terminology.hl7.org/CodeSystem/v2-0074",
                    "code"=>"HM",
                    "display"=>"Complete Hemogram"
                ]
            ];

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



            /*

        m $table->json('subject')->nullable();
        $table->json('encounter')->nullable();
        $table->dateTime('effectiveDateTime')->nullable();
        $table->json('effectivePeriod')->nullable();
        $table->dateTime('issued')->nullable();
        $table->json('performer')->nullable();
        $table->json('specimen')->nullable();
        $table->json('result')->nullable();
        $table->json('note')->nullable();
        $table->json('imagingStudy')->nullable();
        $table->json('media')->nullable();
        $table->json('composition')->nullable();
        $table->json('resultsInterpreter')->nullable();
        $table->string('conclusion')->nullable();
        $table->json('conclusionCode')->nullable();
        $table->json('presentedForm')->nullable();
*/

        return [
            'status'=>$status,
            'category'=>$category,
            'code'=>$code,
            'effectiveDateTime' => $this->faker->dateTime()
        ];
    }
}
