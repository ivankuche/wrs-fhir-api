<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        $name= $this->faker->firstName($gender);
        $surname= $this->faker->lastName();
        $deceasedBoolean= null;

        if ($this->faker->boolean())
            $deceasedDateTime= $this->faker->date('Y-m-d','now - 2 year');


        $address= $this->faker->streetName;
        $number= $this->faker->buildingNumber;
        $city= $this->faker->city;
        $state= $this->faker->state;
        $postCode= $this->faker->postcode;
        $periodStart= $this->faker->date('Y-m-d','now - 3 year');

        $addressItem= [
            'use'=>'home',
            'type'=>'physical',
            'text'=>$number." ".$address.", ".$city.", ".$state." ".$postCode,
            'line'=> [
                $number." ".$address
            ],
            'city'=>$city,
            'state'=>$state,
            'postalCode'=>$postCode,
            'period'=>[
                'start'=>$periodStart
            ],
        ];

        if ($this->faker->boolean())
        {
            $address2= $this->faker->streetName;
            $number2= $this->faker->buildingNumber;
            $city2= $this->faker->city;
            $state2= $this->faker->state;
            $postCode2= $this->faker->postcode;

            $extraAddress= [
                'use'=>'work',
                'type'=>'physical',
                'text'=>$number2." ".$address2.", ".$city2.", ".$state2." ".$postCode2,
                'line'=> [
                    $number2." ".$address2
                ],
                'city'=>$city2,
                'state'=>$state2,
                'postalCode'=>$postCode2
            ];

            $addressItem+= $extraAddress;
        }

        $contact= null;
        if ($this->faker->boolean())
        {
            $marital= [
                "coding"=> [
                  [
                    "system"=> "http://snomed.info/sct",
                    "code"=> "36629006",
                    "display"=> "Legally married"
                  ],
                  [
                    "system"=> "http://terminology.hl7.org/CodeSystem/v3-MaritalStatus",
                    "code"=> "M"
                  ]
                ]
            ];

            $contact= [
                "relationship"=> [
                    [
                      "coding"=> [
                        [
                          "system"=> "http://terminology.hl7.org/CodeSystem/v2-0131",
                          "code"=> "N"
                        ]
                      ]
                    ]
                ],
                "name"=> [
                    "family"=>$this->faker->lastName(),
                    "given"=> [$this->faker->firstName],
                ],
                "telecom"=> [
                    [
                        "system"=> "phone",
                        "value"=>$this->faker->phoneNumber,
                    ]
                ],
                "address"=> [
                    'use'=>'home',
                    'type'=>'physical',
                    'text'=>$number." ".$address.", ".$city.", ".$state." ".$postCode,
                    'line'=> [
                        $number." ".$address
                    ],
                    'city'=>$city,
                    'state'=>$state,
                    'postalCode'=>$postCode
                ],
                "gender"=> $this->faker->randomElement(['male', 'female']),
            ];
        }
        else
        {
            $marital= [
                "coding"=> [
                  [
                    "system"=> "http://snomed.info/sct",
                    "code"=> "125681006",
                    "display"=> "Single person"
                  ],
                  [
                    "system"=> "http://terminology.hl7.org/CodeSystem/v3-MaritalStatus",
                    "code"=> "U"
                  ]
                ]
            ];

        }

        $id="TEMPID";

        return [
            'identifier'=> [
                'use'=>'usual',
                'value'=>$id,
                'system'=> "http://hospital.smarthealthit.org"
            ],
            'active'=> $this->faker->boolean(),
            'name'=>[
                'use'=>'usual',
                'text'=>$name." ".$surname,
                'family'=>$surname,
                'given'=>[$name]
            ],
            'telecom'=> [[
                    "system"=>"phone",
                    "value"=>$this->faker->phoneNumber,
                    "use"=>"home"
                ],
                [
                    "system"=>"phone",
                    "value"=>$this->faker->phoneNumber,
                    "use"=>"work"
                ]],
            'gender'=>$gender,
            'birthDate'=>$this->faker->date('Y-m-d','now - 5 year'),
            'deceasedBoolean'=>$deceasedBoolean,
            'deceasedDateTime'=>$deceasedDateTime,
            'address'=> $addressItem,
            'maritalStatus'=>$marital,
            'contact'=>$contact,
            'communication'=> [
                'language'=> [
                    'coding'=>[
                        [
                            'system'=>'urn:ietf:bcp:47',
                            'code'=>'en-US',
                            'display'=>'English (United States)'
                        ]
                    ],
                    'text'=> "United States"
                ],
                'preferred'=> true
            ],
        ];
    }
}
