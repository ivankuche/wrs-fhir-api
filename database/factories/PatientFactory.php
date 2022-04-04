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
        $ultimo= Patient::latest()->get('id')->toArray();

        if (count($ultimo)==0)
            $id=1;
        else{
            print_r($ultimo);
            die();
            $id= $ultimo['id'] +1;
        }

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

        $addressItem= [
            'use'=>'home',
            'type'=>'physical',
            'text'=>$number." ".$address.", ".$city.", ".$state." ".$postCode,
            'line'=> [
                $number." ".$address
            ],
            'city'=>$city,
            'state'=>$state,
            'postalCode'=>$postCode
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
                    "given"=> $this->faker->firstName,
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

        return [
            'identifier'=> json_encode([
                'use'=>'usual',
                'value'=>$id
            ]),
            'active'=> $this->faker->boolean(),
            'name'=> json_encode([[
                'use'=>'usual',
                'text'=>$name." ".$surname,
                'family'=>$surname,
                'given'=>$name
            ]]),
            'telecom'=> json_encode([
                [
                    "system"=>"phone",
                    "value"=>$this->faker->phoneNumber,
                    "use"=>"home"
                ],
                [
                    "system"=>"phone",
                    "value"=>$this->faker->phoneNumber,
                    "use"=>"work"
                ]
            ]),
            'gender'=>$gender,
            'birthdate'=>$this->faker->date('Y-m-d','now - 5 year'),
            'deceasedBoolean'=>$deceasedBoolean,
            'deceasedDateTime'=>$deceasedDateTime,
            'address'=> json_encode($addressItem),
            'maritalStatus'=>json_encode($marital),
            'contact'=>json_encode($contact)
        ];

/*

  "contact" : [{ // A contact party (e.g. guardian, partner, friend) for the patient
    "relationship" : [{ CodeableConcept }], // The kind of relationship
    "name" : { HumanName }, // A name associated with the contact person
    "telecom" : [{ ContactPoint }], // A contact detail for the person
    "address" : { Address }, // Address for the contact person
    "gender" : "<code>", // male | female | other | unknown
    "organization" : { Reference(Organization) }, // C? Organization that is associated with the contact
    "period" : { Period } // The period during which this contact person or organization is valid to be contacted relating to this patient
  }],
  "communication" : [{ // A language which may be used to communicate with the patient about his or her health
    "language" : { CodeableConcept }, // R!  The language which can be used to communicate with the patient about his or her health
    "preferred" : <boolean> // Language preference indicator
  }],
  "generalPractitioner" : [{ Reference(Organization|Practitioner|
   PractitionerRole) }], // Patient's nominated primary care provider
  "managingOrganization" : { Reference(Organization) }, // Organization that is the custodian of the patient record
  "link" : [{ // Link to another patient resource that concerns the same actual person
    "other" : { Reference(Patient|RelatedPerson) }, // R!  The other patient or related person resource that the link refers to
    "type" : "<code>" // R!  replaced-by | replaces | refer | seealso
  }]
  */


    }
}
