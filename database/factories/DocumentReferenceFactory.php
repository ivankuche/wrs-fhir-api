<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentReference>
 */
class DocumentReferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {



        return [
            'status' => 'current',
            'identifier'=> [],
            'docStatus' => 'preliminary',
            'type'=> [
                'coding'=> [
                    [
                        "system"=>"http://loinc.org",
                        "code"=>"34108-1",
                        "display"=>"Outpatient Note"
                    ]
                ]
            ],
            "category"=> [
                [
                    "coding" => [
                        [
                            "system"=>"http://ihe.net/xds/connectathon/classCodes",
                            "code"=>"History and Physical",
                            "display"=>"History and Physical"
                        ]
                    ]
                ]
            ],
            'date'=>$this->faker->date(),
            'custodian'=> [
                'reference' => 'Organization/1'
            ],
            /*
            'content' => [
                'attachment' => [
                    'url' => 'http://www.demodocument.com/demodocument/'.$this->faker->firstName().'.txt',
                    'contentType' => 'text/plain',
                ],
                "format" => [
                    "system"=>"urn:oid:1.3.6.1.4.1.19376.1.2.3",
                    "code"=>"urn:ihe:pcc:handp:2008"
                ],
            ],
            */
            "context"=> [

                "encounter"=> [
                    [
                      "reference"=> "Encounter/1",
                      "type"=>"Encounter"
                    ]
                ],
                "period"=> [
                    "start"=>"2004-12-23T08:00:00+10:00",
                    "end"=>"2004-12-23T08:01:00+10:00"
                ]
            ],

            /*
            "category" =>[
                [
                    "coding" => [
                        [
                          "system" => "http://loinc.org",
                          "code" => "47039-3",
                          "display" => "Inpatient Admission history and physical note"
                        ]
                    ]
                ]
            ],

            */

        ];
    }
}
