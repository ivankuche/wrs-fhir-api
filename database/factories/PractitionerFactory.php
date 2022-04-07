<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Practitioner>
 */
class PractitionerFactory extends Factory
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
        $deceasedBoolean= $this->faker->boolean();
        $deceasedDateTime= null;

        if ($deceasedBoolean)
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

        $id="TEMPID";


        return [
            'identifier'=> [
                'use'=>'usual',
                'value'=>$id,
                //'system'=> "http://www.acme.org/practitioners",
                "type"=> [
                    "coding"=> [
                        [
                            "system"=> "http://terminology.hl7.org/CodeSystem/v2-0203",
                            "code"=> "NPI"
                        ]
                    ]
                ],
//                  "system": "http://hl7.org/fhir/sid/us-npi",

            ],
            'active'=> $this->faker->boolean(),
            'name'=>[
                'use'=>'usual',
                'text'=>$name." ".$surname,
                'family'=>$surname,
                'given'=>$name
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
            'address'=> $addressItem,
            'qualification' => [],
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
