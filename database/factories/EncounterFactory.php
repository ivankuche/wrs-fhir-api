<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Encounter>
 */
class EncounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'status'=> $this->faker->randomElement(['planned', 'arrived', 'triaged', 'in-progress', 'onleave', 'finished', 'cancelled']),
            'class'=> [
                "system"=>"http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code"=>"IMP",
                "display"=>"inpatient encounter"
            ],
            'type' => [
                "coding"=> [
                    [
                        "system"=> "http://snomed.info/sct",
                        "code"=> "11429006",
                        "display"=> "Consultation"
                    ]
                ]
            ],
            "hospitalization" => [
                "dischargeDisposition"=> [
                      "coding"=> [
                          [
                            "system"=>"http://snomed.info/sct",
                            "code"=> "306689006",
                            "display"=>"Discharge to home"

                          ]
                      ]
                ]
            ],
            "location"=> [
                [
                    "location"=> [
                        "reference"=>"Location/1",
                        "type"=>"Location",
                        "display"=>"Emergency Waiting Room"

                    ]
                ]
            ],
            "participant"=> [
                [
                    "type"=> [
                        [
                          "coding"=> [
                                [
                                    "system"=>"http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code"=>"PART"
                                ]
                          ]
                        ]
                    ],
                    "individual"=> [
                        "reference" =>"Practitioner/1",
                        "type" => "Practitioner"
                    ],
                    "period"=> [
                        "start" =>Carbon::parse(date("Y-m-d H:i:s",strtotime("now - 1 day")))->toIso8601String(),
                        "end" =>Carbon::parse(date("Y-m-d H:i:s",strtotime("now")))->toIso8601String(),
                    ],
                ]
            ],
            "period"=> [
                "start" => "2013-03-11",
                "end" => "2013-03-20"
            ],
            "reasonCode" => [
                [
                    "text" => "The patient seems to suffer from bilateral pneumonia and renal insufficiency, most likely due to chemotherapy."
                ],
            ],
        ];
    }
}
